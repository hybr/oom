-- =====================================================================
-- DELIVERY_ZONE Entity Metadata
-- Delivery zone and coverage management
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'd1z1o1n1-e000-4111-a111-111111111111', 'DELIVERY_ZONE', 'Delivery Zone',
    'Delivery coverage zones with fees and timeframes',
    'MARKETPLACE_COMMERCE', 'delivery_zone', 1
);

-- Full implementation: organization_id, zone_code, coverage_type (POSTAL_CODE, CITY,
-- RADIUS, POLYGON), postal_codes, city_ids, center coordinates, radius, polygon,
-- delivery_fee, free_delivery_threshold, estimated_delivery_days
