-- =====================================================================
-- CATALOG Entity Metadata
-- Product/service catalog management
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: CATALOG
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
    'c1a1t1a1-l1o1-4g11-a111-111111111111',
    'CATALOG',
    'Catalog',
    'Product and service catalog for organizing items',
    'MARKETPLACE_COMMERCE',
    'catalog',
    1
);

-- =========================================
-- 2. Entity Attributes: CATALOG
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
('c1a1t1a1-0001-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('c1a1t1a1-0002-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('c1a1t1a1-0003-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('c1a1t1a1-0004-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('c1a1t1a1-0005-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('c1a1t1a1-0006-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('c1a1t1a1-0007-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('c1a1t1a1-0008-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization owning this catalog (FK to ORGANIZATION)', 8),

-- Core Fields
('c1a1t1a1-0009-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'catalog_code', 'Catalog Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique catalog identifier code', 9),
('c1a1t1a1-0010-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Catalog display name', 10),
('c1a1t1a1-0011-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Catalog description', 11),
('c1a1t1a1-0012-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'catalog_type', 'Catalog Type', 'enum_strings', 1, 0, 0, 0, 'MIXED', NULL, NULL, '["PRODUCT","SERVICE","MIXED"]', NULL, 'Type of catalog', 12),
('c1a1t1a1-0013-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'is_public', 'Is Public', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether catalog is visible to all', 13),
('c1a1t1a1-0014-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'display_order', 'Display Order', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Display order for sorting', 14),
('c1a1t1a1-0015-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'DRAFT', NULL, NULL, '["DRAFT","ACTIVE","INACTIVE","ARCHIVED"]', NULL, 'Catalog status', 15),
('c1a1t1a1-0016-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'published_date', 'Published Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Date when catalog was published', 16),
('c1a1t1a1-0017-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 17);

-- =========================================
-- 3. Entity Relationships: CATALOG
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
-- To ORGANIZATION
('c1a1t1a1-rel1-0000-0000-000000000001', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'o1r1g1a1-n1z1-4t1n-a111-111111111111', 'many-to-one', 'catalog_to_organization', 'organization_id', 'Catalog belongs to organization');
