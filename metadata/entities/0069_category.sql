-- =====================================================================
-- CATEGORY Entity Metadata
-- Hierarchical category structure for organizing items
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: CATEGORY
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
    'c1a1t1e1-g1o1-4r1y-a111-111111111111',
    'CATEGORY',
    'Category',
    'Hierarchical product and service categories',
    'MARKETPLACE_COMMERCE',
    'category',
    1
);

-- =========================================
-- 2. Entity Attributes: CATEGORY
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
('c1a1t1e1-0001-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('c1a1t1e1-0002-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('c1a1t1e1-0003-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('c1a1t1e1-0004-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('c1a1t1e1-0005-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('c1a1t1e1-0006-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('c1a1t1e1-0007-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('c1a1t1e1-0008-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'catalog_id', 'Catalog ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Parent catalog (FK to CATALOG)', 8),
('c1a1t1e1-0009-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'parent_category_id', 'Parent Category ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Parent category for hierarchy (FK to CATEGORY)', 9),
('c1a1t1e1-0010-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'banner_image_media_file_id', 'Banner Image', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Banner image (FK to MEDIA_FILE)', 10),

-- Core Fields
('c1a1t1e1-0011-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'category_code', 'Category Code', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique category code within catalog', 11),
('c1a1t1e1-0012-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Category display name', 12),
('c1a1t1e1-0013-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Category description', 13),
('c1a1t1e1-0014-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'icon_url', 'Icon URL', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Category icon URL', 14),
('c1a1t1e1-0015-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'display_order', 'Display Order', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Display order within parent', 15),
('c1a1t1e1-0016-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'level', 'Level', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Hierarchy level (1, 2, 3...)', 16),
('c1a1t1e1-0017-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'full_path', 'Full Path', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Computed full category path', 17),
('c1a1t1e1-0018-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'is_leaf', 'Is Leaf', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether category has no children', 18),
('c1a1t1e1-0019-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, '["ACTIVE","INACTIVE"]', NULL, 'Category status', 19),
('c1a1t1e1-0020-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 20);

-- =========================================
-- 3. Entity Relationships: CATEGORY
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
-- To CATALOG
('c1a1t1e1-rel1-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'c1a1t1a1-l1o1-4g11-a111-111111111111', 'many-to-one', 'category_to_catalog', 'catalog_id', 'Category belongs to catalog'),

-- To CATEGORY (self-referencing)
('c1a1t1e1-rel2-0000-0000-000000000001', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'many-to-one', 'category_to_parent_category', 'parent_category_id', 'Category belongs to parent category');
