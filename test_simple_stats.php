<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Properties: " . App\Models\ApaleoProperty::count() . "\n";
echo "Unit Groups: " . App\Models\ApaleoUnitGroup::count() . "\n";
echo "Units: " . App\Models\ApaleoUnit::count() . "\n";
echo "Unit Attributes: " . App\Models\ApaleoUnitAttribute::count() . "\n";