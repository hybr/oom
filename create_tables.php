<?php
/**
 * Create Database Tables from Metadata
 *
 * This script reads entity metadata and creates actual database tables
 */

require_once __DIR__ . '/config/config.php';
require_once LIB_PATH . '/core/Autoloader.php';

use V4L\Core\Autoloader;
use V4L\Core\Database;

Autoloader::register();

echo "========================================\n";
echo "V4L Table Creation Script\n";
echo "========================================\n\n";

try {
    $db = Database::getConnection();

    // Get all entity definitions
    $entities = Database::fetchAll(
        "SELECT * FROM entity_definition WHERE is_active = 1 ORDER BY code"
    );

    echo "Found " . count($entities) . " entities to process...\n\n";

    $created = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($entities as $entity) {
        $tableName = $entity['table_name'];
        $entityCode = $entity['code'];

        // Check if table already exists
        if (Database::tableExists($tableName)) {
            echo "[SKIP] Table '$tableName' already exists\n";
            $skipped++;
            continue;
        }

        echo "[CREATE] Creating table '$tableName' for entity '$entityCode'...\n";

        // Get attributes for this entity
        $attributes = Database::fetchAll(
            "SELECT * FROM entity_attribute
             WHERE entity_id = :entity_id
             ORDER BY display_order",
            [':entity_id' => $entity['id']]
        );

        if (empty($attributes)) {
            echo "  [WARNING] No attributes found for entity '$entityCode'\n";
            continue;
        }

        // Build CREATE TABLE statement
        $columns = [];
        $primaryKey = null;
        $hasIdColumn = false;
        $hasCreatedAt = false;
        $hasUpdatedAt = false;

        // Check what system columns are already defined
        foreach ($attributes as $attr) {
            if ($attr['code'] === 'id') $hasIdColumn = true;
            if ($attr['code'] === 'created_at') $hasCreatedAt = true;
            if ($attr['code'] === 'updated_at') $hasUpdatedAt = true;
        }

        // Add id column first if not present
        if (!$hasIdColumn) {
            $columns[] = "`id` TEXT PRIMARY KEY";
            $primaryKey = 'id';
        }

        foreach ($attributes as $attr) {
            $colDef = "`{$attr['code']}` ";

            // Map data type
            switch (strtoupper($attr['data_type'])) {
                case 'UUID':
                case 'TEXT':
                case 'STRING':
                case 'VARCHAR':
                case 'EMAIL':
                case 'URL':
                    $colDef .= 'TEXT';
                    break;
                case 'INTEGER':
                case 'INT':
                    $colDef .= 'INTEGER';
                    break;
                case 'DECIMAL':
                case 'NUMBER':
                case 'FLOAT':
                    $colDef .= 'REAL';
                    break;
                case 'BOOLEAN':
                case 'BOOL':
                    $colDef .= 'INTEGER';
                    break;
                case 'DATE':
                case 'DATETIME':
                case 'TIMESTAMP':
                    $colDef .= 'TEXT';
                    break;
                case 'JSON':
                    $colDef .= 'TEXT';
                    break;
                case 'ENUM_STRINGS':
                    $colDef .= 'TEXT';
                    break;
                default:
                    $colDef .= 'TEXT';
            }

            // Add constraints
            if ($attr['is_required']) {
                $colDef .= ' NOT NULL';
            }

            if ($attr['default_value']) {
                $defaultValue = $attr['default_value'];
                // Handle special defaults
                if ($defaultValue === 'datetime("now")' || $defaultValue === "datetime('now')") {
                    $colDef .= " DEFAULT (datetime('now'))";
                } elseif (is_numeric($defaultValue)) {
                    $colDef .= " DEFAULT $defaultValue";
                } else {
                    $colDef .= " DEFAULT '" . str_replace("'", "''", $defaultValue) . "'";
                }
            }

            if ($attr['is_unique']) {
                $colDef .= ' UNIQUE';
            }

            // Primary key
            if ($attr['code'] === 'id') {
                $colDef .= ' PRIMARY KEY';
                $primaryKey = $attr['code'];
            }

            $columns[] = $colDef;
        }

        // Add created_at and updated_at if not present
        if (!$hasCreatedAt) {
            $columns[] = "`created_at` TEXT DEFAULT (datetime('now'))";
        }
        if (!$hasUpdatedAt) {
            $columns[] = "`updated_at` TEXT DEFAULT (datetime('now'))";
        }

        // Create table
        $createSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (\n  " .
                     implode(",\n  ", $columns) .
                     "\n)";

        try {
            Database::query($createSQL);
            echo "  [OK] Table '$tableName' created successfully\n";
            $created++;
        } catch (Exception $e) {
            echo "  [ERROR] Failed to create table '$tableName': " . $e->getMessage() . "\n";
            $errors++;
        }
    }

    echo "\n========================================\n";
    echo "Table Creation Summary\n";
    echo "========================================\n";
    echo "Created: $created\n";
    echo "Skipped: $skipped\n";
    echo "Errors: $errors\n";
    echo "Total: " . count($entities) . "\n";
    echo "\nDone!\n";

} catch (Exception $e) {
    echo "\n[FATAL ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
