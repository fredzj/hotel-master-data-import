<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Re-importing Mews Resources to Fix Category Assignments ===\n\n";

// Get the Mews adapter
$adapter = new \App\Services\PmsAdapters\MewsAdapter('mews');

echo "Step 1: Clearing existing category assignments...\n";
$deletedAssignments = DB::table('mews_resource_category_assignments')->delete();
echo "Deleted $deletedAssignments existing assignments\n\n";

echo "Step 2: Re-importing resources (this will also import category assignments)...\n";
try {
    $resources = $adapter->importRooms();
    echo "Successfully imported " . count($resources) . " resources\n\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Check storage/logs/laravel.log for details\n";
    exit(1);
}

echo "Step 3: Checking results...\n";
$assignmentsCount = DB::table('mews_resource_category_assignments')->count();
echo "Category assignments in database: $assignmentsCount\n";

if ($assignmentsCount > 0) {
    echo "\n✓ SUCCESS! Category assignments have been created.\n";
    echo "\nSample assignments:\n";
    $samples = DB::table('mews_resource_category_assignments')->limit(5)->get();
    foreach ($samples as $sample) {
        echo "  - Resource: {$sample->resource_id} → Category: {$sample->resource_category_id}\n";
    }
} else {
    echo "\n✗ WARNING: No category assignments were created.\n";
    echo "This means the Mews API is not providing ResourceCategoryAssignments,\n";
    echo "and the resources don't have CategoryId fields in their data.\n";
    echo "\nChecking resource structure...\n";
    
    $resource = \App\Models\MewsResource::first();
    if ($resource && $resource->raw_data) {
        $rawData = is_string($resource->raw_data) ? json_decode($resource->raw_data, true) : $resource->raw_data;
        echo "Sample resource fields: " . implode(', ', array_keys($rawData)) . "\n";
    }
}

echo "\n=== Summary ===\n";
echo "Total Mews Resources: " . \App\Models\MewsResource::count() . "\n";
echo "Resources with categories: $assignmentsCount\n";
echo "Resources without categories: " . (\App\Models\MewsResource::count() - $assignmentsCount) . "\n";
