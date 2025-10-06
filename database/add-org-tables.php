<?php
/**
 * Add Organization Tables to Database
 */

require_once __DIR__ . '/../bootstrap.php';

use App\Database;

echo "Adding Organization Domain Tables...\n\n";

try {
    $db = Database::getConnection();
    $db->exec('PRAGMA foreign_keys = ON');

    echo "- Creating industry_category table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS industry_category (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            parent_category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (parent_category_id) REFERENCES industry_category(id)
        )
    ");

    echo "- Creating popular_organization_department table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS popular_organization_department (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1
        )
    ");

    echo "- Creating popular_organization_team table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS popular_organization_team (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            department_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (department_id) REFERENCES popular_organization_department(id)
        )
    ");

    echo "- Creating popular_organization_designation table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS popular_organization_designation (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1
        )
    ");

    echo "- Creating organization_legal_category table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS organization_legal_category (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            parent_category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (parent_category_id) REFERENCES organization_legal_category(id)
        )
    ");

    echo "- Creating organization table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS organization (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            short_name TEXT NOT NULL,
            tag_line TEXT,
            website TEXT,
            subdomain TEXT UNIQUE,
            admin_id INTEGER,
            industry_id INTEGER,
            legal_category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (admin_id) REFERENCES person(id),
            FOREIGN KEY (industry_id) REFERENCES industry_category(id),
            FOREIGN KEY (legal_category_id) REFERENCES organization_legal_category(id)
        )
    ");

    echo "- Creating organization_branch table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS organization_branch (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            code TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (organization_id) REFERENCES organization(id)
        )
    ");

    echo "- Creating organization_building table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS organization_building (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_branch_id INTEGER NOT NULL,
            postal_address_id INTEGER,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (organization_branch_id) REFERENCES organization_branch(id),
            FOREIGN KEY (postal_address_id) REFERENCES postal_address(id)
        )
    ");

    echo "- Creating workstation table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS workstation (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_building_id INTEGER NOT NULL,
            floor TEXT,
            room TEXT,
            workstation_number TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (organization_building_id) REFERENCES organization_building(id)
        )
    ");

    echo "\n✅ Organization tables added successfully!\n";

    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE '%organization%' OR name LIKE '%industry%' OR name = 'workstation' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    echo "\nNew tables:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }

} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
