-- =====================================================================
-- ENUM_SUBSCRIPTION_STATUS Entity Metadata
-- Enumeration of subscription statuses
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ENUM_SUBSCRIPTION_STATUS
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
    'e1s1u1b1-s1t1-4a1t-u111-111111111111',
    'ENUM_SUBSCRIPTION_STATUS',
    'Subscription Status',
    'Enumeration of subscription statuses (trial, active, paused, cancelled, expired)',
    'MARKETPLACE_COMMERCE',
    'enum_subscription_status',
    1
);

-- =========================================
-- 2. Entity Attributes: ENUM_SUBSCRIPTION_STATUS
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
('e1s1u1b1-0001-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('e1s1u1b1-0002-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('e1s1u1b1-0003-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('e1s1u1b1-0004-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('e1s1u1b1-0005-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('e1s1u1b1-0006-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('e1s1u1b1-0007-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Core Fields
('e1s1u1b1-0008-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique status code', 8),
('e1s1u1b1-0009-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Status display name', 9),
('e1s1u1b1-0010-0000-0000-000000000001', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Status description', 10);
