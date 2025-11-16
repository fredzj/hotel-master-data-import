<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Issue 1: Missing Mews Resource Category Assignments ===\n";
echo "Total Mews Resources: " . \App\Models\MewsResource::count() . "\n";
echo "Total assignments in mews_resource_category_assignments: " . DB::table('mews_resource_category_assignments')->count() . "\n";
echo "\n";

// Check if the table exists and has data
$tableExists = DB::select("SHOW TABLES LIKE 'mews_resource_category_assignments'");
if (empty($tableExists)) {
    echo "ERROR: Table 'mews_resource_category_assignments' does not exist!\n";
} else {
    echo "Table exists. Checking sample data...\n";
    $sampleAssignments = DB::table('mews_resource_category_assignments')->limit(5)->get();
    echo "Sample assignments: " . count($sampleAssignments) . "\n";
    foreach ($sampleAssignments as $assignment) {
        print_r($assignment);
    }
}
echo "\n";

echo "=== Issue 2: Missing 4 Apaleo Units ===\n";
echo "Total Apaleo Units: " . \App\Models\ApaleoUnit::count() . "\n";

// Check if any units don't have unit groups
$unitsWithoutGroup = \App\Models\ApaleoUnit::whereNull('unit_group_id')->count();
echo "Apaleo Units without unit_group_id: " . $unitsWithoutGroup . "\n";

if ($unitsWithoutGroup > 0) {
    echo "\nUnits without unit_group_id:\n";
    $orphanUnits = \App\Models\ApaleoUnit::whereNull('unit_group_id')->get(['id', 'apaleo_id', 'name', 'unit_group_id']);
    foreach ($orphanUnits as $unit) {
        echo "  - ID: {$unit->id}, Apaleo ID: {$unit->apaleo_id}, Name: {$unit->name}\n";
    }
}

// Check if any unit groups don't have properties
$unitGroupsWithoutProperty = \App\Models\ApaleoUnitGroup::whereNull('property_id')->count();
echo "\nApaleo Unit Groups without property_id: " . $unitGroupsWithoutProperty . "\n";

if ($unitGroupsWithoutProperty > 0) {
    $orphanGroups = \App\Models\ApaleoUnitGroup::whereNull('property_id')->with('units')->get();
    foreach ($orphanGroups as $group) {
        echo "  - Group ID: {$group->id}, Apaleo ID: {$group->apaleo_id}, Name: {$group->name}, Units: {$group->units->count()}\n";
    }
}

echo "\n=== Verification ===\n";
$unitsInValidGroups = \App\Models\ApaleoUnit::whereNotNull('unit_group_id')
    ->whereHas('unitGroup', function($q) {
        $q->whereNotNull('property_id');
    })->count();
echo "Apaleo Units with valid group and property chain: " . $unitsInValidGroups . "\n";
echo "This should match the transformed rooms from Apaleo: 359\n";
