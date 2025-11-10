<?php

// Simple HTTP client for Mews API testing
function makeRequest($url, $data) {
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($data)
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Mews API credentials (hardcoded for testing)
$clientToken = 'E0D439EE522F44368DC78E1BFB03710C-D24FB11DBE31D4621C4817E028D9E1D';
$accessToken = 'C66EF7B239D24632943D115EDE9CB810-EA00F8FD8294692C940F6B5A8F9453D';
$baseUrl = 'https://api.mews-demo.com';

echo "=== Testing Mews API Endpoints ===" . PHP_EOL;

// Test Configuration endpoint
echo "1. Testing Configuration endpoint..." . PHP_EOL;
try {
    $response = makeRequest($baseUrl . '/api/connector/v1/configuration/get', [
        'ClientToken' => $clientToken,
        'AccessToken' => $accessToken
    ]);

    if ($response) {
        echo "✓ Configuration endpoint successful" . PHP_EOL;
        
        // Print enterprise info
        if (isset($response['Enterprise'])) {
            $enterprise = $response['Enterprise'];
            echo "Enterprise ID: " . ($enterprise['Id'] ?? 'N/A') . PHP_EOL;
            echo "Enterprise Name: " . ($enterprise['Name'] ?? 'N/A') . PHP_EOL;
        }
        
        // Print services info
        if (isset($response['Services'])) {
            echo "Configuration Services count: " . count($response['Services']) . PHP_EOL;
            if (!empty($response['Services'])) {
                echo "First service structure:" . PHP_EOL;
                print_r($response['Services'][0]);
            } else {
                echo "No services found in configuration" . PHP_EOL;
            }
        }
    } else {
        echo "✗ Configuration failed" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✗ Configuration error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test Services endpoint
echo "2. Testing Services endpoint..." . PHP_EOL;
try {
    $response = makeRequest($baseUrl . '/api/connector/v1/services/getAll', [
        'ClientToken' => $clientToken,
        'AccessToken' => $accessToken,
        'ServiceIds' => []
    ]);

    if ($response) {
        echo "✓ Services endpoint successful" . PHP_EOL;
        echo "Response keys: " . implode(', ', array_keys($response)) . PHP_EOL;
        
        if (isset($response['Services'])) {
            echo "Services count: " . count($response['Services']) . PHP_EOL;
            if (!empty($response['Services'])) {
                echo "First service keys: " . implode(', ', array_keys($response['Services'][0])) . PHP_EOL;
                echo "First service data:" . PHP_EOL;
                print_r($response['Services'][0]);
            } else {
                echo "No services found in response" . PHP_EOL;
            }
        }
    } else {
        echo "✗ Services failed" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✗ Services error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test Resources endpoint  
echo "3. Testing Resources endpoint..." . PHP_EOL;
try {
    $response = makeRequest($baseUrl . '/api/connector/v1/resources/getAll', [
        'ClientToken' => $clientToken,
        'AccessToken' => $accessToken,
        'ResourceIds' => []
    ]);

    if ($response) {
        echo "✓ Resources endpoint successful" . PHP_EOL;
        echo "Response keys: " . implode(', ', array_keys($response)) . PHP_EOL;
        
        if (isset($response['Resources'])) {
            echo "Resources count: " . count($response['Resources']) . PHP_EOL;
        }
        
        if (isset($response['ResourceCategories'])) {
            echo "Resource Categories count: " . count($response['ResourceCategories']) . PHP_EOL;
        }
    } else {
        echo "✗ Resources failed" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "✗ Resources error: " . $e->getMessage() . PHP_EOL;
}