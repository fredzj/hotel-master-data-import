<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\MewsEnterprise;

echo "=== Mews Enterprises in Database ===" . PHP_EOL;

$enterprises = MewsEnterprise::all();

foreach ($enterprises as $enterprise) {
    echo "Database ID: {$enterprise->id}" . PHP_EOL;
    echo "Mews ID: {$enterprise->mews_id}" . PHP_EOL;
    echo "Name: {$enterprise->name}" . PHP_EOL;
    echo "URL: http://127.0.0.1:8000/mews-enterprises/{$enterprise->id}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

if ($enterprises->count() === 0) {
    echo "No enterprises found in database." . PHP_EOL;
}