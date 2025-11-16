<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Mews Resource Raw API Data ===\n\n";

// Get a sample resource to check its structure
$resource = \App\Models\MewsResource::first();

if (!$resource) {
    echo "ERROR: No Mews resources found!\n";
    exit(1);
}

echo "Sample Resource ID: " . $resource->mews_id . "\n";
echo "Resource Name: " . $resource->name . "\n\n";

echo "Raw API Data:\n";
$rawData = json_decode($resource->raw_api_data, true);
print_r($rawData);

echo "\n\n=== Checking for Category Information ===\n";
if (isset($rawData['CategoryId'])) {
    echo "Found CategoryId in resource: " . $rawData['CategoryId'] . "\n";
} elseif (isset($rawData['ResourceCategoryId'])) {
    echo "Found ResourceCategoryId in resource: " . $rawData['ResourceCategoryId'] . "\n";
} else {
    echo "No CategoryId or ResourceCategoryId field found in resource raw data.\n";
    echo "Available fields: " . implode(', ', array_keys($rawData)) . "\n";
}

echo "\n=== Checking Multiple Resources ===\n";
$resources = \App\Models\MewsResource::limit(10)->get();
$withCategory = 0;
$withoutCategory = 0;

foreach ($resources as $res) {
    $raw = json_decode($res->raw_api_data, true);
    if (isset($raw['CategoryId']) || isset($raw['ResourceCategoryId'])) {
        $withCategory++;
    } else {
        $withoutCategory++;
    }
}

echo "Resources with category in raw data: $withCategory\n";
echo "Resources without category in raw data: $withoutCategory\n";
