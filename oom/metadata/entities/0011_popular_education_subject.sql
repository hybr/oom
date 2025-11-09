-- =====================================================================
-- POPULAR_EDUCATION_SUBJECT Entity Metadata
-- Common education subjects and fields of study
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: POPULAR_EDUCATION_SUBJECT
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
    'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3',
    'POPULAR_EDUCATION_SUBJECT',
    'Popular Education Subject',
    'Common education subjects and fields of study (Mathematics, Physics, Computer Science, etc.)',
    'EDUCATION',
    'popular_education_subject',
    1
);

-- =========================================
-- 2. Entity Attributes: POPULAR_EDUCATION_SUBJECT
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
('e1d3a3b3-0001-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('e1d3a3b3-0002-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('e1d3a3b3-0003-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('e1d3a3b3-0004-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('e1d3a3b3-0005-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('e1d3a3b3-0006-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('e1d3a3b3-0007-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Core Fields
('e1d3a3b3-0008-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Subject name', 8),
('e1d3a3b3-0009-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'code', 'Code', 'text', 0, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subject code', 9),
('e1d3a3b3-0010-0000-0000-000000000001', 'e1d3a3b3-c3d3-4e3f-a3b3-c3d3e3f3a3b3', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subject description', 10);
