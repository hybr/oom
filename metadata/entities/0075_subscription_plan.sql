-- =====================================================================
-- SUBSCRIPTION_PLAN Entity Metadata
-- Subscription plans for recurring delivery/service
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: SUBSCRIPTION_PLAN
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
    's1u1b1p1-l1a1-4n11-a111-111111111111',
    'SUBSCRIPTION_PLAN',
    'Subscription Plan',
    'Recurring subscription plan for items or services',
    'MARKETPLACE_COMMERCE',
    'subscription_plan',
    1
);

-- =========================================
-- 2. Entity Attributes: SUBSCRIPTION_PLAN
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
('s1u1b1p1-0001-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('s1u1b1p1-0002-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('s1u1b1p1-0003-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('s1u1b1p1-0004-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('s1u1b1p1-0005-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('s1u1b1p1-0006-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('s1u1b1p1-0007-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('s1u1b1p1-0008-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'item_variant_id', 'Item Variant ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Item being subscribed (FK to ITEM_VARIANT)', 8),

-- Core Fields
('s1u1b1p1-0009-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'plan_code', 'Plan Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique plan code', 9),
('s1u1b1p1-0010-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'plan_name', 'Plan Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Plan display name', 10),
('s1u1b1p1-0011-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Plan description', 11),

-- Billing
('s1u1b1p1-0012-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'billing_interval', 'Billing Interval', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Billing interval (FK to ENUM_SUBSCRIPTION_INTERVAL)', 12),
('s1u1b1p1-0013-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'billing_interval_count', 'Billing Interval Count', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Number of intervals (e.g., 1=weekly, 2=bi-weekly)', 13),
('s1u1b1p1-0014-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'price_per_interval', 'Price Per Interval', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subscription price per billing cycle', 14),
('s1u1b1p1-0015-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'currency', 'Currency', 'text', 1, 0, 0, 0, 'USD', NULL, NULL, NULL, NULL, 'Currency code', 15),

-- Trial and Setup
('s1u1b1p1-0016-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'trial_period_days', 'Trial Period Days', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Free trial period in days', 16),
('s1u1b1p1-0017-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'setup_fee', 'Setup Fee', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'One-time setup cost', 17),

-- Terms
('s1u1b1p1-0018-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'minimum_term_months', 'Minimum Term Months', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Minimum commitment period in months', 18),
('s1u1b1p1-0019-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'cancellation_notice_days', 'Cancellation Notice Days', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Notice period to cancel in days', 19),

-- Delivery/Service Details
('s1u1b1p1-0020-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'delivery_schedule', 'Delivery Schedule', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'For goods: delivery days/times (JSON)', 20),
('s1u1b1p1-0021-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'service_frequency', 'Service Frequency', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'For services: appointments per interval', 21),
('s1u1b1p1-0022-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'quantity_per_delivery', 'Quantity Per Delivery', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Units delivered per cycle', 22),

-- Renewal
('s1u1b1p1-0023-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'is_auto_renew', 'Is Auto Renew', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Auto-renew subscription', 23),
('s1u1b1p1-0024-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'renewal_reminder_days', 'Renewal Reminder Days', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Days before renewal to remind', 24),

-- Benefits and Terms
('s1u1b1p1-0025-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'benefits', 'Benefits', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Plan perks and benefits (JSON)', 25),
('s1u1b1p1-0026-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'terms_and_conditions', 'Terms And Conditions', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Plan terms and conditions', 26),

-- Status
('s1u1b1p1-0027-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, '["ACTIVE","INACTIVE","DISCONTINUED"]', NULL, 'Plan status', 27),
('s1u1b1p1-0028-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 28);

-- =========================================
-- 3. Entity Relationships: SUBSCRIPTION_PLAN
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
-- To ITEM_VARIANT
('s1u1b1p1-rel1-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'many-to-one', 'subscription_plan_to_item_variant', 'item_variant_id', 'Item being subscribed'),

-- To ENUM_SUBSCRIPTION_INTERVAL
('s1u1b1p1-rel2-0000-0000-000000000001', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'e1s1u1b1-i1n1-4t1e-r111-111111111111', 'many-to-one', 'subscription_plan_to_interval', 'billing_interval', 'Billing interval type');
