-- =====================================================================
-- ITEM_VARIANT Entity Metadata
-- Organization-specific product/service offering
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ITEM_VARIANT
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
    'i1v1a1r1-i1a1-4n1t-a111-111111111111',
    'ITEM_VARIANT',
    'Item Variant',
    'Organization-specific variant of a generic item with pricing and availability',
    'MARKETPLACE_COMMERCE',
    'item_variant',
    1
);

-- =========================================
-- 2. Entity Attributes: ITEM_VARIANT
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
('i1v1a1r1-0001-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('i1v1a1r1-0002-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('i1v1a1r1-0003-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('i1v1a1r1-0004-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('i1v1a1r1-0005-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('i1v1a1r1-0006-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('i1v1a1r1-0007-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('i1v1a1r1-0008-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'item_id', 'Item ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Generic item reference (FK to ITEM)', 8),
('i1v1a1r1-0009-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Selling organization (FK to ORGANIZATION)', 9),
('i1v1a1r1-0010-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'organization_building_id', 'Organization Building ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Fulfillment location (FK to ORGANIZATION_BUILDING)', 10),

-- Core Fields
('i1v1a1r1-0011-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'variant_code', 'Variant Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique variant identifier', 11),
('i1v1a1r1-0012-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'variant_name', 'Variant Name', 'text', 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Organization-specific name override', 12),
('i1v1a1r1-0013-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'variant_description', 'Variant Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Additional variant description', 13),
('i1v1a1r1-0014-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'transaction_types', 'Transaction Types', 'json', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Supported transactions (JSON array: ["SALE"], ["RENT"], or both)', 14),

-- Pricing Fields - Sale
('i1v1a1r1-0015-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'sale_price', 'Sale Price', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Price for purchase', 15),
('i1v1a1r1-0016-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'sale_currency', 'Sale Currency', 'text', 1, 0, 0, 0, 'USD', NULL, NULL, NULL, NULL, 'Currency code for sale', 16),

-- Pricing Fields - Rental
('i1v1a1r1-0017-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rental_price_hourly', 'Rental Price Hourly', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Hourly rental rate', 17),
('i1v1a1r1-0018-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rental_price_daily', 'Rental Price Daily', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Daily rental rate', 18),
('i1v1a1r1-0019-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rental_price_weekly', 'Rental Price Weekly', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Weekly rental rate', 19),
('i1v1a1r1-0020-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rental_price_monthly', 'Rental Price Monthly', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Monthly rental rate', 20),
('i1v1a1r1-0021-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rental_currency', 'Rental Currency', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Currency code for rental', 21),
('i1v1a1r1-0022-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rental_deposit', 'Rental Deposit', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Security deposit for rentals', 22),

-- Cost and Discount
('i1v1a1r1-0023-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'cost_price', 'Cost Price', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Internal cost price', 23),
('i1v1a1r1-0024-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'discount_percentage', 'Discount Percentage', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Current discount percentage', 24),
('i1v1a1r1-0025-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'discounted_price', 'Discounted Price', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Price after discount', 25),
('i1v1a1r1-0026-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'tax_rate', 'Tax Rate', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Applicable tax percentage', 26),

-- Inventory Fields
('i1v1a1r1-0027-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'sku', 'SKU', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Stock Keeping Unit', 27),
('i1v1a1r1-0028-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'barcode', 'Barcode', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'UPC/EAN barcode', 28),
('i1v1a1r1-0029-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'condition', 'Condition', 'enum_strings', 1, 0, 0, 0, 'NEW', NULL, NULL, '["NEW","REFURBISHED","USED_LIKE_NEW","USED_GOOD","USED_FAIR"]', NULL, 'Item condition', 29),

-- Warranty and Returns
('i1v1a1r1-0030-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'warranty_period', 'Warranty Period', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Warranty period in months', 30),
('i1v1a1r1-0031-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'warranty_terms', 'Warranty Terms', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Warranty terms and conditions', 31),
('i1v1a1r1-0032-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'return_policy', 'Return Policy', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Return policy details', 32),

-- Availability
('i1v1a1r1-0033-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'availability_status', 'Availability Status', 'enum_strings', 1, 0, 0, 0, 'IN_STOCK', NULL, NULL, '["IN_STOCK","OUT_OF_STOCK","PREORDER","DISCONTINUED"]', NULL, 'Current availability', 33),
('i1v1a1r1-0034-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'lead_time_days', 'Lead Time Days', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Delivery/fulfillment time in days', 34),
('i1v1a1r1-0035-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'min_order_quantity', 'Min Order Quantity', 'integer', 0, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Minimum order quantity', 35),
('i1v1a1r1-0036-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'max_order_quantity', 'Max Order Quantity', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Maximum order quantity per order', 36),

-- Additional Fields
('i1v1a1r1-0037-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'variant_attributes', 'Variant Attributes', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization-specific attributes (JSON)', 37),
('i1v1a1r1-0038-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'is_subscribable', 'Is Subscribable', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Can be subscribed to', 38),

-- Metrics
('i1v1a1r1-0039-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'rating_average', 'Rating Average', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Average rating from reviews', 39),
('i1v1a1r1-0040-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'review_count', 'Review Count', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Number of reviews', 40),
('i1v1a1r1-0041-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'view_count', 'View Count', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Page view counter', 41),
('i1v1a1r1-0042-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'purchase_count', 'Purchase Count', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Number of purchases', 42),

-- Status
('i1v1a1r1-0043-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, '["ACTIVE","INACTIVE","OUT_OF_STOCK"]', NULL, 'Variant status', 43),
('i1v1a1r1-0044-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 44);

-- =========================================
-- 3. Entity Relationships: ITEM_VARIANT
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
-- To ITEM
('i1v1a1r1-rel1-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'i1t1e1m1-0000-4111-a111-111111111111', 'many-to-one', 'item_variant_to_item', 'item_id', 'Variant of generic item'),

-- To ORGANIZATION
('i1v1a1r1-rel2-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'o1r1g1a1-n1z1-4t1n-a111-111111111111', 'many-to-one', 'item_variant_to_organization', 'organization_id', 'Organization selling this variant'),

-- To ORGANIZATION_BUILDING
('i1v1a1r1-rel3-0000-0000-000000000001', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'o1b1l1d1-g111-4111-a111-111111111111', 'many-to-one', 'item_variant_to_building', 'organization_building_id', 'Fulfillment location');
