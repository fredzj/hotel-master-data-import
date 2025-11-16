<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Mews API Response for Resource Category Assignments ===\n\n";

// Get Mews configuration
$accessToken = config('services.mews.access_token');
$clientToken = config('services.mews.client_token');
$baseUrl = config('services.mews.base_url');
$enterpriseId = config('services.mews.enterprise_id');

if (!$accessToken || !$clientToken) {
    echo "ERROR: Mews credentials not configured!\n";
    exit(1);
}

echo "Enterprise ID: $enterpriseId\n";
echo "Base URL: $baseUrl\n\n";

// Make API request to get resources
$response = \Illuminate\Support\Facades\Http::post($baseUrl . '/api/connector/v1/resources/getAll', [
    'ClientToken' => $clientToken,
    'AccessToken' => $accessToken,
    'EnterpriseIds' => [$enterpriseId],
]);

if (!$response->successful()) {
    echo "ERROR: API request failed!\n";
    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
    exit(1);
}

$data = $response->json();

echo "=== API Response Summary ===\n";
echo "Resources count: " . count($data['Resources'] ?? []) . "\n";
echo "ResourceCategoryAssignments count: " . count($data['ResourceCategoryAssignments'] ?? []) . "\n\n";

if (isset($data['ResourceCategoryAssignments']) && count($data['ResourceCategoryAssignments']) > 0) {
    echo "Sample ResourceCategoryAssignments (first 5):\n";
    foreach (array_slice($data['ResourceCategoryAssignments'], 0, 5) as $assignment) {
        print_r($assignment);
    }
} else {
    echo "WARNING: No ResourceCategoryAssignments in API response!\n";
    echo "This is why the assignments table is empty.\n\n";
    
    echo "Checking if Resources have CategoryId directly:\n";
    if (isset($data['Resources']) && count($data['Resources']) > 0) {
        $sampleResource = $data['Resources'][0];
        echo "Sample Resource structure:\n";
        print_r($sampleResource);
    }
}
