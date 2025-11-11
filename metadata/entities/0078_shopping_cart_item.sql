-- =====================================================================
-- SHOPPING_CART_ITEM Entity Metadata
-- Individual items in shopping cart
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: SHOPPING_CART_ITEM
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
    's1c1i1t1-e1m1-4111-a111-111111111111',
    'SHOPPING_CART_ITEM',
    'Shopping Cart Item',
    'Individual item in customer shopping cart',
    'MARKETPLACE_COMMERCE',
    'shopping_cart_item',
    1
);

-- =========================================
-- 2. Entity Attributes: SHOPPING_CART_ITEM
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
('s1c1i1t1-0001-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('s1c1i1t1-0002-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('s1c1i1t1-0003-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('s1c1i1t1-0004-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('s1c1i1t1-0005-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('s1c1i1t1-0006-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('s1c1i1t1-0007-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('s1c1i1t1-0008-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'shopping_cart_id', 'Shopping Cart ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Shopping cart (FK to SHOPPING_CART)', 8),
('s1c1i1t1-0009-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'item_variant_id', 'Item Variant ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Item variant (FK to ITEM_VARIANT)', 9),
('s1c1i1t1-0010-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'subscription_plan_id', 'Subscription Plan ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subscription plan (FK to SUBSCRIPTION_PLAN)', 10),

-- Core Fields
('s1c1i1t1-0011-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'quantity', 'Quantity', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Number of units', 11),
('s1c1i1t1-0012-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'transaction_type', 'Transaction Type', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Transaction type (FK to ENUM_TRANSACTION_TYPE)', 12),

-- Rental Fields
('s1c1i1t1-0013-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'rental_start_date', 'Rental Start Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Rental start date', 13),
('s1c1i1t1-0014-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'rental_end_date', 'Rental End Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Rental end date', 14),
('s1c1i1t1-0015-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'rental_duration_days', 'Rental Duration Days', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Rental duration (computed)', 15),

-- Pricing
('s1c1i1t1-0016-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'unit_price', 'Unit Price', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Price snapshot at time of adding', 16),
('s1c1i1t1-0017-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'discount_percentage', 'Discount Percentage', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Discount percentage', 17),
('s1c1i1t1-0018-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'discount_amount', 'Discount Amount', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Discount amount', 18),
('s1c1i1t1-0019-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'subtotal', 'Subtotal', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Item subtotal (computed)', 19),
('s1c1i1t1-0020-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'tax_amount', 'Tax Amount', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Tax amount', 20),
('s1c1i1t1-0021-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'total', 'Total', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Item total (computed)', 21),

-- Subscription
('s1c1i1t1-0022-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'is_subscription', 'Is Subscription', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Whether this is a subscription', 22),

-- Service Appointment
('s1c1i1t1-0023-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'service_appointment_request', 'Service Appointment Request', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Requested appointment details (JSON)', 23),

-- Additional
('s1c1i1t1-0024-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer notes/preferences', 24),
('s1c1i1t1-0025-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'added_at', 'Added At', 'datetime', 1, 0, 0, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'When item was added to cart', 25),
('s1c1i1t1-0026-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 26);

-- =========================================
-- 3. Entity Relationships: SHOPPING_CART_ITEM
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
-- To SHOPPING_CART
('s1c1i1t1-rel1-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 's1c1a1r1-t000-4111-a111-111111111111', 'many-to-one', 'cart_item_to_cart', 'shopping_cart_id', 'Parent shopping cart'),

-- To ITEM_VARIANT
('s1c1i1t1-rel2-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'many-to-one', 'cart_item_to_variant', 'item_variant_id', 'Item variant being purchased'),

-- To SUBSCRIPTION_PLAN
('s1c1i1t1-rel3-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'many-to-one', 'cart_item_to_subscription_plan', 'subscription_plan_id', 'Subscription plan if subscribing'),

-- To ENUM_TRANSACTION_TYPE
('s1c1i1t1-rel4-0000-0000-000000000001', 's1c1i1t1-e1m1-4111-a111-111111111111', 'e1t1r1a1-n1s1-4t1y-p111-111111111111', 'many-to-one', 'cart_item_to_transaction_type', 'transaction_type', 'Transaction type');
