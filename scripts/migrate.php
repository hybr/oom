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
    private $metaDb;
    private $operationalDb;
    private $migrationsPath;

    public function __construct()
    {
        $this->migrationsPath = BASE_PATH . '/metadata';

        // Initialize databases
        $this->metaDb = Database::meta();
        $this->operationalDb = Database::connection('default');

        // Create migration tracking table
        $this->createMigrationTable();
    }

    /**
     * Create migrations tracking table in meta database
     */
    private function createMigrationTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration TEXT UNIQUE NOT NULL,
            executed_at TEXT DEFAULT (datetime('now'))
        )";

        $this->metaDb->exec($sql);

        // Also create data seeds tracking table
        $sql = "CREATE TABLE IF NOT EXISTS data_seeds (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seed_file TEXT UNIQUE NOT NULL,
            executed_at TEXT DEFAULT (datetime('now'))
        )";

        $this->metaDb->exec($sql);
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
        $stmt = $this->metaDb->query("SELECT migration FROM migrations");
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

        $metaStatements = 0;
        $operationalStatements = 0;

        foreach ($statements as $statement) {
            $stmt = trim($statement);
            if (empty($stmt)) continue;

            try {
                // Determine which database to use
                // Table creation goes to operational DB (except entity_ and migrations tables)
                // Entity metadata INSERT/SELECT goes to meta DB
                $isCreateTable = preg_match('/^\s*CREATE\s+TABLE/i', $stmt);
                $isMetaTable = preg_match('/entity_|migrations/i', $stmt);
                $isInsertOrSelect = preg_match('/^\s*(INSERT|SELECT)/i', $stmt);

                if ($isCreateTable && !$isMetaTable) {
                    // Operational table creation
                    $this->operationalDb->exec($stmt);
                    $operationalStatements++;
                } elseif ($isCreateTable && $isMetaTable) {
                    // Meta table creation (entity_definition, etc.)
                    $this->metaDb->exec($stmt);
                    $metaStatements++;
                } elseif ($isInsertOrSelect) {
                    // All INSERTs and SELECTs go to meta database
                    $this->metaDb->exec($stmt);
                    $metaStatements++;
                } else {
                    // Default to meta database
                    $this->metaDb->exec($stmt);
                    $metaStatements++;
                }
            } catch (PDOException $e) {
                // Skip if already exists or is a SELECT statement
                if (strpos($e->getMessage(), 'already exists') === false &&
                    !preg_match('/^\s*SELECT/i', $stmt)) {
                    echo "  ⚠ Warning: " . $e->getMessage() . "\n";
                }
            }
        }

        // Mark migration as completed
        $stmt = $this->metaDb->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $stmt->execute([$migration]);

        echo "  ✓ Completed: {$metaStatements} meta statements, {$operationalStatements} operational statements\n";
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
    }

    /**
     * Ensure entity tables exist from metadata
     */
    private function ensureEntityTables()
    {
        $sql = "SELECT * FROM entity_definition WHERE is_active = 1 ORDER BY code";
        $stmt = $this->metaDb->query($sql);
        $entities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $created = 0;
        $skipped = 0;

        foreach ($entities as $entity) {
            $tableName = $entity['table_name'];
            $entityCode = $entity['code'];

            // Check if table exists
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=?";
            $stmt = $this->operationalDb->prepare($sql);
            $stmt->execute([$tableName]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  ✓ {$entityCode}: table '{$tableName}' exists\n";
                $skipped++;
                continue;
            }

            // Get attributes
            $sql = "SELECT * FROM entity_attribute WHERE entity_id = ? ORDER BY display_order";
            $stmt = $this->metaDb->prepare($sql);
            $stmt->execute([$entity['id']]);
            $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($attributes)) {
                echo "  ⚠ {$entityCode}: no attributes, skipping\n";
                continue;
            }

            // Get relationships where this entity has foreign keys
            // Check both directions: where this is "from" entity OR "to" entity
            $sql = "SELECT * FROM entity_relationship WHERE from_entity_id = ? OR to_entity_id = ?";
            $stmt = $this->metaDb->prepare($sql);
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

            foreach ($attributes as $attr) {
                if (isset($attr['is_system']) && $attr['is_system'] == 1) continue;

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
                    $stmt = $this->metaDb->prepare($sql);
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

            $this->operationalDb->exec($createSql);
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
        $stmt = $this->metaDb->query("SELECT seed_file FROM data_seeds");
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
            return !empty($trimmed) && !preg_match('/^--/', $trimmed);
        });
        $sql = implode("\n", $cleanedLines);

        // Split by semicolon
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        $insertCount = 0;

        foreach ($statements as $stmt) {
            if (empty($stmt)) continue;

            try {
                // All data inserts go to operational database
                $this->operationalDb->exec($stmt);
                $insertCount++;
            } catch (PDOException $e) {
                echo "  ⚠ Warning: " . $e->getMessage() . "\n";
                // Continue with other statements even if one fails
            }
        }

        // Mark seed as executed
        $stmt = $this->metaDb->prepare("INSERT INTO data_seeds (seed_file) VALUES (?)");
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
     * Reset migrations (for development)
     */
    public function reset()
    {
        echo "=== Resetting Migrations ===\n";

        // Drop migration tracking
        $this->metaDb->exec("DROP TABLE IF EXISTS migrations");

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
