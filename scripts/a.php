<?php
/**
 * Create Entity Tables Script
 * Automatically creates tables in v4l.sqlite for all entities defined in the database
 */

require_once __DIR__ . '/../bootstrap.php';

echo "=== Creating Entity Tables ===\n\n";

try {
    // Get all active entities
    $entities = EntityManager::loadEntities();

    echo "Found " . count($entities) . " entities\n\n";

    foreach ($entities as $entity) {
        $tableName = $entity['table_name'];
        $entityCode = $entity['code'];

        echo "Processing: {$entityCode} (table: {$tableName})...\n";

        // Check if table already exists
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=?";
        $result = Database::fetchOne($sql, [$tableName]);

        if ($result) {
            echo "  ✓ Table already exists\n";
            continue;
        }

        // Get attributes
        $attributes = EntityManager::getAttributes($entity['id']);

        if (empty($attributes)) {
            echo "  ⚠ No attributes defined, skipping\n";
            continue;
        }

        // Build CREATE TABLE statement
        $columns = [];

        // Standard columns
        $columns[] = "id TEXT PRIMARY KEY";
        $columns[] = "created_at TEXT DEFAULT (datetime('now'))";
        $columns[] = "updated_at TEXT DEFAULT (datetime('now'))";
        $columns[] = "deleted_at TEXT";
        $columns[] = "version_no INTEGER DEFAULT 1";
        $columns[] = "changed_by TEXT";

        // Entity-specific columns
        foreach ($attributes as $attr) {
            if (isset($attr['is_system']) && $attr['is_system'] == 1) {
                continue; // Skip system attributes
            }

            $colDef = $attr['code'] . ' ';

            // Map data types
            switch ($attr['data_type']) {
                case 'text':
                    $colDef .= 'TEXT';
                    break;
                case 'number':
                    $colDef .= 'REAL';
                    break;
                case 'integer':
                    $colDef .= 'INTEGER';
                    break;
                case 'boolean':
                    $colDef .= 'INTEGER DEFAULT 0';
                    break;
                case 'date':
                case 'datetime':
                    $colDef .= 'TEXT';
                    break;
                case 'json':
                    $colDef .= 'TEXT';
                    break;
                default:
                    $colDef .= 'TEXT';
            }

            // Add constraints
            if (isset($attr['is_required']) && $attr['is_required'] == 1) {
                $colDef .= ' NOT NULL';
            }

            if (isset($attr['is_unique']) && $attr['is_unique'] == 1) {
                $colDef .= ' UNIQUE';
            }

            if (isset($attr['default_value']) && $attr['default_value']) {
                $colDef .= " DEFAULT '" . str_replace("'", "''", $attr['default_value']) . "'";
            }

            $columns[] = $colDef;
        }

        $columnsSql = implode(",\n    ", $columns);
        $createSql = "CREATE TABLE {$tableName} (\n    {$columnsSql}\n)";

        // Execute CREATE TABLE
        Database::execute($createSql);

        echo "  ✓ Table created successfully\n";
        echo "    Columns: " . count($attributes) . " attributes + 6 system fields\n";

    }

    echo "\n=== Complete ===\n";
    echo "All entity tables have been created.\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
