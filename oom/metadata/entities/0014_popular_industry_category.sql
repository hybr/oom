-- =====================================================================
-- POPULAR_INDUSTRY_CATEGORY Entity Metadata
-- Hierarchical industry classification system
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: POPULAR_INDUSTRY_CATEGORY
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
    'p1i1n1d1-u1s1-4t11-r111-y111c111a111',
    'POPULAR_INDUSTRY_CATEGORY',
    'Popular Industry Category',
    'Hierarchical classification of business industries for organization categorization',
    'POPULAR_ORG_STRUCTURE',
    'popular_industry_category',
    1
);

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (
    id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label,
    default_value, min_value, max_value, enum_values, validation_regex, description, display_order
) VALUES
('p1i1n1d1-0001-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1i1n1d1-0002-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1i1n1d1-0003-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1i1n1d1-0004-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1i1n1d1-0005-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1i1n1d1-0006-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1i1n1d1-0007-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),
('p1i1n1d1-0008-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'parent_category_id', 'Parent Category ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Parent category (self-referencing)', 8),
('p1i1n1d1-0009-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique category code', 9),
('p1i1n1d1-0010-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Industry category name', 10),
('p1i1n1d1-0011-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'level', 'Level', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Hierarchy level (1=root, 2=child, etc.)', 11),
('p1i1n1d1-0012-0000-0000-000000000001', 'p1i1n1d1-u1s1-4t11-r111-y111c111a111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Category description', 12);
