<?php

// Quick script to update bank_name from raw_data for existing Apaleo properties
require_once 'vendor/autoload.php';

use App\Models\ApaleoProperty;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Updating bank_name from raw_data for Apaleo properties...\n";

$properties = ApaleoProperty::whereNotNull('raw_data')->get();

$updated = 0;

foreach ($properties as $property) {
    $rawData = $property->raw_data;
    $bankName = null;
    
    // Try different possible locations for bank name in raw data
    if (isset($rawData['bankAccount']['bankName'])) {
        $bankName = $rawData['bankAccount']['bankName'];
    } elseif (isset($rawData['bankAccount']['bank'])) {
        $bankName = $rawData['bankAccount']['bank'];
    } elseif (isset($rawData['bank'])) {
        $bankName = $rawData['bank'];
    }
    
    if ($bankName && $bankName !== $property->bank_name) {
        echo "Updating property ID {$property->id} ({$property->name}): bank_name = '{$bankName}'\n";
        $property->bank_name = $bankName;
        $property->save();
        $updated++;
    }
}

echo "Updated {$updated} properties with bank_name from raw_data.\n";