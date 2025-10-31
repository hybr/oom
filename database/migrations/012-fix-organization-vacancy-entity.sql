-- ============================================
-- FIX: Create ORGANIZATION_VACANCY Entity
-- ============================================
-- The original metadata file 010-hiring_domain.sql had a UUID conflict
-- where ORGANIZATION_VACANCY tried to use the same ID as ORGANIZATION_ADMIN
-- This migration creates ORGANIZATION_VACANCY with a new unique ID

-- Generate a new UUID for ORGANIZATION_VACANCY entity
-- New ID: 4c07f6b1-7208-4b72-826a-a9c65195bf0f

-- =========================================
-- 1. Create ORGANIZATION_VACANCY Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '4c07f6b1-7208-4b72-826a-a9c65195bf0f',
    'ORGANIZATION_VACANCY',
    'Organization Vacancy',
    'Job vacancies or open positions within an organization',
    'HIRING',
    'organization_vacancy',
    1
);

-- =========================================
-- 2. Create organization_vacancy table
-- =========================================
CREATE TABLE IF NOT EXISTS organization_vacancy (
    id TEXT PRIMARY KEY,
    organization_id TEXT NOT NULL,
    popular_position_id TEXT NOT NULL,
    created_by TEXT NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    requirements TEXT,
    responsibilities TEXT,
    number_of_openings INTEGER NOT NULL DEFAULT 1,
    opening_date DATE NOT NULL,
    closing_date DATE,
    min_salary REAL,
    max_salary REAL,
    employment_type TEXT,
    status TEXT NOT NULL,
    is_urgent BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT (datetime('now')),
    updated_at DATETIME DEFAULT (datetime('now')),
    deleted_at DATETIME,
    FOREIGN KEY (organization_id) REFERENCES organization(id),
    FOREIGN KEY (popular_position_id) REFERENCES popular_organization_position(id),
    FOREIGN KEY (created_by) REFERENCES person(id)
);

-- =========================================
-- 3. Create ORGANIZATION_VACANCY Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('1b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','4c07f6b1-7208-4b72-826a-a9c65195bf0f','organization_id','Organization ID','text',1,0,0,NULL,'Reference to ORGANIZATION entity',1),
('1c2d3e4f-5a6b-7c8d-9e0f-1a2b3c4d5e6f','4c07f6b1-7208-4b72-826a-a9c65195bf0f','popular_position_id','Position ID','text',1,0,0,NULL,'Reference to POPULAR_ORGANIZATION_POSITION entity',2),
('1d2e3f4a-5b6c-7d8e-9f0a-1b2c3d4e5f6a','4c07f6b1-7208-4b72-826a-a9c65195bf0f','created_by','Created By','text',1,0,0,NULL,'Reference to PERSON who created this vacancy',3),

-- Core Fields
('1e2f3a4b-5c6d-7e8f-9a0b-1c2d3e4f5a6b','4c07f6b1-7208-4b72-826a-a9c65195bf0f','title','Vacancy Title','text',1,1,0,NULL,'Title of the vacancy',4),
('1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c','4c07f6b1-7208-4b72-826a-a9c65195bf0f','description','Description','text',0,0,0,NULL,'Detailed description of the vacancy',5),
('1a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d','4c07f6b1-7208-4b72-826a-a9c65195bf0f','requirements','Requirements','text',0,0,0,NULL,'Required qualifications and skills',6),
('1b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e','4c07f6b1-7208-4b72-826a-a9c65195bf0f','responsibilities','Responsibilities','text',0,0,0,NULL,'Key responsibilities of the position',7),
('1c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f','4c07f6b1-7208-4b72-826a-a9c65195bf0f','number_of_openings','Number of Openings','integer',1,0,0,NULL,'Number of positions available',8),

-- Dates
('1d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a','4c07f6b1-7208-4b72-826a-a9c65195bf0f','opening_date','Opening Date','date',1,0,0,NULL,'Date when vacancy was opened',9),
('1e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b','4c07f6b1-7208-4b72-826a-a9c65195bf0f','closing_date','Closing Date','date',0,0,0,NULL,'Date when vacancy will close',10),

