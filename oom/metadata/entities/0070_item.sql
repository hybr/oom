-- =====================================================================
-- ITEM Entity Metadata
-- Generic product or service item
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ITEM
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
    'i1t1e1m1-0000-4111-a111-111111111111',
    'ITEM',
    'Item',
    'Generic product or service item in the marketplace',
    'MARKETPLACE_COMMERCE',
    'item',
    1
);

-- =========================================
-- 2. Entity Attributes: ITEM
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
('i1t1e1m1-0001-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('i1t1e1m1-0002-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('i1t1e1m1-0003-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('i1t1e1m1-0004-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('i1t1e1m1-0005-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('i1t1e1m1-0006-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('i1t1e1m1-0007-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('i1t1e1m1-0008-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'category_id', 'Category ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Category (FK to CATEGORY)', 8),

-- Core Fields
('i1t1e1m1-0009-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'item_code', 'Item Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique item identifier', 9),
('i1t1e1m1-0010-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Item name', 10),
('i1t1e1m1-0011-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'description', 'Description', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Detailed item description', 11),
('i1t1e1m1-0012-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'short_description', 'Short Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Brief item summary', 12),
('i1t1e1m1-0013-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'item_type', 'Item Type', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Type of item (FK to ENUM_ITEM_TYPE)', 13),
('i1t1e1m1-0014-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'brand', 'Brand', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Brand name', 14),
('i1t1e1m1-0015-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'model', 'Model', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Model number or name', 15),
('i1t1e1m1-0016-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'manufacturer', 'Manufacturer', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Manufacturer name', 16),
('i1t1e1m1-0017-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'specifications', 'Specifications', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Generic specifications (JSON)', 17),
('i1t1e1m1-0018-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'need_want_classification', 'Need/Want Classification', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Classification (FK to ENUM_NEED_WANT_CLASSIFICATION)', 18),
('i1t1e1m1-0019-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'is_featured', 'Is Featured', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Featured on homepage', 19),
('i1t1e1m1-0020-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'is_trending', 'Is Trending', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Show in trending section', 20),
('i1t1e1m1-0021-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'tags', 'Tags', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Search tags (JSON array)', 21),
('i1t1e1m1-0022-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'DRAFT', NULL, NULL, '["DRAFT","ACTIVE","INACTIVE","DISCONTINUED"]', NULL, 'Item status', 22),
('i1t1e1m1-0023-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 23);

-- =========================================
-- 3. Entity Relationships: ITEM
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
-- To CATEGORY
('i1t1e1m1-rel1-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'c1a1t1e1-g1o1-4r1y-a111-111111111111', 'many-to-one', 'item_to_category', 'category_id', 'Item belongs to category'),

-- To ENUM_ITEM_TYPE
('i1t1e1m1-rel2-0000-0000-000000000001', 'i1t1e1m1-0000-4111-a111-111111111111', 'e1i1t1e1-m1t1-4y1p-e111-111111111111', 'many-to-one', 'item_to_item_type', 'item_type', 'Item type reference');
