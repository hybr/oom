-- =====================================================================
-- POPULAR_ORGANIZATION_LEGAL_TYPES Entity Metadata
-- Country-specific legal entity types
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'p1l1e1g1-a1l1-4t11-y111-p111e111s111',
    'POPULAR_ORGANIZATION_LEGAL_TYPES',
    'Popular Organization Legal Types',
    'Country-specific legal entity structures (LLC, Ltd, GmbH, etc.) with capital requirements',
    'POPULAR_ORG_STRUCTURE',
    'popular_organization_legal_types',
    1
);

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (
    id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label,
    default_value, min_value, max_value, enum_values, validation_regex, description, display_order
) VALUES
('p1l1e1g1-0001-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1l1e1g1-0002-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1l1e1g1-0003-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1l1e1g1-0004-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1l1e1g1-0005-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1l1e1g1-0006-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1l1e1g1-0007-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),
('p1l1e1g1-0008-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'country_id', 'Country ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'FK to COUNTRY', 8),
('p1l1e1g1-0009-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'code', 'Code', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Legal type code (LLC, Ltd, etc.)', 9),
('p1l1e1g1-0010-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Full legal type name', 10),
('p1l1e1g1-0011-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Legal type description', 11),
('p1l1e1g1-0012-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'requires_minimum_capital', 'Requires Minimum Capital', 'boolean', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Whether minimum capital is required', 12),
('p1l1e1g1-0013-0000-0000-000000000001', 'p1l1e1g1-a1l1-4t11-y111-p111e111s111', 'minimum_capital_amount', 'Minimum Capital Amount', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Minimum required capital', 13);
