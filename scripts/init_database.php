<?php

require_once __DIR__ . '/../config/database.php';

echo "Initializing database...\n";

// Initialize database tables for all entities
$entities = [
    'Continent',
    'Language',
    'Country',
    'Person',
    'PersonCredential',
    'IndustryCategory',
    'OrganizationLegalType',
    'Organization'
];

foreach ($entities as $entityType) {
    try {
        echo "Creating table for {$entityType}...\n";

        require_once __DIR__ . "/../entities/{$entityType}.php";
        $entity = new $entityType();
        $entity->createTable();

        echo "✓ {$entityType} table created successfully\n";
    } catch (Exception $e) {
        echo "✗ Error creating {$entityType} table: " . $e->getMessage() . "\n";
    }
}

// Seed basic data
echo "\nSeeding basic data...\n";

try {
    // Seed industry categories
    require_once __DIR__ . '/../entities/IndustryCategory.php';
    IndustryCategory::seedIndustryTaxonomy();
    echo "✓ Industry categories seeded successfully\n";
} catch (Exception $e) {
    echo "✗ Error seeding industry categories: " . $e->getMessage() . "\n";
}

try {
    // Seed organization legal types
    require_once __DIR__ . '/../entities/OrganizationLegalType.php';
    OrganizationLegalType::seedOrganizationLegalTypes();
    echo "✓ Organization legal types seeded successfully\n";
} catch (Exception $e) {
    echo "✗ Error seeding organization legal types: " . $e->getMessage() . "\n";
}

echo "\nDatabase initialization completed!\n";
echo "You can now use the application with proper data.\n";