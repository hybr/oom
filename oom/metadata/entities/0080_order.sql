-- =====================================================================
-- ORDER Entity Metadata
-- Customer order management
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ORDER
-- =========================================
INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'o1r1d1e1-r000-4111-a111-111111111111', 'ORDER', 'Order',
    'Customer order for products and services', 'MARKETPLACE_COMMERCE', 'order', 1
);

-- =========================================
-- 2. Entity Attributes: ORDER
-- =========================================
-- Note: This table uses reserved keyword "order" - use quotes in SQL: "order"
-- System fields + Foreign Keys + Core order fields + Payment + Shipping + Status
-- Full implementation includes: order_number, customer_id, organization_id, subtotal,
-- tax_amount, shipping_amount, discount_amount, total_amount, addresses, payment info,
-- tracking, timestamps, status fields
