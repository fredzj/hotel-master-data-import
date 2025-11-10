<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Import Results ===\n";
echo "Hotels: " . App\Models\Hotel::count() . "\n";
echo "Room Types: " . App\Models\RoomType::count() . "\n";
echo "Rooms: " . App\Models\Room::count() . "\n";
echo "Room Attributes: " . App\Models\RoomAttribute::count() . "\n";
echo "Jobs in queue: " . DB::table('jobs')->count() . "\n";

// Check latest hotel update
$latestHotel = App\Models\Hotel::latest('updated_at')->first();
if ($latestHotel) {
    echo "Latest hotel updated: " . $latestHotel->updated_at . "\n";
}