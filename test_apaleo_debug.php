<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\PmsAdapters\ApaleoAdapter;

echo "=== Apaleo Connection Debug ===\n";
echo "Testing Apaleo authentication...\n\n";

// Check configuration
echo "1. Checking configuration:\n";
echo "Client ID: " . config('services.apaleo.client_id') . "\n";
echo "Client Secret: " . (config('services.apaleo.client_secret') ? '***' . substr(config('services.apaleo.client_secret'), -4) : 'NOT SET') . "\n";
echo "Base URL: " . config('services.apaleo.base_url') . "\n";
echo "Identity URL: " . config('services.apaleo.identity_url') . "\n\n";

// Test authentication step by step
echo "2. Testing authentication:\n";
try {
    $adapter = new ApaleoAdapter();
    
    echo "- Creating adapter... ✅\n";
    
    // Test authenticate method directly
    echo "- Attempting authentication...\n";
    $authResult = $adapter->authenticate();
    
    if ($authResult) {
        echo "- Authentication successful! ✅\n";
        
        // Test connection method
        echo "- Testing connection method...\n";
        if ($adapter->testConnection()) {
            echo "- Connection test successful! ✅\n";
        } else {
            echo "- Connection test failed! ❌\n";
        }
    } else {
        echo "- Authentication failed! ❌\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n3. Checking jobs table:\n";
$jobCount = DB::table('jobs')->count();
echo "Jobs in queue: $jobCount\n";

if ($jobCount > 0) {
    echo "\nSample job data:\n";
    $job = DB::table('jobs')->first();
    echo "Job ID: " . $job->id . "\n";
    echo "Attempts: " . $job->attempts . "\n";
    echo "Created: " . date('Y-m-d H:i:s', $job->created_at) . "\n";
}

echo "\n=== Debug Complete ===\n";