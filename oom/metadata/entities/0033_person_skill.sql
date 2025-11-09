-- =====================================================================
-- PERSON_SKILL Entity Metadata
-- Person skills and proficiency levels
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: PERSON_SKILL
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
    'p1s1k1i1-l111-4l11-s111-k111i111l111',
    'PERSON_SKILL',
    'Person Skill',
    'Person skills with proficiency levels and optional certification details',
    'PERSON_IDENTITY',
    'person_skill',
    1
);

-- =========================================
-- 2. Entity Attributes: PERSON_SKILL
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
('p1s1k1i1-0001-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1s1k1i1-0002-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1s1k1i1-0003-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1s1k1i1-0004-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1s1k1i1-0005-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1s1k1i1-0006-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1s1k1i1-0007-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('p1s1k1i1-0008-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'person_id', 'Person ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to PERSON entity', 8),
('p1s1k1i1-0009-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'skill_id', 'Skill ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to POPULAR_SKILL', 9),
('p1s1k1i1-0010-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'level', 'Proficiency Level', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ENUM_SKILL_LEVEL', 10),
('p1s1k1i1-0011-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'institution_id', 'Institution ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Training/certifying institution (FK to ORGANIZATION)', 11),
('p1s1k1i1-0012-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'marks_type', 'Marks Type', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ENUM_MARKS_TYPE', 12),

-- Core Fields
('p1s1k1i1-0013-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'start_date', 'Start Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Skill training start date', 13),
('p1s1k1i1-0014-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'complete_date', 'Completion Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Skill training completion date', 14),
('p1s1k1i1-0015-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'marks', 'Marks', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Marks obtained in certification', 15);

-- =========================================
-- 3. Entity Relationships: PERSON_SKILL
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
('p1s1k1i1-rel1-0000-0000-000000000001', 'p1s1k1i1-l111-4l11-s111-k111i111l111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'person_skill_to_person', 'person_id', 'Skill belongs to person');
