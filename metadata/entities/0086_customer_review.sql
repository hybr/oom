-- =====================================================================
-- CUSTOMER_REVIEW Entity Metadata
-- Customer reviews and ratings
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'c1r1e1v1-i1e1-4w11-a111-111111111111', 'CUSTOMER_REVIEW', 'Customer Review',
    'Customer reviews and ratings for items, variants, and service providers',
    'MARKETPLACE_COMMERCE', 'customer_review', 1
);

-- Full implementation: item_id/item_variant_id/service_provider_id, order_item_id,
-- customer_id, rating (1-5), review_title, review_text, pros/cons (JSON),
-- is_verified_purchase, helpful_count, status (PENDING, APPROVED, REJECTED),
-- organization_response
