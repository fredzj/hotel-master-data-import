<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$clientToken = config('services.mews.client_token');
$accessToken = config('services.mews.access_token');
$client = config('services.mews.client');
$baseUrl = config('services.mews.base_url');

echo "Testing Mews API connection...\n";
echo "Base URL: {$baseUrl}\n";
echo "Client Token: " . substr($clientToken, 0, 10) . "...\n";
echo "Access Token: " . substr($accessToken, 0, 10) . "...\n\n";

// Test configuration endpoint first
echo "Testing configuration endpoint...\n";
$configResponse = Http::withHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
])->post($baseUrl . '/api/connector/v1/configuration/get', [
    'ClientToken' => $clientToken,
    'AccessToken' => $accessToken,
    'Client' => $client,
]);

echo "Status: " . $configResponse->status() . "\n";
if ($configResponse->successful()) {
    $configData = $configResponse->json();
    echo "Enterprise Name: " . ($configData['Enterprise']['Name'] ?? 'N/A') . "\n";
    echo "Enterprise ID: " . ($configData['Enterprise']['Id'] ?? 'N/A') . "\n\n";
} else {
    echo "Error: " . $configResponse->body() . "\n\n";
}

// Test enterprises endpoint
echo "Testing enterprises endpoint...\n";
$enterprisesResponse = Http::withHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
])->post($baseUrl . '/api/connector/v1/enterprises/getAll', [
    'ClientToken' => $clientToken,
    'AccessToken' => $accessToken,
    'Client' => $client,
]);

echo "Status: " . $enterprisesResponse->status() . "\n";
echo "Response: " . $enterprisesResponse->body() . "\n";