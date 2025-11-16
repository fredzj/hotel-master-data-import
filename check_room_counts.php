<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Source Data ===\n";
echo "Apaleo Units: " . \App\Models\ApaleoUnit::count() . "\n";
echo "Mews Resources: " . \App\Models\MewsResource::count() . "\n";
echo "\n";

echo "=== Transformed Data ===\n";
echo "Transformed Rooms: " . \App\Models\Room::count() . "\n";
echo "\n";

echo "=== Mews Resource Analysis ===\n";
$totalMewsResources = \App\Models\MewsResource::count();
$mewsResourcesWithCategories = DB::table('mews_resource_category_assignments')
    ->distinct('resource_id')
    ->count('resource_id');
$mewsResourcesWithoutCategories = $totalMewsResources - $mewsResourcesWithCategories;

echo "Mews Resources with categories: " . $mewsResourcesWithCategories . "\n";
echo "Mews Resources WITHOUT categories: " . $mewsResourcesWithoutCategories . "\n";
echo "\n";

echo "=== Room Breakdown by PMS ===\n";
$apaleoPmsId = \App\Models\PmsSystem::where('slug', 'apaleo')->value('id');
$mewsPmsId = \App\Models\PmsSystem::where('slug', 'mews')->value('id');

$apaleoRooms = \App\Models\Room::whereHas('roomType.hotel', function($q) use ($apaleoPmsId) {
    $q->where('pms_system_id', $apaleoPmsId);
})->count();

$mewsRooms = \App\Models\Room::whereHas('roomType.hotel', function($q) use ($mewsPmsId) {
    $q->where('pms_system_id', $mewsPmsId);
})->count();

echo "Rooms from Apaleo: " . $apaleoRooms . "\n";
echo "Rooms from Mews: " . $mewsRooms . "\n";
echo "Total: " . ($apaleoRooms + $mewsRooms) . "\n";
echo "\n";

echo "=== Expected vs Actual ===\n";
echo "Expected from sources: " . (363 + $mewsResourcesWithCategories) . " (363 Apaleo + " . $mewsResourcesWithCategories . " Mews with categories)\n";
echo "Actual in rooms table: " . \App\Models\Room::count() . "\n";
echo "Missing: " . ((363 + $mewsResourcesWithCategories) - \App\Models\Room::count()) . "\n";
