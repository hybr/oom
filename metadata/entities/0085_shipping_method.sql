-- =====================================================================
-- SHIPPING_METHOD Entity Metadata
-- Shipping method configuration
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    's1h1i1p1-m1e1-4t1d-h111-111111111111', 'SHIPPING_METHOD', 'Shipping Method',
    'Shipping methods with pricing and carriers',
    'MARKETPLACE_COMMERCE', 'shipping_method', 1
);

-- Full implementation: organization_id, method_code, method_name, carrier,
-- base_fee, fee_per_item, fee_per_weight_unit, free_shipping_threshold,
-- estimated_delivery_days, tracking_available, insurance options
