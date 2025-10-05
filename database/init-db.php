<?php
/**
 * Database Initialization Script
 * Creates all required tables for the V4L application
 */

require_once __DIR__ . '/../bootstrap.php';

use App\Database;

echo "Initializing V4L Database...\n\n";

try {
    $db = Database::getConnection();

    // Enable foreign keys
    $db->exec('PRAGMA foreign_keys = ON');

    echo "Creating tables...\n";

    // Geography Domain
    echo "- Creating continents table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS continent (
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

    echo "- Creating countries table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS country (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            continent_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (continent_id) REFERENCES continent(id)
        )
    ");

    echo "- Creating languages table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS language (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            country_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (country_id) REFERENCES country(id)
        )
    ");

    echo "- Creating postal_address table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS postal_address (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_street TEXT,
            second_street TEXT,
            area TEXT,
            city TEXT NOT NULL,
            state TEXT,
            pin TEXT,
            latitude REAL,
            longitude REAL,
            country_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (country_id) REFERENCES country(id)
        )
    ");

    // Person Domain
    echo "- Creating person table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS person (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            middle_name TEXT,
            last_name TEXT NOT NULL,
            date_of_birth DATE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1
        )
    ");

    echo "- Creating credential table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS credential (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            person_id INTEGER NOT NULL,
            reset_token TEXT,
            reset_token_expires DATETIME,
            remember_token TEXT,
            failed_login_attempts INTEGER DEFAULT 0,
            locked_until DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (person_id) REFERENCES person(id)
        )
    ");

    // Education & Skill Domain
    echo "- Creating popular_education_subject table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS popular_education_subject (
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

    echo "- Creating popular_skill table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS popular_skill (
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

    echo "- Creating person_education table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS person_education (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_id INTEGER NOT NULL,
            institution TEXT NOT NULL,
            start_date DATE,
            complete_date DATE,
            education_level TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (person_id) REFERENCES person(id)
        )
    ");

    echo "- Creating person_education_subject table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS person_education_subject (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_education_id INTEGER NOT NULL,
            subject_id INTEGER NOT NULL,
            marks_type TEXT,
            marks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (person_education_id) REFERENCES person_education(id),
            FOREIGN KEY (subject_id) REFERENCES popular_education_subject(id)
        )
    ");

    echo "- Creating person_skill table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS person_skill (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_id INTEGER NOT NULL,
            subject_id INTEGER NOT NULL,
            institution TEXT,
            level TEXT,
            start_date DATE,
            complete_date DATE,
            marks_type TEXT,
            marks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_by INTEGER,
            deleted_at DATETIME,
            version INTEGER DEFAULT 1,
            FOREIGN KEY (person_id) REFERENCES person(id),
            FOREIGN KEY (subject_id) REFERENCES popular_skill(id)
        )
    ");

    // Audit Log
    echo "- Creating audit_log table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS audit_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            entity_type TEXT NOT NULL,
            entity_id INTEGER NOT NULL,
            action TEXT NOT NULL,
            user_id INTEGER,
            changes TEXT,
            ip_address TEXT,
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "\n✅ Database initialized successfully!\n";
    echo "\nTables created:\n";

    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "  - $table\n";
    }

} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
