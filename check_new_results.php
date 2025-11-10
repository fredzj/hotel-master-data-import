<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== New Apaleo Import Results ===\n";
echo "Apaleo Properties: " . App\Models\ApaleoProperty::count() . "\n";
echo "Apaleo Unit Groups: " . App\Models\ApaleoUnitGroup::count() . "\n";
echo "Apaleo Units: " . App\Models\ApaleoUnit::count() . "\n";
echo "Apaleo Unit Attributes: " . App\Models\ApaleoUnitAttribute::count() . "\n";

echo "\nSample property data:\n";
$property = App\Models\ApaleoProperty::first();
if ($property) {
    echo "- ID: {$property->apaleo_id}\n";
    echo "- Name: {$property->name}\n";
    echo "- City: {$property->city}\n";
    echo "- Unit Groups: " . $property->unitGroups()->count() . "\n";
    echo "- Units: " . $property->units()->count() . "\n";
}

echo "\nJobs remaining: " . DB::table('jobs')->count() . "\n";
echo "\n=== Import Complete ===\n";