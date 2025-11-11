-- =====================================================================
-- FINISHED_GOODS_INVENTORY Entity Metadata
-- Inventory management for physical goods
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: FINISHED_GOODS_INVENTORY
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
    'f1g1i1n1-v1e1-4n1t-a111-111111111111',
    'FINISHED_GOODS_INVENTORY',
    'Finished Goods Inventory',
    'Stock management and inventory tracking for physical goods',
    'MARKETPLACE_COMMERCE',
    'finished_goods_inventory',
    1
);

-- =========================================
-- 2. Entity Attributes: FINISHED_GOODS_INVENTORY
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
('f1g1i1n1-0001-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('f1g1i1n1-0002-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('f1g1i1n1-0003-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('f1g1i1n1-0004-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('f1g1i1n1-0005-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('f1g1i1n1-0006-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('f1g1i1n1-0007-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('f1g1i1n1-0008-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'item_variant_id', 'Item Variant ID', 'text', 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Item variant (FK to ITEM_VARIANT) - one-to-one', 8),
('f1g1i1n1-0009-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'organization_building_id', 'Organization Building ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Warehouse/store location (FK to ORGANIZATION_BUILDING)', 9),

-- Stock Quantity Fields
('f1g1i1n1-0010-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'quantity_on_hand', 'Quantity On Hand', 'integer', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Current physical stock quantity', 10),
('f1g1i1n1-0011-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'quantity_reserved', 'Quantity Reserved', 'integer', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Reserved in carts/pending orders', 11),
('f1g1i1n1-0012-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'quantity_available', 'Quantity Available', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Available quantity (on_hand - reserved) - computed', 12),
('f1g1i1n1-0013-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'quantity_committed', 'Quantity Committed', 'integer', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Committed to confirmed orders', 13),

-- Reorder Fields
('f1g1i1n1-0014-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'reorder_level', 'Reorder Level', 'integer', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Trigger reorder when stock falls below', 14),
('f1g1i1n1-0015-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'reorder_quantity', 'Reorder Quantity', 'integer', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'How much to reorder', 15),
('f1g1i1n1-0016-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'maximum_stock_level', 'Maximum Stock Level', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Storage capacity limit', 16),

-- Location and Tracking
('f1g1i1n1-0017-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'warehouse_location', 'Warehouse Location', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Bin/aisle location in warehouse', 17),
('f1g1i1n1-0018-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'batch_number', 'Batch Number', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Batch tracking number', 18),
('f1g1i1n1-0019-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'lot_number', 'Lot Number', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Manufacturing lot number', 19),
('f1g1i1n1-0020-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'expiry_date', 'Expiry Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Expiration date for perishable items', 20),

-- Restocking Information
('f1g1i1n1-0021-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'last_restocked_date', 'Last Restocked Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Date of last restocking', 21),
('f1g1i1n1-0022-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'last_restocked_quantity', 'Last Restocked Quantity', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Quantity added in last restock', 22),
('f1g1i1n1-0023-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'last_stock_check_date', 'Last Stock Check Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Date of last physical stock check', 23),

-- Additional Fields
('f1g1i1n1-0024-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Additional inventory notes', 24),
('f1g1i1n1-0025-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 25);

-- =========================================
-- 3. Entity Relationships: FINISHED_GOODS_INVENTORY
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
-- To ITEM_VARIANT (one-to-one)
('f1g1i1n1-rel1-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'one-to-one', 'inventory_to_item_variant', 'item_variant_id', 'Inventory for item variant'),

-- To ORGANIZATION_BUILDING
('f1g1i1n1-rel2-0000-0000-000000000001', 'f1g1i1n1-v1e1-4n1t-a111-111111111111', 'o1b1l1d1-g111-4111-a111-111111111111', 'many-to-one', 'inventory_to_building', 'organization_building_id', 'Warehouse location');
