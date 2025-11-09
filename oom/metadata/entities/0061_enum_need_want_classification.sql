-- =====================================================================
-- ENUM_NEED_WANT_CLASSIFICATION Entity Metadata
-- Enumeration for classifying items as needs or wants
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ENUM_NEED_WANT_CLASSIFICATION
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
    'e1n1w1c1-l1a1-4s1s-i111-111111111111',
    'ENUM_NEED_WANT_CLASSIFICATION',
    'Need Want Classification',
    'Enumeration for classifying items as essential needs or non-essential wants',
    'MARKETPLACE_COMMERCE',
    'enum_need_want_classification',
    1
);

-- =========================================
-- 2. Entity Attributes: ENUM_NEED_WANT_CLASSIFICATION
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
('e1n1w1c1-0001-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('e1n1w1c1-0002-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('e1n1w1c1-0003-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('e1n1w1c1-0004-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('e1n1w1c1-0005-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('e1n1w1c1-0006-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('e1n1w1c1-0007-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Core Fields
('e1n1w1c1-0008-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique classification code', 8),
('e1n1w1c1-0009-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Classification display name', 9),
('e1n1w1c1-0010-0000-0000-000000000001', 'e1n1w1c1-l1a1-4s1s-i111-111111111111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Classification description', 10);
