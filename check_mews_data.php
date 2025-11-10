<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\MewsEnterprise;
use App\Models\MewsService;
use App\Models\MewsResourceCategory;
use App\Models\MewsResource;

echo "=== Mews Enterprise Data ===" . PHP_EOL;

$enterprises = MewsEnterprise::all();
foreach ($enterprises as $enterprise) {
    echo "ID: {$enterprise->mews_id}" . PHP_EOL;
    echo "Name: {$enterprise->name}" . PHP_EOL;
    echo "Timezone: {$enterprise->timezone}" . PHP_EOL;
    echo "Address: {$enterprise->address}" . PHP_EOL;
    echo "Email: {$enterprise->email}" . PHP_EOL;
    echo "Created: {$enterprise->created_at}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo "Total Enterprises: " . $enterprises->count() . PHP_EOL;
echo "Total Services: " . MewsService::count() . PHP_EOL;
echo "Total Resource Categories: " . MewsResourceCategory::count() . PHP_EOL;
echo "Total Resources: " . MewsResource::count() . PHP_EOL;