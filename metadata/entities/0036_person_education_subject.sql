-- =====================================================================
-- PERSON_EDUCATION_SUBJECT Entity Metadata
-- Subjects and marks for person education records
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: PERSON_EDUCATION_SUBJECT
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
    'p1e1s1u1-b111-4j11-e111-c111t111s111',
    'PERSON_EDUCATION_SUBJECT',
    'Person Education Subject',
    'Subjects studied and marks obtained in person education records',
    'PERSON_IDENTITY',
    'person_education_subject',
    1
);

-- =========================================
-- 2. Entity Attributes: PERSON_EDUCATION_SUBJECT
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
('p1e1s1u1-0001-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1e1s1u1-0002-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1e1s1u1-0003-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1e1s1u1-0004-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1e1s1u1-0005-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1e1s1u1-0006-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1e1s1u1-0007-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('p1e1s1u1-0008-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'person_education_id', 'Person Education ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to PERSON_EDUCATION', 8),
('p1e1s1u1-0009-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'subject_id', 'Subject ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to POPULAR_EDUCATION_SUBJECT', 9),
('p1e1s1u1-0010-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'marks_type', 'Marks Type', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ENUM_MARKS_TYPE', 10),

-- Core Fields
('p1e1s1u1-0011-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'marks', 'Marks', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Marks obtained in the subject', 11);

-- =========================================
-- 3. Entity Relationships: PERSON_EDUCATION_SUBJECT
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
-- To PERSON_EDUCATION
('p1e1s1u1-rel1-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'p1e1d1u1-c111-4a11-t111-i111o111n111', 'many-to-one', 'person_education_subject_to_person_education', 'person_education_id', 'Subject belongs to person education'),

-- To POPULAR_EDUCATION_SUBJECT
('p1e1s1u1-rel2-0000-0000-000000000001', 'p1e1s1u1-b111-4j11-e111-c111t111s111', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'many-to-one', 'person_education_subject_to_popular_subject', 'subject_id', 'Reference to popular education subject');