-- Compensation
('1f3a4b5c-6d7e-8f9a-0b1c-2d3e4f5a6b7c','4c07f6b1-7208-4b72-826a-a9c65195bf0f','min_salary','Minimum Salary','number',0,0,0,NULL,'Minimum salary offered',11),
('1a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d','4c07f6b1-7208-4b72-826a-a9c65195bf0f','max_salary','Maximum Salary','number',0,0,0,NULL,'Maximum salary offered',12),
('1b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e','4c07f6b1-7208-4b72-826a-a9c65195bf0f','employment_type','Employment Type','enum_strings',0,0,0,'["Full-time","Part-time","Contract","Temporary","Internship"]','Type of employment',13),

-- Status
('1c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f','4c07f6b1-7208-4b72-826a-a9c65195bf0f','status','Status','enum_objects',1,0,0,'{"render_as":"select","options":[{"value":"draft","label":"Draft"},{"value":"open","label":"Open"},{"value":"closed","label":"Closed"},{"value":"cancelled","label":"Cancelled"},{"value":"filled","label":"Filled"}]}','Current status of the vacancy',14),
('1d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a','4c07f6b1-7208-4b72-826a-a9c65195bf0f','is_urgent','Is Urgent','boolean',0,0,0,NULL,'Whether this is an urgent vacancy',15);

-- =========================================
-- 4. Update process_graph to use correct entity
-- =========================================
UPDATE process_graph
SET entity_id = '4c07f6b1-7208-4b72-826a-a9c65195bf0f'
WHERE id = 'VC000000-0000-4000-8000-000000000001';

-- =========================================
-- 5. Create relationships
-- =========================================

-- Relationship: ORGANIZATION_VACANCY -> ORGANIZATION
INSERT OR IGNORE INTO entity_relationship (
    id,
    from_entity_id,
    to_entity_id,
    fk_field,
    relation_name,
    relation_type
)
VALUES (
    '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
    '4c07f6b1-7208-4b72-826a-a9c65195bf0f',
    (SELECT id FROM entity_definition WHERE code = 'ORGANIZATION'),
    'organization_id',
    'Vacancy Organization',
    'ManyToOne'
);

-- Relationship: ORGANIZATION_VACANCY -> POPULAR_ORGANIZATION_POSITION
INSERT OR IGNORE INTO entity_relationship (
    id,
    from_entity_id,
    to_entity_id,
    fk_field,
    relation_name,
    relation_type
)
VALUES (
    '2b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e',
    '4c07f6b1-7208-4b72-826a-a9c65195bf0f',
    (SELECT id FROM entity_definition WHERE code = 'POPULAR_ORGANIZATION_POSITION'),
    'popular_position_id',
    'Position',
    'ManyToOne'
);

-- Relationship: ORGANIZATION_VACANCY -> PERSON (created_by)
INSERT OR IGNORE INTO entity_relationship (
    id,
    from_entity_id,
    to_entity_id,
    fk_field,
    relation_name,
    relation_type
)
VALUES (
    '2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f',
    '4c07f6b1-7208-4b72-826a-a9c65195bf0f',
    (SELECT id FROM entity_definition WHERE code = 'PERSON'),
    'created_by',
    'Created By',
    'ManyToOne'
);

-- Update ORGANIZATION_VACANCY_WORKSTATION FK relationship
UPDATE entity_attribute
SET description = 'Reference to ORGANIZATION_VACANCY entity'
WHERE entity_id = (SELECT id FROM entity_definition WHERE code = 'ORGANIZATION_VACANCY_WORKSTATION')
AND code = 'organization_vacancy_id';

-- Update VACANCY_APPLICATION FK relationship
UPDATE entity_attribute
SET description = 'Reference to ORGANIZATION_VACANCY entity'
WHERE entity_id = (SELECT id FROM entity_definition WHERE code = 'VACANCY_APPLICATION')
AND code = 'vacancy_id';

-- =========================================
-- Verification
-- =========================================
SELECT
    'ORGANIZATION_VACANCY entity created successfully!' as message,
    '4c07f6b1-7208-4b72-826a-a9c65195bf0f' as entity_id,
    'ORGANIZATION_VACANCY' as entity_code,
    (SELECT COUNT(*) FROM entity_attribute WHERE entity_id = '4c07f6b1-7208-4b72-826a-a9c65195bf0f') as attribute_count,
    (SELECT entity_id FROM process_graph WHERE id = 'VC000000-0000-4000-8000-000000000001') as process_entity_id;
