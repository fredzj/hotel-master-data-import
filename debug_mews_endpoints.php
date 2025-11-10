<?php

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Http;

// Mews API credentials
$clientToken = env('MEWS_CLIENT_TOKEN');
$accessToken = env('MEWS_ACCESS_TOKEN');

if (!$clientToken || !$accessToken) {
    echo "Error: Mews API credentials not found in environment variables\n";
    exit(1);
}

$baseUrl = 'https://api.mews-demo.com';

echo "=== Testing Mews API Endpoints ===" . PHP_EOL;

// Test Configuration endpoint
echo "1. Testing Configuration endpoint..." . PHP_EOL;
try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])->post($baseUrl . '/api/connector/v1/configuration/get', [
        'ClientToken' => $clientToken,
        'AccessToken' => $accessToken
    ]);

    if ($response->successful()) {
        $data = $response->json();
        echo "✓ Configuration endpoint successful" . PHP_EOL;
        
        // Print enterprise info
        if (isset($data['Enterprise'])) {
            $enterprise = $data['Enterprise'];
            echo "Enterprise ID: " . ($enterprise['Id'] ?? 'N/A') . PHP_EOL;
            echo "Enterprise Name: " . ($enterprise['Name'] ?? 'N/A') . PHP_EOL;
        }
        
        // Print services info
        if (isset($data['Services'])) {
            echo "Services count: " . count($data['Services']) . PHP_EOL;
            foreach ($data['Services'] as $service) {
                echo "Service: " . ($service['Name'] ?? 'Unnamed') . " (ID: " . ($service['Id'] ?? 'N/A') . ")" . PHP_EOL;
            }
        }
    } else {
        echo "✗ Configuration failed: " . $response->status() . " - " . $response->body() . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✗ Configuration error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test Services endpoint
echo "2. Testing Services endpoint..." . PHP_EOL;
try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])->post($baseUrl . '/api/connector/v1/services/getAll', [
        'ClientToken' => $clientToken,
        'AccessToken' => $accessToken,
        'ServiceIds' => []
    ]);

    if ($response->successful()) {
        $data = $response->json();
        echo "✓ Services endpoint successful" . PHP_EOL;
        echo "Response structure: " . PHP_EOL;
        print_r(array_keys($data));
        
        if (isset($data['Services']) && !empty($data['Services'])) {
            echo "First service structure:" . PHP_EOL;
            print_r($data['Services'][0]);
        }
    } else {
        echo "✗ Services failed: " . $response->status() . " - " . $response->body() . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✗ Services error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test Resources endpoint  
echo "3. Testing Resources endpoint..." . PHP_EOL;
try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])->post($baseUrl . '/api/connector/v1/resources/getAll', [
        'ClientToken' => $clientToken,
        'AccessToken' => $accessToken,
        'ResourceIds' => []
    ]);

    if ($response->successful()) {
        $data = $response->json();
        echo "✓ Resources endpoint successful" . PHP_EOL;
        echo "Response structure: " . PHP_EOL;
        print_r(array_keys($data));
        
        if (isset($data['Resources']) && !empty($data['Resources'])) {
            echo "Resources count: " . count($data['Resources']) . PHP_EOL;
            echo "First resource structure:" . PHP_EOL;
            print_r($data['Resources'][0]);
        }
    } else {
        echo "✗ Resources failed: " . $response->status() . " - " . $response->body() . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✗ Resources error: " . $e->getMessage() . PHP_EOL;
}