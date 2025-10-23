<?php
/**
 * Database Migration Runner
 * Runs SQL migration files from metadata folder and tracks completed migrations
 */

// Set base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/lib/Database.php';

class Migrator
{
    private $db;
    private $migrationsPath;

    public function __construct()
    {
        $this->migrationsPath = BASE_PATH . '/metadata';

        // Initialize database connection
        $this->db = Database::connection('default');

        // Create migration tracking table
        $this->createMigrationTable();
    }

    /**
     * Create migrations tracking table in database
     */
    private function createMigrationTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration TEXT UNIQUE NOT NULL,
            executed_at TEXT DEFAULT (datetime('now'))
        )";

        $this->db->exec($sql);

        // Also create data seeds tracking table
        $sql = "CREATE TABLE IF NOT EXISTS data_seeds (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seed_file TEXT UNIQUE NOT NULL,
            executed_at TEXT DEFAULT (datetime('now'))
        )";

        $this->db->exec($sql);
        echo "✓ Migration tracking tables ready\n";
    }

    /**
     * Get list of pending migrations
     */
    private function getPendingMigrations()
    {
        // Get all migration files
        $files = glob($this->migrationsPath . '/*.sql');
        sort($files);

        // Get completed migrations
        $stmt = $this->db->query("SELECT migration FROM migrations");
        $completed = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Filter to get pending only
        $pending = [];
        foreach ($files as $file) {
            $migration = basename($file);
            if (!in_array($migration, $completed)) {
                $pending[] = $file;
            }
        }

        return $pending;
    }

    /**
     * Execute a migration file
     */
    private function executeMigration($file)
    {
        $migration = basename($file);
        echo "\nExecuting: {$migration}\n";

        $sql = file_get_contents($file);

        // Remove comments first
        $lines = explode("\n", $sql);
        $cleanedLines = array_filter($lines, function($line) {
            $trimmed = trim($line);
            return !empty($trimmed) && !preg_match('/^--/', $trimmed);
        });
        $cleanedSql = implode("\n", $cleanedLines);

        // Split into separate statements
        $statements = array_filter(
            array_map('trim', explode(';', $cleanedSql)),
            function($stmt) {
                return !empty(trim($stmt));
            }
        );

        $executedStatements = 0;

        foreach ($statements as $statement) {
            $stmt = trim($statement);
            if (empty($stmt)) continue;

            try {
                // Execute all statements in the same database
                $this->db->exec($stmt);
                $executedStatements++;
            } catch (PDOException $e) {
                // Skip if already exists or is a SELECT statement
                if (strpos($e->getMessage(), 'already exists') === false &&
                    !preg_match('/^\s*SELECT/i', $stmt)) {
                    echo "  ⚠ Warning: " . $e->getMessage() . "\n";
                }
            }
        }

        // Mark migration as completed
        $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $stmt->execute([$migration]);

        echo "  ✓ Completed: {$executedStatements} statements executed\n";
    }

    /**
     * Run all pending migrations
     */
    public function migrate()
    {
        echo "=== Database Migration ===\n";

        $pending = $this->getPendingMigrations();

        if (empty($pending)) {
            echo "\n✓ No pending migrations\n";
        } else {
            echo "\nFound " . count($pending) . " pending migration(s):\n";
            foreach ($pending as $file) {
                echo "  - " . basename($file) . "\n";
            }

            foreach ($pending as $file) {
                $this->executeMigration($file);
            }

            echo "\n✓ All migrations completed successfully!\n";
        }

        // Always ensure entity tables exist (even if no pending migrations)
        echo "\n=== Ensuring Entity Tables ===\n";
        $this->ensureEntityTables();

        // Run data seeds
        echo "\n=== Running Data Seeds ===\n";
        $this->runDataSeeds();

        // Run process definitions
        echo "\n=== Running Process Definitions ===\n";
        $this->runProcessDefinitions();
    }

    /**
     * Ensure entity tables exist from metadata
     */
    private function ensureEntityTables()
    {
        $sql = "SELECT * FROM entity_definition WHERE is_active = 1 ORDER BY code";
        $stmt = $this->db->query($sql);
        $entities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $created = 0;
        $skipped = 0;

        foreach ($entities as $entity) {
            $tableName = $entity['table_name'];
            $entityCode = $entity['code'];

            // Check if table exists
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tableName]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  ✓ {$entityCode}: table '{$tableName}' exists\n";
                $skipped++;
                continue;
            }

            // Get attributes
            $sql = "SELECT * FROM entity_attribute WHERE entity_id = ? ORDER BY display_order";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entity['id']]);
            $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($attributes)) {
                echo "  ⚠ {$entityCode}: no attributes, skipping\n";
                continue;
            }

            // Get relationships where this entity has foreign keys
            // Check both directions: where this is "from" entity OR "to" entity
            $sql = "SELECT * FROM entity_relationship WHERE from_entity_id = ? OR to_entity_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entity['id'], $entity['id']]);
            $relationships = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Build CREATE TABLE
            $columns = [
                "id TEXT PRIMARY KEY",
                "created_at TEXT DEFAULT (datetime('now'))",
                "updated_at TEXT DEFAULT (datetime('now'))",
                "deleted_at TEXT",
                "version_no INTEGER DEFAULT 1",
                "changed_by TEXT"
            ];

            // Standard field names that are already included above
            $standardFields = ['id', 'created_at', 'updated_at', 'deleted_at', 'version_no', 'changed_by'];

            foreach ($attributes as $attr) {
                // Skip system attributes
                if (isset($attr['is_system']) && $attr['is_system'] == 1) continue;

                // Skip attributes that conflict with standard field names
                if (in_array($attr['code'], $standardFields)) continue;

                $colDef = $attr['code'] . ' ';
                $colDef .= match($attr['data_type']) {
                    'number' => 'REAL',
                    'integer' => 'INTEGER',
                    'boolean' => 'INTEGER DEFAULT 0',
                    default => 'TEXT'
                };

                if (isset($attr['is_required']) && $attr['is_required'] == 1) $colDef .= ' NOT NULL';
                if (isset($attr['is_unique']) && $attr['is_unique'] == 1) $colDef .= ' UNIQUE';

                $columns[] = $colDef;
            }

            // Add foreign key constraints
            // FK field exists in current table and points to the other entity in the relationship
            $foreignKeys = [];
            $attributeCodes = array_column($attributes, 'code');

            foreach ($relationships as $rel) {
                // Check if the foreign key field exists in this entity's attributes
                if (in_array($rel['fk_field'], $attributeCodes)) {
                    // Determine which entity this FK points to
                    // If this entity is the "from" entity, FK points to "to" entity
                    // If this entity is the "to" entity, FK points to "from" entity
                    $targetEntityId = ($entity['id'] === $rel['from_entity_id'])
                        ? $rel['to_entity_id']
                        : $rel['from_entity_id'];

                    $sql = "SELECT table_name FROM entity_definition WHERE id = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$targetEntityId]);
                    $targetEntity = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($targetEntity) {
                        $foreignKeys[] = "FOREIGN KEY({$rel['fk_field']}) REFERENCES {$targetEntity['table_name']}(id)";
                    }
                }
            }

            // Combine columns and foreign keys
            $allConstraints = array_merge($columns, $foreignKeys);
            $createSql = "CREATE TABLE {$tableName} (\n    " . implode(",\n    ", $allConstraints) . "\n)";

            $this->db->exec($createSql);
            echo "  ✓ {$entityCode}: created table '{$tableName}'\n";
            $created++;
        }

        echo "\n  Summary: Created {$created}, Already existed {$skipped}\n";
    }

    /**
     * Get list of pending data seed files
     */
    private function getPendingDataSeeds()
    {
        $dataPath = $this->migrationsPath . '/data';

        // Check if data directory exists
        if (!is_dir($dataPath)) {
            return [];
        }

        // Get all .sql files in data directory
        $files = glob($dataPath . '/*.sql');
        sort($files);

        // Get already executed seeds
        $stmt = $this->db->query("SELECT seed_file FROM data_seeds");
        $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Filter out executed ones
        $pending = [];
        foreach ($files as $file) {
            $basename = basename($file);
            if (!in_array($basename, $executed)) {
                $pending[] = $file;
            }
        }

        return $pending;
    }

    /**
     * Execute a data seed file
     */
    private function executeDataSeed($file)
    {
        $basename = basename($file);
        echo "\n  Processing: {$basename}\n";

        $sql = file_get_contents($file);

        // Remove comments and split into statements
        $lines = explode("\n", $sql);
        $cleanedLines = array_filter($lines, function($line) {
            $trimmed = trim($line);
            // Keep line if it's not empty (checking length, not truthiness) and doesn't start with --
            return strlen($trimmed) > 0 && !preg_match('/^--/', $trimmed);
        });
        $sql = implode("\n", $cleanedLines);

        // Split by semicolon
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        $insertCount = 0;

        foreach ($statements as $stmt) {
            if (empty($stmt)) continue;

            try {
                // All data inserts go to the database
                $this->db->exec($stmt);
                $insertCount++;
            } catch (PDOException $e) {
                echo "  ⚠ Warning: " . $e->getMessage() . "\n";
                // Continue with other statements even if one fails
            }
        }

        // Mark seed as executed
        $stmt = $this->db->prepare("INSERT INTO data_seeds (seed_file) VALUES (?)");
        $stmt->execute([$basename]);

        echo "  ✓ Completed: {$insertCount} statements executed\n";
    }

    /**
     * Run all pending data seeds
     */
    private function runDataSeeds()
    {
        $pending = $this->getPendingDataSeeds();

        if (empty($pending)) {
            echo "  ✓ No pending data seeds\n";
            return;
        }

        echo "  Found " . count($pending) . " pending data seed(s):\n";
        foreach ($pending as $file) {
            echo "    - " . basename($file) . "\n";
        }

        foreach ($pending as $file) {
            $this->executeDataSeed($file);
        }

        echo "\n  ✓ All data seeds completed successfully!\n";
    }

    /**
     * Get list of pending process definition files
     */
    private function getPendingProcessDefinitions()
    {
        $processPath = $this->migrationsPath . '/processes';

        // Check if processes directory exists
        if (!is_dir($processPath)) {
            return [];
        }

        // Get all .sql files in processes directory
        $files = glob($processPath . '/*.sql');
        sort($files);

        // Get already executed process definitions
        $stmt = $this->db->query("SELECT seed_file FROM data_seeds WHERE seed_file LIKE 'process_%'");
        $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Filter out executed ones
        $pending = [];
        foreach ($files as $file) {
            $basename = basename($file);
            // Use 'process_' prefix to distinguish from data seeds
            $trackingName = 'process_' . $basename;
            if (!in_array($trackingName, $executed)) {
                $pending[] = $file;
            }
        }

        return $pending;
    }

    /**
     * Execute a process definition file
     */
    private function executeProcessDefinition($file)
    {
        $basename = basename($file);
        echo "\n  Installing process: {$basename}\n";

        // Temporarily disable foreign key constraints for process installation
        $this->db->exec("PRAGMA foreign_keys = OFF");

        $sql = file_get_contents($file);

        // Remove comments and split into statements
        $lines = explode("\n", $sql);
        $cleanedLines = array_filter($lines, function($line) {
            $trimmed = trim($line);
            // Keep line if it's not empty (checking length, not truthiness) and doesn't start with --
            return strlen($trimmed) > 0 && !preg_match('/^--/', $trimmed);
        });
        $sql = implode("\n", $cleanedLines);

        // Split by semicolon
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        $executedCount = 0;

        foreach ($statements as $stmt) {
            if (empty($stmt)) continue;

            try {
                // Execute process definition statements
                $this->db->exec($stmt);
                $executedCount++;
            } catch (PDOException $e) {
                // Check if it's a SELECT statement (result message)
                if (preg_match('/^\s*SELECT/i', $stmt)) {
                    // Execute SELECT and display results
                    $result = $this->db->query($stmt)->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        foreach ($result as $key => $value) {
                            echo "    ➜ {$key}: {$value}\n";
                        }
                    }
                } else if (strpos($e->getMessage(), 'already exists') === false &&
                           strpos($e->getMessage(), 'UNIQUE constraint') === false) {
                    echo "    ⚠ Warning: " . $e->getMessage() . "\n";
                    // Only show statement preview if it contains "syntax error"
                    if (strpos($e->getMessage(), 'syntax error') !== false) {
                        // Show full statement for syntax errors
                        echo "       Full statement:\n" . $stmt . "\n";
                    }
                }
            }
        }

        // Re-enable foreign key constraints
        $this->db->exec("PRAGMA foreign_keys = ON");

        // Mark process definition as executed (with 'process_' prefix)
        $trackingName = 'process_' . $basename;
        $stmt = $this->db->prepare("INSERT INTO data_seeds (seed_file) VALUES (?)");
        $stmt->execute([$trackingName]);

        echo "    ✓ Completed: {$executedCount} statements executed\n";
    }

    /**
     * Run all pending process definitions
     */
    private function runProcessDefinitions()
    {
        $pending = $this->getPendingProcessDefinitions();

        if (empty($pending)) {
            echo "  ✓ No pending process definitions\n";
            return;
        }

        echo "  Found " . count($pending) . " pending process definition(s):\n";
        foreach ($pending as $file) {
            echo "    - " . basename($file) . "\n";
        }

        foreach ($pending as $file) {
            $this->executeProcessDefinition($file);
        }

        echo "\n  ✓ All process definitions installed successfully!\n";
    }

    /**
     * Reset migrations (for development)
     */
    public function reset()
    {
        echo "=== Resetting Migrations ===\n";

        // Drop migration tracking
        $this->db->exec("DROP TABLE IF EXISTS migrations");

        // Recreate tracking table
        $this->createMigrationTable();

        echo "✓ Migration tracking reset\n";
    }
}

// Parse command line arguments
$command = $argv[1] ?? 'migrate';

$migrator = new Migrator();

switch ($command) {
    case 'migrate':
        $migrator->migrate();
        break;

    case 'reset':
        $migrator->reset();
        $migrator->migrate();
        break;

    default:
        echo "Usage: php migrate.php [migrate|reset]\n";
        echo "  migrate - Run pending migrations\n";
        echo "  reset   - Reset and re-run all migrations\n";
        exit(1);
}
