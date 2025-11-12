<?php
/**
 * Import Entity Data from Metadata Files
 *
 * This script extracts and imports entity data from metadata/entities/*.sql files
 * Should be run AFTER create_tables.php
 */

require_once __DIR__ . '/config/config.php';
require_once LIB_PATH . '/core/Autoloader.php';

use V4L\Core\Autoloader;
use V4L\Core\Database;

Autoloader::register();

echo "========================================\n";
echo "V4L Entity Data Import Script\n";
echo "========================================\n\n";

try {
    $db = Database::getConnection();

    // Get all entity SQL files
    $entityFiles = glob(__DIR__ . '/metadata/entities/*.sql');

    if (empty($entityFiles)) {
        echo "No entity files found.\n";
        exit(0);
    }

    // Sort files to maintain dependency order
    sort($entityFiles);

    echo "Found " . count($entityFiles) . " entity files to process...\n\n";

    $imported = 0;
    $skipped = 0;
    $errors = 0;

    foreach ($entityFiles as $file) {
        $filename = basename($file);
        echo "[PROCESSING] $filename\n";

        $content = file_get_contents($file);

        // Extract the data section (section 7)
        // Look for the pattern: -- =========================================
        //                       -- 7. Entity Data
        //                       -- =========================================

        $dataPattern = '/-- =+\s*\n-- 7\. Entity Data\s*\n-- =+\s*\n(.*?)(?=-- =+|$)/s';

        if (preg_match($dataPattern, $content, $matches)) {
            $dataSQL = trim($matches[1]);

            // Skip if no actual INSERT statements
            if (empty($dataSQL) || !preg_match('/INSERT/i', $dataSQL)) {
                echo "  [SKIP] No data found in file\n";
                $skipped++;
                continue;
            }

            // Remove comments and verification SELECT statements
            $lines = explode("\n", $dataSQL);
            $cleanSQL = [];

            foreach ($lines as $line) {
                $trimmed = trim($line);
                // Skip empty lines, comments, and SELECT statements
                if (empty($trimmed) ||
                    substr($trimmed, 0, 2) === '--' ||
                    stripos($trimmed, 'SELECT') === 0) {
                    continue;
                }
                $cleanSQL[] = $line;
            }

            $dataSQL = implode("\n", $cleanSQL);

            if (empty($dataSQL)) {
                echo "  [SKIP] No INSERT statements found\n";
                $skipped++;
                continue;
            }

            // Execute the data import
            try {
                // Execute as a single batch
                $db->exec($dataSQL);
                echo "  [OK] Data imported successfully\n";
                $imported++;
            } catch (PDOException $e) {
                // Check if error is due to UNIQUE constraint (data already exists)
                if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false ||
                    strpos($e->getMessage(), 'already exists') !== false) {
                    echo "  [SKIP] Data already exists (UNIQUE constraint)\n";
                    $skipped++;
                } else {
                    echo "  [ERROR] Failed to import data: " . $e->getMessage() . "\n";
                    $errors++;
                }
            }
        } else {
            echo "  [SKIP] No data section found in file\n";
            $skipped++;
        }
    }

    echo "\n========================================\n";
    echo "Data Import Summary\n";
    echo "========================================\n";
    echo "Imported: $imported\n";
    echo "Skipped: $skipped\n";
    echo "Errors: $errors\n";
    echo "Total Files: " . count($entityFiles) . "\n";

    if ($errors > 0) {
        echo "\nWARNING: Some data imports failed. Check the output above for details.\n";
    } else {
        echo "\nAll entity data imported successfully!\n";
    }

} catch (Exception $e) {
    echo "\n[FATAL ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
