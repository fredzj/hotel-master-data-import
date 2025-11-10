<?php

// Basic database connection and query
$host = 'localhost';
$dbname = 'u10919p130675_hmdi2'; // Your database name
$username = 'u10919p130675_hmdi2'; // Your database user
$password = 'n4xng&wWKyJN'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    echo "=== Mews Import Summary ===" . PHP_EOL;
    
    // Check enterprises
    $enterprises = $pdo->query("SELECT COUNT(*) FROM mews_enterprises")->fetchColumn();
    echo "Enterprises: $enterprises" . PHP_EOL;
    
    if ($enterprises > 0) {
        $enterprise = $pdo->query("SELECT name, time_zone_identifier FROM mews_enterprises LIMIT 1")->fetch();
        echo "  - Name: " . $enterprise['name'] . PHP_EOL;
        echo "  - Timezone: " . $enterprise['time_zone_identifier'] . PHP_EOL;
    }
    
    // Check services
    $services = $pdo->query("SELECT COUNT(*) FROM mews_services")->fetchColumn();
    echo "Services: $services" . PHP_EOL;
    
    if ($services > 0) {
        $servicesSample = $pdo->query("SELECT name, data_discriminator FROM mews_services LIMIT 3")->fetchAll();
        foreach ($servicesSample as $service) {
            echo "  - " . $service['name'] . " (" . $service['data_discriminator'] . ")" . PHP_EOL;
        }
    }
    
    // Check resources
    $resources = $pdo->query("SELECT COUNT(*) FROM mews_resources")->fetchColumn();
    echo "Resources: $resources" . PHP_EOL;
    
    if ($resources > 0) {
        $resourcesSample = $pdo->query("SELECT name, data_discriminator FROM mews_resources LIMIT 3")->fetchAll();
        foreach ($resourcesSample as $resource) {
            echo "  - " . $resource['name'] . " (" . $resource['data_discriminator'] . ")" . PHP_EOL;
        }
    }
    
    // Check resource categories
    $categories = $pdo->query("SELECT COUNT(*) FROM mews_resource_categories")->fetchColumn();
    echo "Resource Categories: $categories" . PHP_EOL;
    
    // Check assignments
    $categoryAssignments = $pdo->query("SELECT COUNT(*) FROM mews_resource_category_assignments")->fetchColumn();
    $featureAssignments = $pdo->query("SELECT COUNT(*) FROM mews_resource_feature_assignments")->fetchColumn();
    echo "Category Assignments: $categoryAssignments" . PHP_EOL;
    echo "Feature Assignments: $featureAssignments" . PHP_EOL;
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . PHP_EOL;
}