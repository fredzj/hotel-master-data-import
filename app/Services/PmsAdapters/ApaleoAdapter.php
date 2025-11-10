<?php

namespace App\Services\PmsAdapters;

use App\Contracts\PmsAdapterInterface;
use App\Models\Hotel;
use App\Models\PmsSystem;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\RoomAttribute;
use App\Models\ApaleoProperty;
use App\Models\ApaleoUnit;
use App\Models\ApaleoUnitAttribute;
use App\Models\ApaleoUnitGroup;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApaleoAdapter implements PmsAdapterInterface
{
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl;
    private string $identityUrl;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->clientId = config('services.apaleo.client_id');
        $this->clientSecret = config('services.apaleo.client_secret');
        $this->baseUrl = config('services.apaleo.base_url');
        $this->identityUrl = config('services.apaleo.identity_url');
    }

    public function authenticate(): bool
    {
        try {
            // Check if we have a cached valid token
            $cachedToken = Cache::get('apaleo_access_token');
            if ($cachedToken) {
                $this->accessToken = $cachedToken;
                return true;
            }

            // Request new token using Client Credentials Grant Flow
            $response = Http::asForm()->post($this->identityUrl . '/connect/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'setup.read',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];

                // Cache the token for 55 minutes (tokens expire in 60 minutes)
                Cache::put('apaleo_access_token', $this->accessToken, 3300);
                return true;
            }

            Log::error('Apaleo authentication failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Apaleo authentication error ' . $e->getMessage());
            return false;
        }
    } 

    public function importHotels(): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Apaleo');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/inventory/v1/properties');

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch properties from Apaleo: ' . $response->body());
            }

            $properties = $response->json()['properties'] ?? [];
            $importedProperties = [];

            foreach ($properties as $property) {

                $apaleoProperty = ApaleoProperty::updateOrCreate(
                    [
                        'apaleo_id' => $property['id'],
                    ],
                    [
                        'name' => $property['name'],
                        'code' => $property['code'] ?? null,
                        'description' => $property['description'] ?? null,
                        'status' => $property['status'] ?? null,
                        'country_code' => $property['location']['countryCode'] ?? null,
                        'city' => $property['location']['city'] ?? null,
                        'postal_code' => $property['location']['postalCode'] ?? null,
                        'address_line1' => $property['location']['addressLine1'] ?? null,
                        'address_line2' => $property['location']['addressLine2'] ?? null,
                        'state' => $property['location']['state'] ?? null,                                
                        'company_name' => $property['companyName'] ?? null,
                        'tax_id' => $property['taxId'] ?? null,
                        'commercial_register_entry' => $property['commercialRegisterEntry'] ?? null,
                        'iban' => $property['bankAccount']['iban'] ?? null,
                        'bic' => $property['bankAccount']['bic'] ?? null,
                        'bank_name' => $property['bankAccount']['bankName'] ?? $property['bankAccount']['bank'] ?? $property['bank'] ?? null,                                
                        'timezone' => $property['timeZone'] ?? null,
                        'currency_code' => $property['currencyCode'] ?? null,
                        'raw_data' => $property,
                    ]
                );

                $importedProperties[] = $apaleoProperty;
            }

            return $importedProperties;

        } catch (\Exception $e) {
            Log::error('Apaleo hotels import error', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function importRoomTypes(string $propertyId): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Apaleo');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . "/inventory/v1/unit-groups?propertyId={$propertyId}");

            if (!$response->successful()) {
                $error = 'Status: ' . $response->status() . ', Body: ' . $response->body();
                throw new \Exception('Failed to fetch unit groups from Apaleo: ' . $error);
            }

            $unitGroups = $response->json()['unitGroups'] ?? [];
            $importedUnitGroups = [];
            foreach ($unitGroups as $unitGroup) {
                $apaleoUnitGroup = ApaleoUnitGroup::updateOrCreate(
                    [
                        'apaleo_id' => $unitGroup['id'],
                    ],
                    [
                        'property_id' => $propertyId,
                        'code' => $unitGroup['code'] ?? null,
                        'name' => $unitGroup['name'],
                        'description' => $unitGroup['description'] ?? null,
                        'type' => $unitGroup['type'] ?? null,
                        'max_persons' => $unitGroup['maxPersons'] ?? null,
                        'member_count' => $unitGroup['memberCount'] ?? null,
                        'raw_data' => $unitGroup,
                    ]
                );
                $importedUnitGroups[] = $apaleoUnitGroup;
            }
            return $importedUnitGroups;
        } catch (\Exception $e) {
            Log::error('Apaleo unit groups import error', [
                'error' => $e->getMessage(),
                'property_id' => $propertyId
            ]);
            throw $e;
        }
    }

    public function importRooms(string $propertyId): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Failed to authenticate with Apaleo');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . "/inventory/v1/units", [
                'propertyId' => $propertyId
            ]);

            if (!$response->successful()) {
                $error = 'Status: ' . $response->status() . ', Body: ' . $response->body();
                throw new \Exception('Failed to fetch units from Apaleo: ' . $error);
            }

            $responseData = $response->json();
            $units = $responseData['units'] ?? $responseData ?? [];
            $importedUnits = [];
            foreach ($units as $unit) {
                // Get unit group ID
                $unitGroupData = $unit['unitGroup'] ?? null;
                $unitGroupId = is_array($unitGroupData) ? ($unitGroupData['id'] ?? null) : $unitGroupData;
                $apaleoUnit = ApaleoUnit::updateOrCreate(
                    [
                        'apaleo_id' => $unit['id'],
                    ],
                    [
                        'property_id' => $propertyId,
                        'unit_group_id' => $unitGroupId,
                        'name' => $unit['name'],
                        'description' => $unit['description'] ?? null,
                        'status' => isset($unit['status']['isOccupied']) ? ($unit['status']['isOccupied'] ? 'Occupied' : 'Vacant') : null,
                        'condition' => $unit['status']['condition'] ?? null,
                        'max_persons' => $unit['maxPersons'] ?? null,
                        'size' => $unit['size'] ?? null,
                        'view' => $unit['view'] ?? null,
                        'raw_data' => $unit,
                    ]
                );
                $importedUnits[] = $apaleoUnit;
            }
            return $importedUnits;

        } catch (\Exception $e) {
            Log::error('Apaleo units import error', [
                'error' => $e->getMessage(),
                'property_id' => $propertyId
            ]);
            throw $e;
        }
    }

    public function getPmsSystemSlug(): string
    {
        return 'apaleo';
    }

    public function testConnection(): bool
    {
        try {
            return $this->authenticate();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function importRoomAttributes(string $propertyId): array
    {
        if (!$this->authenticate()) {
            throw new \Exception('Authentication failed');
        }

        try {
            // Get all units for this property
            $units = ApaleoUnit::where('property_id', $propertyId)->get();
            $importedAttributes = [];

            foreach ($units as $unit) {
                // Get unit attributes from Apaleo API
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json'
                ])->get($this->baseUrl . "/inventory/v1/unit-attributes", [
                    'unitId' => $unit->apaleo_id
                ]);

                if (!$response->successful()) {
                    Log::warning('Failed to fetch unit attributes from Apaleo', [
                        'unit_id' => $unit->apaleo_id,
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    continue;
                }

                $responseData = $response->json();
                $attributes = $responseData['attributes'] ?? $responseData ?? [];

                foreach ($attributes as $attributeData) {
                    $attribute = ApaleoUnitAttribute::updateOrCreate(
                        [
                            'unit_id' => $unit->apaleo_id,
                            'name' => $attributeData['name'] ?? 'Unknown'
                        ],
                        [
                            'value' => $attributeData['value'] ?? null,
                            'type' => $attributeData['type'] ?? 'text',
                            'unit_of_measure' => $attributeData['unitOfMeasure'] ?? null,
                            'raw_data' => $attributeData
                        ]
                    );

                    $importedAttributes[] = $attribute;
                }
            }

            return $importedAttributes;
        } catch (\Exception $e) {
            Log::error('Apaleo unit attributes import error', [
                'error' => $e->getMessage(),
                'property_id' => $propertyId
            ]);
            throw $e;
        }
    }
    
    private function mapApaleoUnitStatus(string $apaleoStatus): string
    {
        return match (strtolower($apaleoStatus)) {
            'active' => 'available',
            'inactive' => 'out_of_order',
            'maintenance' => 'maintenance',
            default => 'available',
        };
    }
}