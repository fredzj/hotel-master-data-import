<?php

namespace App\Services\PmsAdapters;

use App\Contracts\PmsAdapterInterface;
use App\Models\Hotel;
use App\Models\PmsSystem;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\RoomAttribute;
use App\Models\MewsEnterprise;
use App\Models\MewsService;
use App\Models\MewsResource;
use App\Models\MewsResourceCategory;
use App\Models\MewsResourceFeature;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MewsAdapter implements PmsAdapterInterface
{
    private string $clientToken;
    private string $accessToken;
    private string $client;
    private string $baseUrl;

    public function __construct()
    {
        $this->clientToken = config('services.mews.client_token');
        $this->accessToken = config('services.mews.access_token');
        $this->client = config('services.mews.client', 'HotelMasterDataImport');
        $this->baseUrl = config('services.mews.base_url');
    }

    public function authenticate(): bool
    {
        // Mews uses pre-configured tokens, so we just validate they exist
        if (empty($this->clientToken) || empty($this->accessToken)) {
            Log::error('Mews authentication failed: Missing client token or access token');
            return false;
        }

        // Test authentication by calling the configuration endpoint
        try {
            $response = $this->makeRequest('/api/connector/v1/configuration/get', []);
            
            if ($response->successful()) {
                Log::info('Mews authentication successful');
                return true;
            }

            Log::error('Mews authentication failed: Invalid tokens', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Mews authentication error: ' . $e->getMessage());
            return false;
        }
    }

    public function testConnection(): bool
    {
        return $this->authenticate();
    }

    public function importHotels(): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Mews API');
        }

        try {
            // Try portfolio-level access first
            $response = $this->makeRequest('/api/connector/v1/enterprises/getAll', []);
            
            if ($response->successful()) {
                $data = $response->json();
                $enterprises = $data['Enterprises'] ?? [];
                $imported = [];

                foreach ($enterprises as $enterpriseData) {
                    $enterprise = $this->importEnterprise($enterpriseData);
                    $imported[] = $enterprise;
                }

                Log::info("Imported " . count($imported) . " enterprises from Mews (portfolio access)");
                return $imported;
            }

            // If portfolio access fails, fall back to single enterprise from configuration
            $configResponse = $this->makeRequest('/api/connector/v1/configuration/get', []);
            
            if (!$configResponse->successful()) {
                throw new \Exception('Failed to fetch enterprise data from Mews API');
            }

            $configData = $configResponse->json();
            $enterpriseData = $configData['Enterprise'] ?? null;
            
            if (!$enterpriseData) {
                throw new \Exception('No enterprise data found in Mews configuration response');
            }

            $imported = [];
            $enterprise = $this->importEnterprise($enterpriseData);
            $imported[] = $enterprise;

            Log::info("Imported 1 enterprise from Mews (single enterprise access)");
            return $imported;

        } catch (\Exception $e) {
            Log::error('Error importing enterprises from Mews: ' . $e->getMessage());
            throw $e;
        }
    }

    public function importRoomTypes(string $hotelId = ''): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Mews API');
        }

        try {
            // First import services - try services endpoint, fallback to configuration
            $servicesResponse = $this->makeRequest('/api/connector/v1/services/getAll', []);
            $services = [];
            
            if ($servicesResponse->successful()) {
                $servicesData = $servicesResponse->json();
                $services = $servicesData['Services'] ?? [];
            }
            
            // If no services from dedicated endpoint, try configuration
            if (empty($services)) {
                $configResponse = $this->makeRequest('/api/connector/v1/configuration/get', []);
                if ($configResponse->successful()) {
                    $configData = $configResponse->json();
                    $services = $configData['Services'] ?? [];
                }
            }

            $imported = [];
            foreach ($services as $serviceData) {
                try {
                    $this->importService($serviceData);
                    $imported[] = $serviceData['Id'] ?? 'unknown';
                } catch (\Exception $e) {
                    Log::warning("Failed to import service: " . $e->getMessage(), ['data' => $serviceData]);
                }
            }
            
            if (empty($services)) {
                Log::info("No services found in Mews API (demo environment limitation)");
            }

            // Then import resource categories (room types)
            $resourceCategoriesResponse = $this->makeRequest('/api/connector/v1/resources/getAll', [
                'Extent' => [
                    'ResourceCategories' => true,
                    'Resources' => false,
                    'ResourceCategoryAssignments' => false,
                    'ResourceCategoryImageAssignments' => false,
                    'ResourceFeatures' => false,
                    'ResourceFeatureAssignments' => false,
                    'Inactive' => false
                ]
            ]);

            if (!$resourceCategoriesResponse->successful()) {
                throw new \Exception('Failed to fetch resource categories from Mews API');
            }

            $resourceCategoriesData = $resourceCategoriesResponse->json();
            $resourceCategories = $resourceCategoriesData['ResourceCategories'] ?? [];

            foreach ($resourceCategories as $categoryData) {
                try {
                    $category = $this->importResourceCategory($categoryData);
                    $imported[] = $category;
                } catch (\Exception $e) {
                    Log::warning("Failed to import resource category: " . $e->getMessage(), ['data' => $categoryData]);
                }
            }
            
            if (empty($resourceCategories)) {
                Log::info("No resource categories found in Mews API (demo environment limitation)");
            }

            Log::info("Imported " . count($imported) . " resource categories from Mews");
            return $imported;

        } catch (\Exception $e) {
            Log::error('Error importing resource categories from Mews: ' . $e->getMessage());
            throw $e;
        }
    }

    public function importRooms(string $hotelId = ''): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Mews API');
        }

        try {
            $response = $this->makeRequest('/api/connector/v1/resources/getAll', [
                'Extent' => [
                    'Resources' => true,
                    'ResourceCategories' => false,
                    'ResourceCategoryAssignments' => true,
                    'ResourceCategoryImageAssignments' => false,
                    'ResourceFeatures' => false,
                    'ResourceFeatureAssignments' => false,
                    'Inactive' => false
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch resources from Mews API');
            }

            $data = $response->json();
            $resources = $data['Resources'] ?? [];
            $categoryAssignments = $data['ResourceCategoryAssignments'] ?? [];
            $imported = [];

            // Import resources in hierarchical order (parents first)
            $this->importResourcesHierarchically($resources, $imported);
            

            // Import category assignments
            foreach ($categoryAssignments as $assignment) {
                $this->importResourceCategoryAssignment($assignment);
            }

            Log::info("Imported " . count($imported) . " resources from Mews");
            return $imported;

        } catch (\Exception $e) {
            Log::error('Error importing resources from Mews: ' . $e->getMessage());
            throw $e;
        }
    }

    public function importRoomAttributes(): int
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Mews API');
        }

        try {
            $response = $this->makeRequest('/api/connector/v1/resources/getAll', [
                'Extent' => [
                    'Resources' => false,
                    'ResourceCategories' => false,
                    'ResourceCategoryAssignments' => false,
                    'ResourceCategoryImageAssignments' => false,
                    'ResourceFeatures' => true,
                    'ResourceFeatureAssignments' => true,
                    'Inactive' => false
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch resource features from Mews API');
            }

            $data = $response->json();
            $features = $data['ResourceFeatures'] ?? [];
            $featureAssignments = $data['ResourceFeatureAssignments'] ?? [];
            $imported = 0;

            foreach ($features as $featureData) {
                try {
                    $this->importResourceFeature($featureData);
                    $imported++;
                } catch (\Exception $e) {
                    Log::warning("Failed to import resource feature: " . $e->getMessage(), ['data' => $featureData]);
                }
            }
            
            if (empty($features)) {
                Log::info("No resource features found in Mews API (demo environment limitation)");
            }

            // Import feature assignments
            foreach ($featureAssignments as $assignment) {
                try {
                    $this->importResourceFeatureAssignment($assignment);
                } catch (\Exception $e) {
                    Log::warning("Failed to import resource feature assignment: " . $e->getMessage(), ['assignment' => $assignment]);
                }
            }

            Log::info("Imported {$imported} resource features from Mews");
            return $imported;

        } catch (\Exception $e) {
            Log::error('Error importing resource features from Mews: ' . $e->getMessage());
            throw $e;
        }
    }

    private function importEnterprise(array $data): MewsEnterprise
    {
        $address = $data['Address'] ?? [];
        $subscription = $data['Subscription'] ?? [];

        return MewsEnterprise::updateOrCreate(
            ['mews_id' => $data['Id']],
            [
                'external_identifier' => $data['ExternalIdentifier'] ?? null,
                'holding_key' => $data['HoldingKey'] ?? null,
                'chain_id' => $data['ChainId'] ?? null,
                'chain_name' => $data['ChainName'] ?? null,
                'name' => $data['Name'],
                'time_zone_identifier' => $data['TimeZoneIdentifier'],
                'legal_environment_code' => $data['LegalEnvironmentCode'] ?? null,
                'accommodation_environment_code' => $data['AccommodationEnvironmentCode'] ?? null,
                'accounting_environment_code' => $data['AccountingEnvironmentCode'] ?? null,
                'tax_environment_code' => $data['TaxEnvironmentCode'] ?? null,
                'default_language_code' => $data['DefaultLanguageCode'] ?? null,
                'pricing' => $data['Pricing'] ?? null,
                'tax_precision' => $data['TaxPrecision'] ?? null,
                'website_url' => $data['WebsiteUrl'] ?? null,
                'email' => $data['Email'] ?? null,
                'phone' => $data['Phone'] ?? null,
                'logo_image_id' => $data['LogoImageId'] ?? null,
                'cover_image_id' => $data['CoverImageId'] ?? null,
                'address_id' => $address['Id'] ?? null,
                'address_line1' => $address['Line1'] ?? null,
                'address_line2' => $address['Line2'] ?? null,
                'city' => $address['City'] ?? null,
                'postal_code' => $address['PostalCode'] ?? null,
                'country_code' => $address['CountryCode'] ?? null,
                'country_subdivision_code' => $address['CountrySubdivisionCode'] ?? null,
                'latitude' => $address['Latitude'] ?? null,
                'longitude' => $address['Longitude'] ?? null,
                'tax_identifier' => $subscription['TaxIdentifier'] ?? null,
                'raw_data' => $data,
                'linked_utc' => isset($data['LinkedUtc']) ? \Carbon\Carbon::parse($data['LinkedUtc']) : null,
                'mews_created_utc' => isset($data['CreatedUtc']) ? \Carbon\Carbon::parse($data['CreatedUtc']) : null,
                'mews_updated_utc' => isset($data['UpdatedUtc']) ? \Carbon\Carbon::parse($data['UpdatedUtc']) : null,
                'last_imported_at' => now(),
            ]
        );
    }

    private function importService(array $data): void
    {
        $serviceData = $data['Data']['Value'] ?? [];

        MewsService::updateOrCreate(
            ['mews_id' => $data['Id']],
            [
                'enterprise_id' => $data['EnterpriseId'],
                'external_identifier' => $data['ExternalIdentifier'] ?? null,
                'name' => $data['Name'],
                'is_active' => $data['IsActive'] ?? true,
                'bill_as_package' => $data['Options']['BillAsPackage'] ?? false,
                'data_discriminator' => $data['Data']['Discriminator'],
                'start_offset' => $serviceData['StartOffset'] ?? null,
                'end_offset' => $serviceData['EndOffset'] ?? null,
                'occupancy_start_offset' => $serviceData['OccupancyStartOffset'] ?? null,
                'occupancy_end_offset' => $serviceData['OccupancyEndOffset'] ?? null,
                'time_unit_period' => $serviceData['TimeUnitPeriod'] ?? null,
                'promotion_before_checkin' => $serviceData['Promotions']['BeforeCheckIn'] ?? false,
                'promotion_after_checkin' => $serviceData['Promotions']['AfterCheckIn'] ?? false,
                'promotion_during_stay' => $serviceData['Promotions']['DuringStay'] ?? false,
                'promotion_before_checkout' => $serviceData['Promotions']['BeforeCheckOut'] ?? false,
                'promotion_after_checkout' => $serviceData['Promotions']['AfterCheckOut'] ?? false,
                'promotion_during_checkout' => $serviceData['Promotions']['DuringCheckOut'] ?? false,
                'raw_data' => $data,
                'mews_created_utc' => isset($data['CreatedUtc']) ? \Carbon\Carbon::parse($data['CreatedUtc']) : null,
                'mews_updated_utc' => isset($data['UpdatedUtc']) ? \Carbon\Carbon::parse($data['UpdatedUtc']) : null,
                'last_imported_at' => now(),
            ]
        );
    }

    private function importResourceCategory(array $data): MewsResourceCategory
    {
        return MewsResourceCategory::updateOrCreate(
            ['mews_id' => $data['Id']],
            [
                'service_id' => $data['ServiceId'],
                'external_identifier' => $data['ExternalIdentifier'] ?? null,
                'name' => $data['Name'],
                'description' => $data['Description'] ?? null,
                'is_active' => $data['IsActive'] ?? true,
                'type' => $data['Type'] ?? null,
                'normal_bed_count' => $data['NormalBedCount'] ?? null,
                'extra_bed_count' => $data['ExtraBedCount'] ?? null,
                'included_persons' => $data['IncludedPersons'] ?? null,
                'capacity' => $data['Capacity'] ?? null,
                'ordering' => $data['Ordering'] ?? null,
                'area' => $data['Area'] ?? null,
                'raw_data' => $data,
                'mews_created_utc' => isset($data['CreatedUtc']) ? \Carbon\Carbon::parse($data['CreatedUtc']) : null,
                'mews_updated_utc' => isset($data['UpdatedUtc']) ? \Carbon\Carbon::parse($data['UpdatedUtc']) : null,
                'last_imported_at' => now(),
            ]
        );
    }

    private function importResource(array $data): MewsResource
    {
        $resourceData = $data['Data']['Value'] ?? [];

        return MewsResource::updateOrCreate(
            ['mews_id' => $data['Id']],
            [
                'enterprise_id' => $data['EnterpriseId'],
                'parent_resource_id' => $data['ParentResourceId'] ?? null,
                'name' => $data['Name'],
                'is_active' => $data['IsActive'] ?? true,
                'state' => $data['State'] ?? null,
                'state_reason' => $data['StateReason'] ?? null,
                'descriptions' => $data['Descriptions'] ?? null,
                'external_names' => $data['ExternalNames'] ?? null,
                'directions' => $data['Directions'] ?? null,
                'data_discriminator' => $data['Data']['Discriminator'],
                'floor_number' => $resourceData['FloorNumber'] ?? null,
                'location_notes' => $resourceData['LocationNotes'] ?? null,
                'raw_data' => $data,
                'mews_created_utc' => isset($data['CreatedUtc']) ? \Carbon\Carbon::parse($data['CreatedUtc']) : null,
                'mews_updated_utc' => isset($data['UpdatedUtc']) ? \Carbon\Carbon::parse($data['UpdatedUtc']) : null,
                'last_imported_at' => now(),
            ]
        );
    }

    private function importResourceFeature(array $data): void
    {
        MewsResourceFeature::updateOrCreate(
            ['mews_id' => $data['Id']],
            [
                'service_id' => $data['ServiceId'],
                'external_identifier' => $data['ExternalIdentifier'] ?? null,
                'name' => $data['Name'],
                'description' => $data['Description'] ?? null,
                'is_active' => $data['IsActive'] ?? true,
                'classification' => $data['Classification'] ?? null,
                'raw_data' => $data,
                'mews_created_utc' => isset($data['CreatedUtc']) ? \Carbon\Carbon::parse($data['CreatedUtc']) : null,
                'mews_updated_utc' => isset($data['UpdatedUtc']) ? \Carbon\Carbon::parse($data['UpdatedUtc']) : null,
                'last_imported_at' => now(),
            ]
        );
    }

    private function importResourceCategoryAssignment(array $assignment): void
    {
        // Check if the resource category exists before creating the assignment
        $categoryExists = \DB::table('mews_resource_categories')
            ->where('mews_id', $assignment['CategoryId'])
            ->exists();
        
        if (!$categoryExists) {
            Log::warning("Skipping resource category assignment - category not found: " . $assignment['CategoryId']);
            return;
        }

        \DB::table('mews_resource_category_assignments')->updateOrInsert(
            [
                'resource_id' => $assignment['ResourceId'],
                'resource_category_id' => $assignment['CategoryId'],
            ],
            [
                'last_imported_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function importResourceFeatureAssignment(array $assignment): void
    {
        \DB::table('mews_resource_feature_assignments')->updateOrInsert(
            [
                'resource_id' => $assignment['ResourceId'],
                'resource_feature_id' => $assignment['ResourceFeatureId'],
            ],
            [
                'last_imported_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function makeRequest(string $endpoint, array $data): \Illuminate\Http\Client\Response
    {
        $requestData = array_merge([
            'ClientToken' => $this->clientToken,
            'AccessToken' => $this->accessToken,
            'Client' => $this->client,
        ], $data);

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . $endpoint, $requestData);
    }

    // Legacy interface methods - map to Mews equivalents
    public function mapToRoomType(MewsResourceCategory $category): ?RoomType
    {
        $enterprise = $category->service->enterprise ?? null;
        if (!$enterprise) {
            return null;
        }

        return new RoomType([
            'external_id' => $category->mews_id,
            'name' => $category->name,
            'description' => $category->description,
            'capacity' => $category->capacity,
            'bed_count' => $category->normal_bed_count,
            'extra_bed_count' => $category->extra_bed_count,
            'area' => $category->area,
            'metadata' => [
                'mews_type' => $category->type,
                'mews_service_id' => $category->service_id,
                'raw_data' => $category->raw_data,
            ],
        ]);
    }

    public function mapToRoom(MewsResource $resource): ?Room
    {
        if (!$resource->is_space) {
            return null; // Only map space resources to rooms
        }

        return new Room([
            'external_id' => $resource->mews_id,
            'name' => $resource->name,
            'floor_number' => $resource->floor_number,
            'status' => $this->mapResourceState($resource->state),
            'description' => $resource->description,
            'metadata' => [
                'mews_discriminator' => $resource->data_discriminator,
                'location_notes' => $resource->location_notes,
                'external_names' => $resource->external_names,
                'directions' => $resource->directions,
                'raw_data' => $resource->raw_data,
            ],
        ]);
    }

    private function mapResourceState(?string $mewsState): string
    {
        return match ($mewsState) {
            'Clean' => 'clean',
            'Dirty' => 'dirty',
            'Inspected' => 'inspected',
            'OutOfService' => 'out_of_service',
            'OutOfOrder' => 'out_of_order',
            default => 'available',
        };
    }

    public function getPmsSystemSlug(): string
    {
        return 'mews';
    }

    // Additional methods for command use
    public function importAllRoomTypes(): int
    {
        return count($this->importRoomTypes());
    }

    public function importAllRooms(): int
    {
        return count($this->importRooms());
    }

    public function importAllHotels(): int
    {
        return count($this->importHotels());
    }

    /**
     * Import resources in hierarchical order (parents before children)
     */
    private function importResourcesHierarchically(array $resources, array &$imported): void
    {
        $resourceMap = [];
        foreach ($resources as $resource) {
            $resourceMap[$resource['Id']] = $resource;
        }

        $processedIds = [];
        
        foreach ($resources as $resourceData) {
            $this->importResourceRecursively($resourceData, $resourceMap, $processedIds, $imported);
        }
    }

    /**
     * Recursively import a resource and its parents
     */
    private function importResourceRecursively(array $resourceData, array $resourceMap, array &$processedIds, array &$imported): void
    {
        $resourceId = $resourceData['Id'];
        
        // Skip if already processed
        if (in_array($resourceId, $processedIds)) {
            return;
        }

        // If this resource has a parent, import the parent first
        if (!empty($resourceData['ParentResourceId'])) {
            $parentId = $resourceData['ParentResourceId'];
            if (isset($resourceMap[$parentId]) && !in_array($parentId, $processedIds)) {
                $this->importResourceRecursively($resourceMap[$parentId], $resourceMap, $processedIds, $imported);
            }
        }

        // Import this resource
        try {
            $resource = $this->importResource($resourceData);
            $imported[] = $resource;
            $processedIds[] = $resourceId;
        } catch (\Exception $e) {
            Log::warning("Failed to import resource {$resourceId}: " . $e->getMessage(), ['data' => $resourceData]);
        }
    }
}