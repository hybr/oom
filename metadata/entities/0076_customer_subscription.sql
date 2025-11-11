-- =====================================================================
-- CUSTOMER_SUBSCRIPTION Entity Metadata
-- Active customer subscription for recurring delivery/service
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: CUSTOMER_SUBSCRIPTION
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
    'c1s1u1b1-s1c1-4r1p-t111-111111111111',
    'CUSTOMER_SUBSCRIPTION',
    'Customer Subscription',
    'Active customer subscription with billing and delivery details',
    'MARKETPLACE_COMMERCE',
    'customer_subscription',
    1
);

-- =========================================
-- 2. Entity Attributes: CUSTOMER_SUBSCRIPTION
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
('c1s1u1b1-0001-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('c1s1u1b1-0002-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('c1s1u1b1-0003-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('c1s1u1b1-0004-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('c1s1u1b1-0005-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('c1s1u1b1-0006-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('c1s1u1b1-0007-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('c1s1u1b1-0008-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'subscription_plan_id', 'Subscription Plan ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subscription plan (FK to SUBSCRIPTION_PLAN)', 8),
('c1s1u1b1-0009-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'customer_id', 'Customer ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer (FK to PERSON)', 9),
('c1s1u1b1-0010-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'delivery_address_id', 'Delivery Address ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Delivery address for goods (FK to POSTAL_ADDRESS)', 10),
('c1s1u1b1-0011-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'service_address_id', 'Service Address ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Service address (FK to POSTAL_ADDRESS)', 11),
('c1s1u1b1-0012-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'payment_method_id', 'Payment Method ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Payment method (FK to PAYMENT_METHOD)', 12),

-- Core Fields
('c1s1u1b1-0013-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'subscription_number', 'Subscription Number', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique subscription number', 13),

-- Dates
('c1s1u1b1-0014-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'start_date', 'Start Date', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subscription start date', 14),
('c1s1u1b1-0015-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'trial_end_date', 'Trial End Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Trial period end date', 15),
('c1s1u1b1-0016-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'current_period_start', 'Current Period Start', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Current billing cycle start', 16),
('c1s1u1b1-0017-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'current_period_end', 'Current Period End', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Current billing cycle end', 17),
('c1s1u1b1-0018-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'next_billing_date', 'Next Billing Date', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Next charge date', 18),
('c1s1u1b1-0019-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'end_date', 'End Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Subscription end date', 19),

-- Cancellation
('c1s1u1b1-0020-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'cancellation_requested_date', 'Cancellation Requested Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When cancellation was requested', 20),
('c1s1u1b1-0021-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'cancellation_effective_date', 'Cancellation Effective Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When cancellation takes effect', 21),
('c1s1u1b1-0022-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'cancellation_reason', 'Cancellation Reason', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Why subscription was cancelled', 22),

-- Quantity and Billing
('c1s1u1b1-0023-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'quantity', 'Quantity', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Units per delivery/service', 23),
('c1s1u1b1-0024-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'billing_amount', 'Billing Amount', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Amount charged per interval', 24),

-- Status
('c1s1u1b1-0025-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'status', 'Status', 'text', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, NULL, NULL, 'Subscription status (FK to ENUM_SUBSCRIPTION_STATUS)', 25),
('c1s1u1b1-0026-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'total_billing_cycles_completed', 'Total Billing Cycles Completed', 'integer', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Number of completed billing cycles', 26),

-- Pause
('c1s1u1b1-0027-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'pause_reason', 'Pause Reason', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reason for pausing', 27),
('c1s1u1b1-0028-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'paused_at', 'Paused At', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When subscription was paused', 28),
('c1s1u1b1-0029-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'pause_until_date', 'Pause Until Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Resume date for paused subscription', 29),

-- Additional
('c1s1u1b1-0030-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Additional subscription notes', 30),
('c1s1u1b1-0031-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 31);

-- =========================================
-- 3. Entity Relationships: CUSTOMER_SUBSCRIPTION
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
-- To SUBSCRIPTION_PLAN
('c1s1u1b1-rel1-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 's1u1b1p1-l1a1-4n11-a111-111111111111', 'many-to-one', 'customer_subscription_to_plan', 'subscription_plan_id', 'Subscribed plan'),

-- To PERSON (customer)
('c1s1u1b1-rel2-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'customer_subscription_to_customer', 'customer_id', 'Customer subscribing'),

-- To POSTAL_ADDRESS (delivery)
('c1s1u1b1-rel3-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'p1o1s1t1-a1d1-4d1r-s111-111111111111', 'many-to-one', 'customer_subscription_to_delivery_address', 'delivery_address_id', 'Delivery address for goods'),

-- To POSTAL_ADDRESS (service)
('c1s1u1b1-rel4-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'p1o1s1t1-a1d1-4d1r-s111-111111111111', 'many-to-one', 'customer_subscription_to_service_address', 'service_address_id', 'Service address'),

-- To ENUM_SUBSCRIPTION_STATUS
('c1s1u1b1-rel5-0000-0000-000000000001', 'c1s1u1b1-s1c1-4r1p-t111-111111111111', 'e1s1u1b1-s1t1-4a1t-u111-111111111111', 'many-to-one', 'customer_subscription_to_status', 'status', 'Subscription status');
