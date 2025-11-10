<?php

// Script to update status and condition from raw_data for existing Apaleo units
require_once 'vendor/autoload.php';

use App\Models\ApaleoUnit;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Updating status and condition from raw_data for Apaleo units...\n";

$units = ApaleoUnit::whereNotNull('raw_data')->get();

$updated = 0;

foreach ($units as $unit) {
    $rawData = $unit->raw_data;
    $needsUpdate = false;
    $newData = [];
    
    // Extract status from raw data
    if (isset($rawData['status']['isOccupied'])) {
        $newStatus = $rawData['status']['isOccupied'] ? 'Occupied' : 'Vacant';
        if ($newStatus !== $unit->status) {
            $newData['status'] = $newStatus;
            $needsUpdate = true;
        }
    }
    
    // Extract condition from raw data
    if (isset($rawData['status']['condition'])) {
        $newCondition = $rawData['status']['condition'];
        if ($newCondition !== $unit->condition) {
            $newData['condition'] = $newCondition;
            $needsUpdate = true;
        }
    }
    
    if ($needsUpdate) {
        echo "Updating unit ID {$unit->id} ({$unit->name}):";
        if (isset($newData['status'])) {
            echo " status = '{$newData['status']}'";
        }
        if (isset($newData['condition'])) {
            echo " condition = '{$newData['condition']}'";
        }
        echo "\n";
        
        $unit->update($newData);
        $updated++;
    }
}

echo "Updated {$updated} units with status/condition from raw_data.\n";