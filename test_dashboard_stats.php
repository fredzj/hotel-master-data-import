<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Dashboard Statistics Test ===\n";

// Test the DashboardController stats method
$controller = new App\Http\Controllers\DashboardController();

// Use reflection to access private methods for testing
$reflection = new ReflectionClass($controller);
$getAllStatsMethod = $reflection->getMethod('getAllStats');
$getAllStatsMethod->setAccessible(true);

$stats = $getAllStatsMethod->invoke($controller);

echo "Statistics returned by DashboardController:\n";
foreach ($stats as $key => $value) {
    echo "- {$key}: {$value}\n";
}

echo "\nDirect model counts:\n";
echo "- ApaleoProperty: " . App\Models\ApaleoProperty::count() . "\n";
echo "- ApaleoUnitGroup: " . App\Models\ApaleoUnitGroup::count() . "\n";
echo "- ApaleoUnit: " . App\Models\ApaleoUnit::count() . "\n";
echo "- ApaleoUnitAttribute: " . App\Models\ApaleoUnitAttribute::count() . "\n";

echo "\n=== Test Complete ===\n";