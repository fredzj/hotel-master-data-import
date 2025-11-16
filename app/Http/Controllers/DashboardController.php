<?php

namespace App\Http\Controllers;

use App\Jobs\ImportPmsDataJob;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Hotel;
use App\Models\PmsSystem;
use App\Models\Room;
use App\Models\RoomAttribute;
use App\Models\RoomType;
use App\Models\Sunbed;
use App\Models\SunbedArea;
use App\Models\ApaleoProperty;
use App\Models\ApaleoUnitGroup;
use App\Models\ApaleoUnit;
use App\Models\ApaleoUnitAttribute;
use App\Models\MewsEnterprise;
use App\Models\MewsService;
use App\Models\MewsResourceCategory;
use App\Models\MewsResource;
use App\Models\MewsResourceFeature;
use App\Providers\PmsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $availablePmsAdapters = PmsServiceProvider::getAvailablePmsAdapters();
        
        // Get statistics based on user role
        if ($user->isSuperAdmin()) {
            $stats = $this->getAllStats();
            $hotels = Hotel::all();
        } else {
            $stats = $this->getHotelStats($user->hotel_id);
            $hotels = $user->hotel ? [$user->hotel] : [];
        }

        // Check if Apaleo tables have any data
        $apaleoHasData = ApaleoProperty::count() > 0 
            || ApaleoUnitGroup::count() > 0 
            || ApaleoUnit::count() > 0 
            || ApaleoUnitAttribute::count() > 0;

        // Check if Mews tables have any data
        $mewsHasData = MewsEnterprise::count() > 0 
            || MewsService::count() > 0 
            || MewsResourceCategory::count() > 0 
            || MewsResource::count() > 0 
            || MewsResourceFeature::count() > 0;

        return view('dashboard', compact('availablePmsAdapters', 'stats', 'hotels', 'apaleoHasData', 'mewsHasData'));
    }

    public function importPmsData(Request $request, string $pmsSlug)
    {
        $user = auth()->user();
        
        if (!$user->can('import_data')) {
            abort(403, 'Unauthorized');
        }

        $availablePmsAdapters = PmsServiceProvider::getAvailablePmsAdapters();
        
        if (!isset($availablePmsAdapters[$pmsSlug]) || !$availablePmsAdapters[$pmsSlug]['enabled']) {
            return redirect()->back()->with('error', "PMS adapter for {$pmsSlug} is not available or configured.");
        }

        // Check if there's already an import running
        $cacheKey = "pms_import_{$pmsSlug}_{$user->id}";
        $importStatus = Cache::get($cacheKey);
        
        if ($importStatus && $importStatus['status'] === 'running') {
            return redirect()->back()->with('info', 'An import is already running for this PMS system.');
        }

        // Dispatch the import job
        ImportPmsDataJob::dispatch($pmsSlug, $user->id);
        
        // Automatically trigger queue processing (for shared hosting)
        try {
            // Use schedule:run instead which triggers the queue worker via console scheduler
            Artisan::call('schedule:run');
        } catch (\Exception $e) {
            \Log::warning('Queue processing failed after job dispatch: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', "Import from {$availablePmsAdapters[$pmsSlug]['name']} has been started.");
    }

    public function importStatus(Request $request, string $pmsSlug)
    {
        $user = auth()->user();
        $cacheKey = "pms_import_{$pmsSlug}_{$user->id}";
        $importStatus = Cache::get($cacheKey, ['status' => 'idle', 'progress' => 0]);

        return response()->json($importStatus);
    }

    public function transformPmsData(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->can('import_data')) {
            abort(403, 'Unauthorized');
        }

        try {
            \DB::beginTransaction();
            
            $transformedCount = [
                'hotels' => 0,
                'room_types' => 0,
                'rooms' => 0,
            ];

            // Get or create PMS systems
            $apaleoPms = PmsSystem::firstOrCreate(
                ['slug' => 'apaleo'],
                ['name' => 'Apaleo']
            );
            
            $mewsPms = PmsSystem::firstOrCreate(
                ['slug' => 'mews'],
                ['name' => 'Mews']
            );

            // 1. Transform Apaleo Properties to Hotels
            $apaleoProperties = ApaleoProperty::with(['unitGroups.units'])->get();
            foreach ($apaleoProperties as $property) {
                $hotel = Hotel::updateOrCreate(
                    [
                        'pms_system_id' => $apaleoPms->id,
                        'external_id' => $property->apaleo_id,
                    ],
                    [
                        'code' => $property->code,
                        'name' => $property->name,
                        'description' => $property->description,
                        'company_name' => $property->company_name,
                        'commercial_register_entry' => $property->commercial_register_entry,
                        'tax_id' => $property->tax_id,
                        'address' => $property->address_line1,
                        'city' => $property->city,
                        'country' => $property->country_code,
                        'postal_code' => $property->postal_code,
                        'phone' => null,
                        'email' => null,
                        'website' => null,
                        'timezone' => $property->time_zone,
                        'currency' => $property->currency_code,
                        'bank_iban' => $property->bank_account_iban,
                        'bank_bic' => $property->bank_account_bic,
                        'bank_name' => $property->bank_account_holder,
                        'status' => $property->status,
                        'external_created_at' => $property->created,
                    ]
                );
                $transformedCount['hotels']++;

                // 2. Transform Apaleo Unit Groups to Room Types
                foreach ($property->unitGroups as $unitGroup) {
                    $roomType = RoomType::updateOrCreate(
                        [
                            'hotel_id' => $hotel->id,
                            'external_id' => $unitGroup->apaleo_id,
                        ],
                        [
                            'code' => $unitGroup->code,
                            'name' => $unitGroup->name,
                            'description' => $unitGroup->description,
                            'max_occupancy' => $unitGroup->max_persons,
                            'member_count' => $unitGroup->member_count,
                            'type' => $unitGroup->type,
                        ]
                    );
                    $transformedCount['room_types']++;

                    // 3. Transform Apaleo Units to Rooms
                    foreach ($unitGroup->units as $unit) {
                        Room::updateOrCreate(
                            [
                                'room_type_id' => $roomType->id,
                                'external_id' => $unit->apaleo_id,
                            ],
                            [
                                'name' => $unit->name,
                                'number' => $unit->name, // Use name as number for Apaleo
                                'description' => $unit->description,
                                'status' => $this->mapRoomStatus($unit->status, 'apaleo'),
                            ]
                        );
                        $transformedCount['rooms']++;
                    }
                }
            }

            // 1. Transform Mews Enterprises to Hotels
            $mewsEnterprises = MewsEnterprise::with(['services.resourceCategories'])->get();
            
            // Pre-load all resource-category assignments to avoid N+1 queries
            $resourceCategoryMap = \DB::table('mews_resource_category_assignments')
                ->select('resource_id', 'resource_category_id')
                ->get()
                ->groupBy('resource_category_id');
            
            foreach ($mewsEnterprises as $enterprise) {
                $hotel = Hotel::updateOrCreate(
                    [
                        'pms_system_id' => $mewsPms->id,
                        'external_id' => $enterprise->mews_id,
                    ],
                    [
                        'name' => $enterprise->name,
                        'address' => $enterprise->address_line1,
                        'city' => $enterprise->city,
                        'country' => $enterprise->country_code,
                        'postal_code' => $enterprise->postal_code,
                        'phone' => $enterprise->telephone,
                        'email' => $enterprise->email,
                        'website' => $enterprise->website_url,
                        'timezone' => $enterprise->timezone,
                        'tax_id' => $enterprise->tax_identifier,
                        'external_created_at' => $enterprise->mews_created_utc,
                    ]
                );
                $transformedCount['hotels']++;

                // 2. Transform Mews Resource Categories to Room Types
                foreach ($enterprise->services as $service) {
                    foreach ($service->resourceCategories as $category) {
                        $roomType = RoomType::updateOrCreate(
                            [
                                'hotel_id' => $hotel->id,
                                'external_id' => $category->mews_id,
                            ],
                            [
                                'code' => $category->external_identifier,
                                'name' => $category->name,
                                'description' => $category->description,
                                'max_occupancy' => $category->capacity,
                                'type' => $category->type,
                            ]
                        );
                        $transformedCount['room_types']++;

                        // 3. Transform Mews Resources to Rooms
                        // Get resource IDs for this category from the pre-loaded map
                        $resourceIds = $resourceCategoryMap->get($category->mews_id, collect())
                            ->pluck('resource_id')
                            ->toArray();
                        
                        if (!empty($resourceIds)) {
                            $resources = MewsResource::whereIn('mews_id', $resourceIds)->get();
                            
                            foreach ($resources as $resource) {
                                Room::updateOrCreate(
                                    [
                                        'room_type_id' => $roomType->id,
                                        'external_id' => $resource->mews_id,
                                    ],
                                    [
                                        'name' => $resource->name,
                                        'number' => $resource->name,
                                        'description' => null,
                                        'status' => $this->mapRoomStatus($resource->state, 'mews'),
                                    ]
                                );
                                $transformedCount['rooms']++;
                            }
                        }
                    }
                }
            }

            \DB::commit();

            $message = sprintf(
                'Successfully transformed PMS data: %d hotels, %d room types, %d rooms',
                $transformedCount['hotels'],
                $transformedCount['room_types'],
                $transformedCount['rooms']
            );

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('PMS Data Transformation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'PMS Data Transformation failed: ' . $e->getMessage());
        }
    }

    private function getAllStats(): array
    {
        return [
            // Combined statistics from all PMS systems
            'properties' => ApaleoProperty::count() + MewsEnterprise::count(),
            'unit_types' => ApaleoUnitGroup::count() + MewsResourceCategory::count(),
            'units' => ApaleoUnit::count() + MewsResource::count(),
            'unit_attributes' => ApaleoUnitAttribute::count() + MewsResourceFeature::count(),
            
            // Individual PMS statistics for detailed view
            'apaleo' => [
                'properties' => ApaleoProperty::count(),
                'unit_types' => ApaleoUnitGroup::count(),
                'units' => ApaleoUnit::count(),
                'unit_attributes' => ApaleoUnitAttribute::count(),
            ],
            'mews' => [
                'enterprises' => MewsEnterprise::count(),
                'services' => MewsService::count(),
                'resource_categories' => MewsResourceCategory::count(),
                'resources' => MewsResource::count(),
                'resource_features' => MewsResourceFeature::count(),
            ],
        ];
    }

    private function getHotelStats(?int $hotelId): array
    {
        // For now, return the same stats as getAllStats since we're focusing on Apaleo data
        // In the future, this could filter by specific property if needed
        return $this->getAllStats();
    }

    /**
     * Map PMS-specific status values to the allowed room status enum values
     * Allowed values: 'available', 'occupied', 'maintenance', 'out_of_order'
     */
    private function mapRoomStatus(?string $pmsStatus, string $pmsType = 'apaleo'): string
    {
        if (!$pmsStatus) {
            return 'available';
        }

        $statusLower = strtolower($pmsStatus);

        // Apaleo status mapping
        if ($pmsType === 'apaleo') {
            return match ($statusLower) {
                'vacant' => 'available',
                'occupied' => 'occupied',
                default => 'available',
            };
        }

        // Mews status mapping (state field)
        if ($pmsType === 'mews') {
            return match ($statusLower) {
                'clean', 'inspected' => 'available',
                'dirty' => 'maintenance',
                'outoforder', 'outofservice' => 'out_of_order',
                default => 'available',
            };
        }

        return 'available';
    }
}
