-- =====================================================================
-- ENUM_GENDER Entity Metadata
-- Enumeration of gender options
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ENUM_GENDER
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
    'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1',
    'ENUM_GENDER',
    'Gender',
    'Enumeration of gender options for person profiles',
    'PERSON_IDENTITY',
    'enum_gender',
    1
);

-- =========================================
-- 2. Entity Attributes: ENUM_GENDER
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
('e1d1a1b1-0001-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('e1d1a1b1-0002-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('e1d1a1b1-0003-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('e1d1a1b1-0004-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('e1d1a1b1-0005-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('e1d1a1b1-0006-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('e1d1a1b1-0007-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Core Fields
('e1d1a1b1-0008-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique gender code', 8),
('e1d1a1b1-0009-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Gender display name', 9),
('e1d1a1b1-0010-0000-0000-000000000001', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Gender description', 10);
