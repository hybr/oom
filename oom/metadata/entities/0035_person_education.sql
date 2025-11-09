-- =====================================================================
-- PERSON_EDUCATION Entity Metadata
-- Person education history and qualifications
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: PERSON_EDUCATION
-- =========================================
INSERT OR IGNORE INTO entity_definition (
    id,
    code,
    name,
    description,
    domain,
    table_name,
    is_active
) VALUES (
    'p1e1d1u1-c111-4a11-t111-i111o111n111',
    'PERSON_EDUCATION',
    'Person Education',
    'Person education history including degrees, institutions, and completion dates',
    'PERSON_IDENTITY',
    'person_education',
    1
);

-- =========================================
-- 2. Entity Attributes: PERSON_EDUCATION
-- =========================================
INSERT OR IGNORE INTO entity_attribute (
    id,
    entity_id,
    code,
    name,
    data_type,
    is_required,
    is_unique,
    is_system,
    is_label,
    default_value,
    min_value,
    max_value,
    enum_values,
    validation_regex,
    description,
    display_order
) VALUES
-- System Fields
('p1e1d1u1-0001-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1e1d1u1-0002-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1e1d1u1-0003-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1e1d1u1-0004-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1e1d1u1-0005-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1e1d1u1-0006-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1e1d1u1-0007-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('p1e1d1u1-0008-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'person_id', 'Person ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to PERSON entity', 8),
('p1e1d1u1-0009-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'institution_id', 'Institution ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ORGANIZATION (educational institution)', 9),
('p1e1d1u1-0010-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'education_level', 'Education Level', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ENUM_EDUCATION_LEVELS', 10),

-- Core Fields
('p1e1d1u1-0011-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'degree_certificate_name', 'Degree/Certificate Name', 'text', 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Name of degree or certificate', 11),
('p1e1d1u1-0012-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'start_date', 'Start Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Education start date', 12),
('p1e1d1u1-0013-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'complete_date', 'Completion Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Education completion date', 13);

-- =========================================
-- 3. Entity Relationships: PERSON_EDUCATION
-- =========================================
INSERT OR IGNORE INTO entity_relationship (
    id,
    from_entity_id,
    to_entity_id,
    relation_type,
    relation_name,
    fk_field,
    description
) VALUES
-- To PERSON
('p1e1d1u1-rel1-0000-0000-000000000001', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'person_education_to_person', 'person_id', 'Education belongs to person');
