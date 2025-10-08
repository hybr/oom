-- Migration: Add Missing Database Tables
-- Created: 2025-10-07
-- Description: Creates all missing entity tables for the OOM system
--
-- This migration creates 23 new tables:
-- 1. popular_organization_position
-- 2. organization_vacancy
-- 3. organization_vacancy_workstation
-- 4. vacancy_application
-- 5. application_review
-- 6. interview_stage
-- 7. application_interview
-- 8. job_offer
-- 9. employment_contract
-- 10. entity_definition
-- 11. entity_process_authorization
-- 12. entity_instance_authorization
-- 13. catalog_item
-- 14. catalog_category
-- 15. catalog_item_media
-- 16. catalog_item_feature
-- 17. catalog_item_review
-- 18. seller_item_inventory
-- 19. seller_item
-- 20. catalog_item_tag
-- 21. seller_item_price
-- 22. seller_item_review
-- 23. seller_service_schedule

-- ===========================================================================
-- RECRUITMENT & EMPLOYMENT TABLES
-- ===========================================================================

-- Table: popular_organization_position
-- Standardized global position templates usable across organizations
CREATE TABLE IF NOT EXISTS popular_organization_position (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    department_id INTEGER,
    team_id INTEGER,
    designation_id INTEGER,
    minimum_education_level TEXT,
    minimum_subject_id INTEGER,
    description TEXT,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (department_id) REFERENCES popular_organization_department(id),
    FOREIGN KEY (team_id) REFERENCES popular_organization_team(id),
    FOREIGN KEY (designation_id) REFERENCES popular_organization_designation(id),
    FOREIGN KEY (minimum_subject_id) REFERENCES popular_education_subject(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: organization_vacancy
-- Represents job openings within an organization
CREATE TABLE IF NOT EXISTS organization_vacancy (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    organization_id INTEGER NOT NULL,
    popular_position_id INTEGER NOT NULL,
    opening_date TEXT NOT NULL,
    closing_date TEXT,
    status TEXT NOT NULL, -- open, closed, filled, cancelled
    created_by INTEGER,
    created_at TEXT NOT NULL,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (organization_id) REFERENCES organization(id),
    FOREIGN KEY (popular_position_id) REFERENCES popular_organization_position(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: organization_vacancy_workstation
-- Links vacancies to specific workstations where the position will be located
CREATE TABLE IF NOT EXISTS organization_vacancy_workstation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    organization_vacancy_id INTEGER NOT NULL,
    organization_workstation_id INTEGER NOT NULL,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (organization_vacancy_id) REFERENCES organization_vacancy(id),
    FOREIGN KEY (organization_workstation_id) REFERENCES workstation(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: vacancy_application
-- Represents a job application submitted by a candidate for a vacancy
CREATE TABLE IF NOT EXISTS vacancy_application (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vacancy_id INTEGER NOT NULL,
    applicant_id INTEGER NOT NULL,
    application_date TEXT NOT NULL,
    status TEXT NOT NULL, -- pending, under_review, shortlisted, interviewed, rejected, withdrawn, offered, accepted
    resume_url TEXT,
    cover_letter TEXT,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (vacancy_id) REFERENCES organization_vacancy(id),
    FOREIGN KEY (applicant_id) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: application_review
-- Represents a review of a job application by hiring personnel
CREATE TABLE IF NOT EXISTS application_review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    application_id INTEGER NOT NULL,
    reviewed_by INTEGER NOT NULL,
    review_date TEXT NOT NULL,
    review_notes TEXT,
    status TEXT NOT NULL, -- approved, rejected, needs_more_info
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (application_id) REFERENCES vacancy_application(id),
    FOREIGN KEY (reviewed_by) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: interview_stage
-- Represents different stages in an organization's interview process
CREATE TABLE IF NOT EXISTS interview_stage (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    organization_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    order_number INTEGER NOT NULL,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (organization_id) REFERENCES organization(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: application_interview
-- Represents an interview scheduled for a job application
CREATE TABLE IF NOT EXISTS application_interview (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    application_id INTEGER NOT NULL,
    stage_id INTEGER NOT NULL,
    interviewer_id INTEGER NOT NULL,
    scheduled_date TEXT NOT NULL,
    actual_date TEXT,
    feedback_notes TEXT,
    rating REAL,
    status TEXT NOT NULL, -- scheduled, completed, cancelled, rescheduled
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (application_id) REFERENCES vacancy_application(id),
    FOREIGN KEY (stage_id) REFERENCES interview_stage(id),
    FOREIGN KEY (interviewer_id) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: job_offer
-- Represents a job offer extended to a successful applicant
CREATE TABLE IF NOT EXISTS job_offer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    application_id INTEGER NOT NULL,
    offered_by INTEGER NOT NULL,
    offer_date TEXT NOT NULL,
    position_title TEXT NOT NULL,
    salary_offered REAL,
    joining_date TEXT,
    status TEXT NOT NULL, -- pending, accepted, declined, expired, withdrawn
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (application_id) REFERENCES vacancy_application(id),
    FOREIGN KEY (offered_by) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: employment_contract
-- Represents the formal employment contract between an organization and an employee
CREATE TABLE IF NOT EXISTS employment_contract (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    job_offer_id INTEGER NOT NULL,
    organization_id INTEGER NOT NULL,
    employee_id INTEGER NOT NULL,
    start_date TEXT NOT NULL,
    end_date TEXT,
    contract_terms TEXT,
    status TEXT NOT NULL, -- draft, active, completed, terminated, expired
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (job_offer_id) REFERENCES job_offer(id),
    FOREIGN KEY (organization_id) REFERENCES organization(id),
    FOREIGN KEY (employee_id) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- ===========================================================================
-- AUTHORIZATION & ACCESS CONTROL TABLES
-- ===========================================================================

-- Table: entity_definition
-- Defines entities in the system for authorization and access control
CREATE TABLE IF NOT EXISTS entity_definition (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: entity_process_authorization
-- Defines which positions can perform which actions on entities (process-level authorization)
CREATE TABLE IF NOT EXISTS entity_process_authorization (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    entity_id INTEGER NOT NULL,
    action TEXT NOT NULL,
    popular_position_id INTEGER NOT NULL,
    remarks TEXT,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY (popular_position_id) REFERENCES popular_organization_position(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: entity_instance_authorization
-- Defines instance-specific authorizations for particular records (instance-level authorization)
CREATE TABLE IF NOT EXISTS entity_instance_authorization (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    entity_id INTEGER NOT NULL,
    entity_record_id INTEGER NOT NULL,
    action TEXT NOT NULL,
    assigned_position_id INTEGER,
    assigned_person_id INTEGER,
    valid_from TEXT,
    valid_to TEXT,
    status TEXT NOT NULL, -- active, revoked, expired
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY (assigned_position_id) REFERENCES popular_organization_position(id),
    FOREIGN KEY (assigned_person_id) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- ===========================================================================
-- CATALOG & MARKETPLACE TABLES
-- ===========================================================================

-- Table: catalog_category
-- Hierarchical catalog categories for organizing items
CREATE TABLE IF NOT EXISTS catalog_category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    parent_category_id INTEGER,
    is_active INTEGER DEFAULT 1,
    managed_by_system INTEGER DEFAULT 1,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (parent_category_id) REFERENCES catalog_category(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: catalog_item
-- Centralized catalog of all goods and services (V4L-managed)
CREATE TABLE IF NOT EXISTS catalog_item (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL,
    type TEXT NOT NULL, -- Good, Service, Subscription, Rental
    name TEXT NOT NULL,
    brand_name TEXT,
    short_description TEXT NOT NULL,
    detailed_description TEXT,
    unit_of_measure TEXT NOT NULL,
    thumbnail_url TEXT,
    status TEXT DEFAULT 'Active', -- Active, Inactive
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES catalog_category(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: catalog_item_media
-- Images, videos, and documents for catalog items
CREATE TABLE IF NOT EXISTS catalog_item_media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL,
    media_type TEXT NOT NULL, -- Image, Video, PDF, SpecSheet, 3D
    media_url TEXT NOT NULL,
    caption TEXT,
    is_primary INTEGER DEFAULT 0,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (item_id) REFERENCES catalog_item(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: catalog_item_feature
-- Specifications and features for catalog items
CREATE TABLE IF NOT EXISTS catalog_item_feature (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL,
    feature_name TEXT NOT NULL,
    feature_value TEXT NOT NULL,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (item_id) REFERENCES catalog_item(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: catalog_item_review
-- Reviews for catalog items (product quality, not seller performance)
CREATE TABLE IF NOT EXISTS catalog_item_review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL,
    reviewed_by INTEGER NOT NULL,
    rating INTEGER NOT NULL, -- 1-5
    review_text TEXT,
    review_date TEXT,
    status TEXT DEFAULT 'Visible', -- Visible, Hidden
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (item_id) REFERENCES catalog_item(id),
    FOREIGN KEY (reviewed_by) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: catalog_item_tag
-- Tags for catalog items (local, eco-friendly, handmade, etc.)
CREATE TABLE IF NOT EXISTS catalog_item_tag (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL,
    tag TEXT NOT NULL,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (item_id) REFERENCES catalog_item(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: seller_item
-- Represents an organization's offering of a catalog item
CREATE TABLE IF NOT EXISTS seller_item (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    organization_id INTEGER NOT NULL,
    catalog_item_id INTEGER NOT NULL,
    type TEXT NOT NULL, -- Sell, Rent, Lease, Service
    available_from_building_id INTEGER NOT NULL,
    availability_status TEXT DEFAULT 'Available', -- Available, Unavailable, Seasonal
    remarks TEXT,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (organization_id) REFERENCES organization(id),
    FOREIGN KEY (catalog_item_id) REFERENCES catalog_item(id),
    FOREIGN KEY (available_from_building_id) REFERENCES organization_building(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: seller_item_inventory
-- Inventory management for physical goods
CREATE TABLE IF NOT EXISTS seller_item_inventory (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller_item_id INTEGER NOT NULL,
    available_quantity INTEGER DEFAULT 0,
    reorder_level INTEGER DEFAULT 0,
    last_restock_date TEXT,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (seller_item_id) REFERENCES seller_item(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: seller_item_price
-- Pricing information for seller items with date ranges
CREATE TABLE IF NOT EXISTS seller_item_price (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller_item_id INTEGER NOT NULL,
    currency_code TEXT NOT NULL,
    base_price REAL NOT NULL,
    discount_percent REAL DEFAULT 0.0,
    final_price REAL,
    effective_from TEXT NOT NULL,
    effective_to TEXT,
    is_active INTEGER DEFAULT 1,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (seller_item_id) REFERENCES seller_item(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: seller_item_review
-- Reviews for seller performance (distinct from catalog item quality)
CREATE TABLE IF NOT EXISTS seller_item_review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller_item_id INTEGER NOT NULL,
    reviewed_by INTEGER NOT NULL,
    rating INTEGER NOT NULL, -- 1-5
    review_text TEXT,
    review_date TEXT,
    status TEXT DEFAULT 'Visible', -- Visible, Hidden
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (seller_item_id) REFERENCES seller_item(id),
    FOREIGN KEY (reviewed_by) REFERENCES person(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- Table: seller_service_schedule
-- Scheduling information for services and rentals
CREATE TABLE IF NOT EXISTS seller_service_schedule (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller_item_id INTEGER NOT NULL,
    available_from_time TEXT NOT NULL,
    available_to_time TEXT NOT NULL,
    days_available TEXT, -- JSON: ["Mon", "Tue", "Wed", ...]
    duration_minutes INTEGER NOT NULL,
    requires_appointment INTEGER DEFAULT 0,
    created_at TEXT NOT NULL,
    created_by INTEGER,
    updated_at TEXT NOT NULL,
    updated_by INTEGER,
    deleted_at TEXT,
    version INTEGER DEFAULT 1,
    FOREIGN KEY (seller_item_id) REFERENCES seller_item(id),
    FOREIGN KEY (created_by) REFERENCES person(id),
    FOREIGN KEY (updated_by) REFERENCES person(id)
);

-- ===========================================================================
-- INDEXES FOR PERFORMANCE
-- ===========================================================================

-- Indexes for popular_organization_position
CREATE INDEX IF NOT EXISTS idx_pop_position_department ON popular_organization_position(department_id);
CREATE INDEX IF NOT EXISTS idx_pop_position_team ON popular_organization_position(team_id);
CREATE INDEX IF NOT EXISTS idx_pop_position_designation ON popular_organization_position(designation_id);
CREATE INDEX IF NOT EXISTS idx_pop_position_name ON popular_organization_position(name);

-- Indexes for organization_vacancy
CREATE INDEX IF NOT EXISTS idx_vacancy_org ON organization_vacancy(organization_id);
CREATE INDEX IF NOT EXISTS idx_vacancy_position ON organization_vacancy(popular_position_id);
CREATE INDEX IF NOT EXISTS idx_vacancy_status ON organization_vacancy(status);
CREATE INDEX IF NOT EXISTS idx_vacancy_dates ON organization_vacancy(opening_date, closing_date);

-- Indexes for vacancy_application
CREATE INDEX IF NOT EXISTS idx_application_vacancy ON vacancy_application(vacancy_id);
CREATE INDEX IF NOT EXISTS idx_application_applicant ON vacancy_application(applicant_id);
CREATE INDEX IF NOT EXISTS idx_application_status ON vacancy_application(status);

-- Indexes for application_review
CREATE INDEX IF NOT EXISTS idx_review_application ON application_review(application_id);
CREATE INDEX IF NOT EXISTS idx_review_reviewer ON application_review(reviewed_by);

-- Indexes for interview_stage
CREATE INDEX IF NOT EXISTS idx_interview_stage_org ON interview_stage(organization_id);
CREATE INDEX IF NOT EXISTS idx_interview_stage_order ON interview_stage(organization_id, order_number);

-- Indexes for application_interview
CREATE INDEX IF NOT EXISTS idx_interview_application ON application_interview(application_id);
CREATE INDEX IF NOT EXISTS idx_interview_stage ON application_interview(stage_id);
CREATE INDEX IF NOT EXISTS idx_interview_interviewer ON application_interview(interviewer_id);
CREATE INDEX IF NOT EXISTS idx_interview_scheduled ON application_interview(scheduled_date);

-- Indexes for job_offer
CREATE INDEX IF NOT EXISTS idx_offer_application ON job_offer(application_id);
CREATE INDEX IF NOT EXISTS idx_offer_status ON job_offer(status);

-- Indexes for employment_contract
CREATE INDEX IF NOT EXISTS idx_contract_offer ON employment_contract(job_offer_id);
CREATE INDEX IF NOT EXISTS idx_contract_org ON employment_contract(organization_id);
CREATE INDEX IF NOT EXISTS idx_contract_employee ON employment_contract(employee_id);
CREATE INDEX IF NOT EXISTS idx_contract_status ON employment_contract(status);

-- Indexes for entity_definition
CREATE INDEX IF NOT EXISTS idx_entity_def_name ON entity_definition(name);

-- Indexes for entity_process_authorization
CREATE INDEX IF NOT EXISTS idx_proc_auth_entity ON entity_process_authorization(entity_id);
CREATE INDEX IF NOT EXISTS idx_proc_auth_position ON entity_process_authorization(popular_position_id);
CREATE INDEX IF NOT EXISTS idx_proc_auth_action ON entity_process_authorization(entity_id, action);

-- Indexes for entity_instance_authorization
CREATE INDEX IF NOT EXISTS idx_inst_auth_entity ON entity_instance_authorization(entity_id);
CREATE INDEX IF NOT EXISTS idx_inst_auth_record ON entity_instance_authorization(entity_id, entity_record_id);
CREATE INDEX IF NOT EXISTS idx_inst_auth_person ON entity_instance_authorization(assigned_person_id);
CREATE INDEX IF NOT EXISTS idx_inst_auth_position ON entity_instance_authorization(assigned_position_id);
CREATE INDEX IF NOT EXISTS idx_inst_auth_status ON entity_instance_authorization(status);

-- Indexes for catalog_category
CREATE INDEX IF NOT EXISTS idx_catalog_cat_parent ON catalog_category(parent_category_id);
CREATE INDEX IF NOT EXISTS idx_catalog_cat_active ON catalog_category(is_active);
CREATE INDEX IF NOT EXISTS idx_catalog_cat_name ON catalog_category(name);

-- Indexes for catalog_item
CREATE INDEX IF NOT EXISTS idx_catalog_item_category ON catalog_item(category_id);
CREATE INDEX IF NOT EXISTS idx_catalog_item_type ON catalog_item(type);
CREATE INDEX IF NOT EXISTS idx_catalog_item_status ON catalog_item(status);
CREATE INDEX IF NOT EXISTS idx_catalog_item_name ON catalog_item(name);

-- Indexes for catalog_item_media
CREATE INDEX IF NOT EXISTS idx_catalog_media_item ON catalog_item_media(item_id);
CREATE INDEX IF NOT EXISTS idx_catalog_media_type ON catalog_item_media(media_type);
CREATE INDEX IF NOT EXISTS idx_catalog_media_primary ON catalog_item_media(item_id, is_primary);

-- Indexes for catalog_item_feature
CREATE INDEX IF NOT EXISTS idx_catalog_feature_item ON catalog_item_feature(item_id);
CREATE INDEX IF NOT EXISTS idx_catalog_feature_name ON catalog_item_feature(feature_name);

-- Indexes for catalog_item_review
CREATE INDEX IF NOT EXISTS idx_catalog_review_item ON catalog_item_review(item_id);
CREATE INDEX IF NOT EXISTS idx_catalog_review_reviewer ON catalog_item_review(reviewed_by);
CREATE INDEX IF NOT EXISTS idx_catalog_review_status ON catalog_item_review(status);

-- Indexes for catalog_item_tag
CREATE INDEX IF NOT EXISTS idx_catalog_tag_item ON catalog_item_tag(item_id);
CREATE INDEX IF NOT EXISTS idx_catalog_tag_tag ON catalog_item_tag(tag);

-- Indexes for seller_item
CREATE INDEX IF NOT EXISTS idx_seller_item_org ON seller_item(organization_id);
CREATE INDEX IF NOT EXISTS idx_seller_item_catalog ON seller_item(catalog_item_id);
CREATE INDEX IF NOT EXISTS idx_seller_item_type ON seller_item(type);
CREATE INDEX IF NOT EXISTS idx_seller_item_building ON seller_item(available_from_building_id);
CREATE INDEX IF NOT EXISTS idx_seller_item_status ON seller_item(availability_status);

-- Indexes for seller_item_inventory
CREATE INDEX IF NOT EXISTS idx_inventory_seller_item ON seller_item_inventory(seller_item_id);
CREATE INDEX IF NOT EXISTS idx_inventory_quantity ON seller_item_inventory(available_quantity);

-- Indexes for seller_item_price
CREATE INDEX IF NOT EXISTS idx_price_seller_item ON seller_item_price(seller_item_id);
CREATE INDEX IF NOT EXISTS idx_price_active ON seller_item_price(is_active);
CREATE INDEX IF NOT EXISTS idx_price_effective ON seller_item_price(effective_from, effective_to);

-- Indexes for seller_item_review
CREATE INDEX IF NOT EXISTS idx_seller_review_item ON seller_item_review(seller_item_id);
CREATE INDEX IF NOT EXISTS idx_seller_review_reviewer ON seller_item_review(reviewed_by);
CREATE INDEX IF NOT EXISTS idx_seller_review_status ON seller_item_review(status);

-- Indexes for seller_service_schedule
CREATE INDEX IF NOT EXISTS idx_schedule_seller_item ON seller_service_schedule(seller_item_id);

-- ===========================================================================
-- END OF MIGRATION
-- ===========================================================================
