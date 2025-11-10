<?php

namespace App\Http\Controllers;

use App\Jobs\ImportPmsDataJob;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Hotel;
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

        return view('dashboard', compact('availablePmsAdapters', 'stats', 'hotels'));
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
}
