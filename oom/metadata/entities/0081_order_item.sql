-- =====================================================================
-- ORDER_ITEM Entity Metadata
-- Order line items
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'o1r1d1i1-t1e1-4m11-a111-111111111111', 'ORDER_ITEM', 'Order Item',
    'Individual line item in customer order', 'MARKETPLACE_COMMERCE', 'order_item', 1
);

-- Full implementation includes: order_id, item_variant_id, quantity, transaction_type,
-- rental dates, pricing fields, fulfillment_status, service_appointment_id
