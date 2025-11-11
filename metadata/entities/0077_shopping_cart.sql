-- =====================================================================
-- SHOPPING_CART Entity Metadata
-- Customer shopping cart management
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: SHOPPING_CART
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
    's1c1a1r1-t000-4111-a111-111111111111',
    'SHOPPING_CART',
    'Shopping Cart',
    'Customer shopping cart for adding items before checkout',
    'MARKETPLACE_COMMERCE',
    'shopping_cart',
    1
);

-- =========================================
-- 2. Entity Attributes: SHOPPING_CART
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
('s1c1a1r1-0001-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('s1c1a1r1-0002-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('s1c1a1r1-0003-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('s1c1a1r1-0004-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('s1c1a1r1-0005-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('s1c1a1r1-0006-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('s1c1a1r1-0007-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('s1c1a1r1-0008-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'customer_id', 'Customer ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer (FK to PERSON)', 8),
('s1c1a1r1-0009-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'converted_to_order_id', 'Converted To Order ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Order created from cart (FK to ORDER)', 9),

-- Core Fields
('s1c1a1r1-0010-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'session_id', 'Session ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Session ID for guest users', 10),
('s1c1a1r1-0011-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'cart_status', 'Cart Status', 'text', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, NULL, NULL, 'Cart status (FK to ENUM_CART_STATUS)', 11),

-- Timestamps
('s1c1a1r1-0012-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'last_activity_at', 'Last Activity At', 'datetime', 1, 0, 0, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Last cart activity timestamp', 12),
('s1c1a1r1-0013-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'abandoned_at', 'Abandoned At', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When cart was abandoned', 13),
('s1c1a1r1-0014-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'converted_at', 'Converted At', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When cart was converted to order', 14),

-- Totals (computed)
('s1c1a1r1-0015-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'subtotal', 'Subtotal', 'number', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Cart subtotal (computed)', 15),
('s1c1a1r1-0016-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'tax_amount', 'Tax Amount', 'number', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Total tax (computed)', 16),
('s1c1a1r1-0017-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'shipping_amount', 'Shipping Amount', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Estimated shipping', 17),
('s1c1a1r1-0018-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'discount_amount', 'Discount Amount', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Total discounts', 18),
('s1c1a1r1-0019-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'total', 'Total', 'number', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Cart total (computed)', 19),
('s1c1a1r1-0020-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'currency', 'Currency', 'text', 1, 0, 0, 0, 'USD', NULL, NULL, NULL, NULL, 'Currency code', 20),

-- Additional
('s1c1a1r1-0021-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer cart notes', 21),
('s1c1a1r1-0022-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 22);

-- =========================================
-- 3. Entity Relationships: SHOPPING_CART
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
-- To PERSON
('s1c1a1r1-rel1-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'shopping_cart_to_customer', 'customer_id', 'Cart owner'),

-- To ENUM_CART_STATUS
('s1c1a1r1-rel2-0000-0000-000000000001', 's1c1a1r1-t000-4111-a111-111111111111', 'e1c1a1r1-s1t1-4a1t-u111-111111111111', 'many-to-one', 'shopping_cart_to_status', 'cart_status', 'Cart status');
