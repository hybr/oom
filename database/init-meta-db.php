<?php
/**
 * Meta Database Initialization Script
 * Creates the SQLite meta database with all entity definitions from metadata.txt
 */

require_once __DIR__ . '/../bootstrap.php';

echo "Initializing Meta Database...\n";

try {
    $metaDbPath = Config::get('database.meta.path');
    $metaDir = dirname($metaDbPath);

    // Ensure directory exists
    if (!is_dir($metaDir)) {
        mkdir($metaDir, 0755, true);
    }

    // Remove existing database if present
    if (file_exists($metaDbPath)) {
        unlink($metaDbPath);
        echo "Removed existing meta database.\n";
    }

    // Create new SQLite database connection
    $db = new PDO('sqlite:' . $metaDbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Created new meta database at: {$metaDbPath}\n";

    // Read and execute the metadata SQL script
    $metadataFile = BASE_PATH . '/metadata.txt';

    if (!file_exists($metadataFile)) {
        throw new Exception("Metadata file not found: {$metadataFile}");
    }

    $sql = file_get_contents($metadataFile);

    // Execute the SQL script
    $db->exec($sql);

    echo "Executed metadata SQL script.\n";

    // Verify the data was inserted
    $tables = [
        'entity_definition',
        'entity_attribute',
        'entity_relationship',
        'entity_function',
        'entity_function_handler',
        'entity_validation_rule'
    ];

    echo "\nVerification:\n";
    echo str_repeat('=', 50) . "\n";

    foreach ($tables as $table) {
        $stmt = $db->query("SELECT COUNT(*) as cnt FROM {$table}");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        echo sprintf("%-30s: %4d records\n", $table, $count);
    }

    echo str_repeat('=', 50) . "\n";
    echo "\nMeta database initialized successfully!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
