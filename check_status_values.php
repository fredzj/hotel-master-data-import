<?php

// Script to check actual status values from Apaleo properties
require_once 'vendor/autoload.php';

use App\Models\ApaleoProperty;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Analyzing status values in Apaleo properties...\n\n";

$properties = ApaleoProperty::all();

$statusValues = [];
foreach ($properties as $property) {
    echo "Property: {$property->name}\n";
    echo "  DB Status: " . ($property->status ?? 'NULL') . "\n";
    
    if ($property->raw_data && isset($property->raw_data['status'])) {
        echo "  Raw Status: " . $property->raw_data['status'] . "\n";
        $statusValues[] = $property->raw_data['status'];
    } else {
        echo "  Raw Status: Not found in raw_data\n";
    }
    echo "\n";
}

echo "Unique status values found in raw data: " . implode(', ', array_unique($statusValues)) . "\n";