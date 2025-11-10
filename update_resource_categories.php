<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MewsResource;
use Illuminate\Support\Facades\DB;

echo "Updating resources with category_id...\n";

// First check how many assignments we have
$assignmentCount = DB::table('mews_resource_category_assignments')->count();
echo "Total category assignments: $assignmentCount\n";

$resources = MewsResource::whereNull('category_id')->get();
$updated = 0;

foreach ($resources as $resource) {
    $assignment = DB::table('mews_resource_category_assignments')
        ->where('resource_id', $resource->mews_id)
        ->first();
    
    if ($assignment) {
        $resource->update(['category_id' => $assignment->resource_category_id]);
        echo "Updated resource {$resource->name} with category {$assignment->resource_category_id}\n";
        $updated++;
    } else {
        echo "No assignment found for resource {$resource->name} (ID: {$resource->mews_id})\n";
        if ($updated < 5) { // Only show first 5 to avoid spam
            echo "  Checking first few assignments...\n";
            $sample = DB::table('mews_resource_category_assignments')->limit(3)->get();
            foreach ($sample as $s) {
                echo "    Sample assignment: resource_id={$s->resource_id}, category_id={$s->resource_category_id}\n";
            }
        }
    }
    
    if ($updated >= 10) break; // Limit for testing
}

echo "Updated $updated out of " . $resources->count() . " resources\n";