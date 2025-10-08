<?php

require_once __DIR__ . '/../bootstrap.php';

echo "Initializing database...\n";

$db = db();

try {
    // Drop all tables if they exist (for clean initialization)
    $tables = [
        'seller_item_reviews', 'seller_service_schedules', 'seller_item_inventories',
        'seller_item_prices', 'seller_items', 'catalog_item_reviews', 'catalog_item_tags',
        'catalog_item_media', 'catalog_item_features', 'catalog_items', 'catalog_categories',
        'entity_instance_authorizations', 'entity_process_authorizations', 'entity_definitions',
        'employment_contracts', 'job_offers', 'application_interviews', 'interview_stages',
        'application_reviews', 'vacancy_applications', 'organization_vacancy_workstations',
        'organization_vacancies', 'workstations', 'organization_buildings', 'organization_branches',
        'organizations', 'organization_legal_categories', 'popular_organization_positions',
        'popular_organization_designations', 'popular_organization_teams', 'popular_organization_departments',
        'industry_categories', 'person_skills', 'person_education_subjects', 'person_education',
        'popular_skills', 'popular_education_subjects', 'credentials', 'persons',
        'postal_addresses', 'languages', 'countries', 'continents'
    ];

    foreach ($tables as $table) {
        $db->query("DROP TABLE IF EXISTS $table");
    }

    echo "Creating tables...\n";

    // GEOGRAPHY DOMAIN
    $db->query("
        CREATE TABLE continents (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE countries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            continent_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (continent_id) REFERENCES continents(id)
        )
    ");

    $db->query("
        CREATE TABLE languages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            country_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (country_id) REFERENCES countries(id)
        )
    ");

    $db->query("
        CREATE TABLE postal_addresses (
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
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (country_id) REFERENCES countries(id)
        )
    ");

    // PERSON DOMAIN
    $db->query("
        CREATE TABLE persons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            middle_name TEXT,
            last_name TEXT NOT NULL,
            date_of_birth DATE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE credentials (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            person_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (person_id) REFERENCES persons(id)
        )
    ");

    // EDUCATION & SKILL DOMAIN
    $db->query("
        CREATE TABLE popular_education_subjects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE popular_skills (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE person_education (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_id INTEGER NOT NULL,
            institution TEXT NOT NULL,
            start_date DATE,
            complete_date DATE,
            education_level TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (person_id) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE person_education_subjects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_education_id INTEGER NOT NULL,
            subject_id INTEGER NOT NULL,
            marks_type TEXT,
            marks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (person_education_id) REFERENCES person_education(id),
            FOREIGN KEY (subject_id) REFERENCES popular_education_subjects(id)
        )
    ");

    $db->query("
        CREATE TABLE person_skills (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            person_id INTEGER NOT NULL,
            skill_id INTEGER NOT NULL,
            institution TEXT,
            level TEXT,
            start_date DATE,
            complete_date DATE,
            marks_type TEXT,
            marks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (person_id) REFERENCES persons(id),
            FOREIGN KEY (skill_id) REFERENCES popular_skills(id)
        )
    ");

    // ORGANIZATION DOMAIN
    $db->query("
        CREATE TABLE industry_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            parent_category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (parent_category_id) REFERENCES industry_categories(id)
        )
    ");

    $db->query("
        CREATE TABLE popular_organization_departments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE popular_organization_teams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            department_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (department_id) REFERENCES popular_organization_departments(id)
        )
    ");

    $db->query("
        CREATE TABLE popular_organization_designations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE popular_organization_positions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            department_id INTEGER,
            team_id INTEGER,
            designation_id INTEGER NOT NULL,
            minimum_education_level TEXT,
            minimum_subject_id INTEGER,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (department_id) REFERENCES popular_organization_departments(id),
            FOREIGN KEY (team_id) REFERENCES popular_organization_teams(id),
            FOREIGN KEY (designation_id) REFERENCES popular_organization_designations(id),
            FOREIGN KEY (minimum_subject_id) REFERENCES popular_education_subjects(id)
        )
    ");

    $db->query("
        CREATE TABLE organization_legal_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            parent_category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (parent_category_id) REFERENCES organization_legal_categories(id)
        )
    ");

    $db->query("
        CREATE TABLE organizations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            short_name TEXT NOT NULL,
            tag_line TEXT,
            website TEXT,
            subdomain TEXT UNIQUE,
            admin_id INTEGER NOT NULL,
            industry_id INTEGER,
            legal_category_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (admin_id) REFERENCES persons(id),
            FOREIGN KEY (industry_id) REFERENCES industry_categories(id),
            FOREIGN KEY (legal_category_id) REFERENCES organization_legal_categories(id),
            UNIQUE (short_name, legal_category_id)
        )
    ");

    $db->query("
        CREATE TABLE organization_branches (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            code TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_id) REFERENCES organizations(id)
        )
    ");

    $db->query("
        CREATE TABLE organization_buildings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_branch_id INTEGER NOT NULL,
            postal_address_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_branch_id) REFERENCES organization_branches(id),
            FOREIGN KEY (postal_address_id) REFERENCES postal_addresses(id)
        )
    ");

    $db->query("
        CREATE TABLE workstations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_building_id INTEGER NOT NULL,
            floor TEXT,
            room TEXT,
            workstation_number TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_building_id) REFERENCES organization_buildings(id)
        )
    ");

    // HIRING DOMAIN
    $db->query("
        CREATE TABLE organization_vacancies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_id INTEGER NOT NULL,
            popular_position_id INTEGER NOT NULL,
            opening_date DATE NOT NULL,
            closing_date DATE,
            status TEXT DEFAULT 'Open',
            created_by INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_id) REFERENCES organizations(id),
            FOREIGN KEY (popular_position_id) REFERENCES popular_organization_positions(id),
            FOREIGN KEY (created_by) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE organization_vacancy_workstations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_vacancy_id INTEGER NOT NULL,
            organization_workstation_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_vacancy_id) REFERENCES organization_vacancies(id),
            FOREIGN KEY (organization_workstation_id) REFERENCES workstations(id)
        )
    ");

    $db->query("
        CREATE TABLE vacancy_applications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            vacancy_id INTEGER NOT NULL,
            applicant_id INTEGER NOT NULL,
            application_date DATE NOT NULL,
            status TEXT DEFAULT 'Applied',
            resume_url TEXT,
            cover_letter TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (vacancy_id) REFERENCES organization_vacancies(id),
            FOREIGN KEY (applicant_id) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE application_reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            application_id INTEGER NOT NULL,
            reviewed_by INTEGER NOT NULL,
            review_date DATE NOT NULL,
            review_notes TEXT,
            status TEXT DEFAULT 'Pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (application_id) REFERENCES vacancy_applications(id),
            FOREIGN KEY (reviewed_by) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE interview_stages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            order_number INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_id) REFERENCES organizations(id)
        )
    ");

    $db->query("
        CREATE TABLE application_interviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            application_id INTEGER NOT NULL,
            stage_id INTEGER NOT NULL,
            interviewer_id INTEGER NOT NULL,
            scheduled_date DATETIME,
            actual_date DATETIME,
            feedback_notes TEXT,
            rating INTEGER,
            status TEXT DEFAULT 'Scheduled',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (application_id) REFERENCES vacancy_applications(id),
            FOREIGN KEY (stage_id) REFERENCES interview_stages(id),
            FOREIGN KEY (interviewer_id) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE job_offers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            application_id INTEGER NOT NULL,
            offered_by INTEGER NOT NULL,
            offer_date DATE NOT NULL,
            position_title TEXT NOT NULL,
            salary_offered REAL,
            joining_date DATE,
            status TEXT DEFAULT 'Offered',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (application_id) REFERENCES vacancy_applications(id),
            FOREIGN KEY (offered_by) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE employment_contracts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            job_offer_id INTEGER NOT NULL,
            organization_id INTEGER NOT NULL,
            employee_id INTEGER NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE,
            contract_terms TEXT,
            status TEXT DEFAULT 'Active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (job_offer_id) REFERENCES job_offers(id),
            FOREIGN KEY (organization_id) REFERENCES organizations(id),
            FOREIGN KEY (employee_id) REFERENCES persons(id)
        )
    ");

    // PROCESS AUTHORIZATION DOMAIN
    $db->query("
        CREATE TABLE entity_definitions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )
    ");

    $db->query("
        CREATE TABLE entity_process_authorizations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            entity_id INTEGER NOT NULL,
            action TEXT NOT NULL,
            popular_position_id INTEGER NOT NULL,
            remarks TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (entity_id) REFERENCES entity_definitions(id),
            FOREIGN KEY (popular_position_id) REFERENCES popular_organization_positions(id)
        )
    ");

    $db->query("
        CREATE TABLE entity_instance_authorizations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            entity_id INTEGER NOT NULL,
            entity_record_id INTEGER NOT NULL,
            action TEXT NOT NULL,
            assigned_position_id INTEGER,
            assigned_person_id INTEGER,
            valid_from DATE,
            valid_to DATE,
            status TEXT DEFAULT 'Active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (entity_id) REFERENCES entity_definitions(id),
            FOREIGN KEY (assigned_position_id) REFERENCES popular_organization_positions(id),
            FOREIGN KEY (assigned_person_id) REFERENCES persons(id)
        )
    ");

    // CATALOG DOMAIN
    $db->query("
        CREATE TABLE catalog_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            parent_category_id INTEGER,
            is_active INTEGER DEFAULT 1,
            managed_by_system INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (parent_category_id) REFERENCES catalog_categories(id)
        )
    ");

    $db->query("
        CREATE TABLE catalog_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            name TEXT NOT NULL,
            brand_name TEXT,
            short_description TEXT,
            detailed_description TEXT,
            unit_of_measure TEXT,
            thumbnail_url TEXT,
            status TEXT DEFAULT 'Active',
            created_by INTEGER NOT NULL,
            created_date DATE DEFAULT (date('now')),
            updated_date DATE DEFAULT (date('now')),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (category_id) REFERENCES catalog_categories(id),
            FOREIGN KEY (created_by) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE catalog_item_features (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            item_id INTEGER NOT NULL,
            feature_name TEXT NOT NULL,
            feature_value TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (item_id) REFERENCES catalog_items(id)
        )
    ");

    $db->query("
        CREATE TABLE catalog_item_media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            item_id INTEGER NOT NULL,
            media_type TEXT NOT NULL,
            media_url TEXT NOT NULL,
            caption TEXT,
            is_primary INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (item_id) REFERENCES catalog_items(id)
        )
    ");

    $db->query("
        CREATE TABLE catalog_item_tags (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            item_id INTEGER NOT NULL,
            tag TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (item_id) REFERENCES catalog_items(id)
        )
    ");

    $db->query("
        CREATE TABLE catalog_item_reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            item_id INTEGER NOT NULL,
            reviewed_by INTEGER NOT NULL,
            rating INTEGER NOT NULL,
            review_text TEXT,
            review_date DATE DEFAULT (date('now')),
            status TEXT DEFAULT 'Visible',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (item_id) REFERENCES catalog_items(id),
            FOREIGN KEY (reviewed_by) REFERENCES persons(id)
        )
    ");

    // SELLER / LESSOR DOMAIN
    $db->query("
        CREATE TABLE seller_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            organization_id INTEGER NOT NULL,
            catalog_item_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            available_from_building_id INTEGER NOT NULL,
            availability_status TEXT DEFAULT 'Available',
            remarks TEXT,
            created_by INTEGER NOT NULL,
            created_date DATE DEFAULT (date('now')),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (organization_id) REFERENCES organizations(id),
            FOREIGN KEY (catalog_item_id) REFERENCES catalog_items(id),
            FOREIGN KEY (available_from_building_id) REFERENCES organization_buildings(id),
            FOREIGN KEY (created_by) REFERENCES persons(id)
        )
    ");

    $db->query("
        CREATE TABLE seller_item_prices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seller_item_id INTEGER NOT NULL,
            currency_code TEXT DEFAULT 'USD',
            base_price REAL NOT NULL,
            discount_percent REAL DEFAULT 0,
            final_price REAL NOT NULL,
            effective_from DATE DEFAULT (date('now')),
            effective_to DATE,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (seller_item_id) REFERENCES seller_items(id)
        )
    ");

    $db->query("
        CREATE TABLE seller_item_inventories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seller_item_id INTEGER NOT NULL,
            available_quantity INTEGER NOT NULL,
            reorder_level INTEGER,
            last_restock_date DATE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (seller_item_id) REFERENCES seller_items(id)
        )
    ");

    $db->query("
        CREATE TABLE seller_service_schedules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seller_item_id INTEGER NOT NULL,
            available_from_time TIME,
            available_to_time TIME,
            days_available TEXT,
            duration_minutes INTEGER,
            requires_appointment INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (seller_item_id) REFERENCES seller_items(id)
        )
    ");

    $db->query("
        CREATE TABLE seller_item_reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            seller_item_id INTEGER NOT NULL,
            reviewed_by INTEGER NOT NULL,
            rating INTEGER NOT NULL,
            review_text TEXT,
            review_date DATE DEFAULT (date('now')),
            status TEXT DEFAULT 'Visible',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (seller_item_id) REFERENCES seller_items(id),
            FOREIGN KEY (reviewed_by) REFERENCES persons(id)
        )
    ");

    echo "Database initialization completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
