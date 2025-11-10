<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing New Apaleo Import Structure ===\n";

// Check new tables exist
echo "Checking new tables:\n";
echo "- apaleo_properties: " . (Schema::hasTable('apaleo_properties') ? '✅' : '❌') . "\n";
echo "- apaleo_unit_groups: " . (Schema::hasTable('apaleo_unit_groups') ? '✅' : '❌') . "\n";
echo "- apaleo_units: " . (Schema::hasTable('apaleo_units') ? '✅' : '❌') . "\n";
echo "- apaleo_unit_attributes: " . (Schema::hasTable('apaleo_unit_attributes') ? '✅' : '❌') . "\n";

// Clear existing data
echo "\nClearing previous data...\n";
DB::table('apaleo_properties')->delete();
DB::table('apaleo_unit_groups')->delete(); 
DB::table('apaleo_units')->delete();
DB::table('apaleo_unit_attributes')->delete();

// Test import
echo "\nTesting import...\n";
try {
    dispatch(new App\Jobs\ImportPmsDataJob('apaleo', 1));
    echo "✅ Job dispatched successfully\n";
    
    // Check jobs table
    $jobCount = DB::table('jobs')->count();
    echo "Jobs in queue: $jobCount\n";
    
} catch (Exception $e) {
    echo "❌ Error dispatching job: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";