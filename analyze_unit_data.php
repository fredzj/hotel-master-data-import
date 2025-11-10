<?php

// Script to analyze raw_data vs structured fields for Apaleo units
require_once 'vendor/autoload.php';

use App\Models\ApaleoUnit;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Analyzing raw_data vs structured fields for Apaleo units...\n\n";

$unit = ApaleoUnit::where('id', 4)->first();

if ($unit && $unit->raw_data) {
    echo "Unit ID 4: {$unit->name}\n";
    echo "Raw data keys: " . implode(', ', array_keys($unit->raw_data)) . "\n\n";
    
    // Check specific fields
    $rawData = $unit->raw_data;
    
    echo "Field analysis:\n";
    echo "- status: DB='" . ($unit->status ?? 'NULL') . "' vs Raw='" . json_encode($rawData['status'] ?? 'NULL') . "'\n";
    echo "- condition: DB='" . ($unit->condition ?? 'NULL') . "' vs Raw='" . json_encode($rawData['condition'] ?? 'NULL') . "'\n";
    echo "- maxPersons: DB='" . ($unit->max_persons ?? 'NULL') . "' vs Raw='" . ($rawData['maxPersons'] ?? 'NULL') . "'\n";
    echo "- size: DB='" . ($unit->size ?? 'NULL') . "' vs Raw='" . ($rawData['size'] ?? 'NULL') . "'\n";
    echo "- view: DB='" . ($unit->view ?? 'NULL') . "' vs Raw='" . ($rawData['view'] ?? 'NULL') . "'\n\n";
    
    echo "Full raw data structure:\n";
    echo json_encode($rawData, JSON_PRETTY_PRINT) . "\n";
    
} else {
    echo "Unit with ID 4 not found or no raw_data available.\n";
}