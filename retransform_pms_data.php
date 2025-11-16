<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Re-transforming PMS Data ===\n\n";

echo "Before transformation:\n";
echo "  Hotels: " . \App\Models\Hotel::count() . "\n";
echo "  Room Types: " . \App\Models\RoomType::count() . "\n";
echo "  Rooms: " . \App\Models\Room::count() . "\n\n";

echo "Starting transformation...\n";

// Call the transformation via HTTP request or directly through the controller
$controller = new \App\Http\Controllers\DashboardController();
$request = new \Illuminate\Http\Request();

// Set up authenticated user
$user = \App\Models\User::first();
if (!$user) {
    echo "ERROR: No user found in database\n";
    exit(1);
}

auth()->login($user);

try {
    $response = $controller->transformPmsData($request);
    
    echo "\nTransformation completed!\n\n";
    
    echo "After transformation:\n";
    echo "  Hotels: " . \App\Models\Hotel::count() . "\n";
    echo "  Room Types: " . \App\Models\RoomType::count() . "\n";
    echo "  Rooms: " . \App\Models\Room::count() . "\n\n";
    
    // Breakdown by PMS
    $apaleoPmsId = \App\Models\PmsSystem::where('slug', 'apaleo')->value('id');
    $mewsPmsId = \App\Models\PmsSystem::where('slug', 'mews')->value('id');
    
    $apaleoRooms = \App\Models\Room::whereHas('roomType.hotel', function($q) use ($apaleoPmsId) {
        $q->where('pms_system_id', $apaleoPmsId);
    })->count();
    
    $mewsRooms = \App\Models\Room::whereHas('roomType.hotel', function($q) use ($mewsPmsId) {
        $q->where('pms_system_id', $mewsPmsId);
    })->count();
    
    echo "Breakdown by PMS:\n";
    echo "  Rooms from Apaleo: $apaleoRooms (from 363 units, 4 orphaned)\n";
    echo "  Rooms from Mews: $mewsRooms (from 1361 resources)\n";
    echo "  Total: " . ($apaleoRooms + $mewsRooms) . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
