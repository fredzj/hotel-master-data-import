<?php

// Script to analyze raw_data vs structured fields for Apaleo properties
require_once 'vendor/autoload.php';

use App\Models\ApaleoProperty;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Analyzing raw_data vs structured fields for Apaleo properties...\n\n";

$property = ApaleoProperty::first();

if ($property && $property->raw_data) {
    echo "Sample raw data structure for property: {$property->name}\n";
    echo "Raw data keys: " . implode(', ', array_keys($property->raw_data)) . "\n\n";
    
    // Check bank account structure
    if (isset($property->raw_data['bankAccount'])) {
        echo "Bank Account structure:\n";
        echo json_encode($property->raw_data['bankAccount'], JSON_PRETTY_PRINT) . "\n\n";
    }
    
    // Check if there are any fields in raw_data that we might be missing
    $rawData = $property->raw_data;
    
    echo "Potential missing fields analysis:\n";
    echo "- company_name: DB='" . ($property->company_name ?? 'NULL') . "' vs Raw='" . ($rawData['companyName'] ?? 'NULL') . "'\n";
    echo "- bank_name: DB='" . ($property->bank_name ?? 'NULL') . "' vs Raw='" . ($rawData['bankAccount']['bankName'] ?? $rawData['bank'] ?? 'NULL') . "'\n";
    echo "- currency_code: DB='" . ($property->currency_code ?? 'NULL') . "' vs Raw='" . ($rawData['currencyCode'] ?? 'NULL') . "'\n";
    echo "- timezone: DB='" . ($property->timezone ?? 'NULL') . "' vs Raw='" . ($rawData['timeZone'] ?? 'NULL') . "'\n";
    
} else {
    echo "No properties found or no raw_data available.\n";
}