-- =====================================================================
-- PAYMENT_METHOD Entity Metadata
-- Customer payment method storage
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: PAYMENT_METHOD
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
    'p1a1y1m1-e1t1-4h1d-o111-111111111111',
    'PAYMENT_METHOD',
    'Payment Method',
    'Customer payment method for orders and subscriptions',
    'MARKETPLACE_COMMERCE',
    'payment_method',
    1
);

-- =========================================
-- 2. Entity Attributes: PAYMENT_METHOD
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
('p1a1y1m1-0001-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1a1y1m1-0002-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1a1y1m1-0003-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1a1y1m1-0004-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1a1y1m1-0005-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1a1y1m1-0006-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1a1y1m1-0007-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('p1a1y1m1-0008-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'person_id', 'Person ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Payment method owner (FK to PERSON)', 8),
('p1a1y1m1-0009-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'billing_address_id', 'Billing Address ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Billing address (FK to POSTAL_ADDRESS)', 9),

-- Core Fields
('p1a1y1m1-0010-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'payment_type', 'Payment Type', 'enum_strings', 1, 0, 0, 0, NULL, NULL, NULL, '["CREDIT_CARD","DEBIT_CARD","PAYPAL","BANK_ACCOUNT","DIGITAL_WALLET"]', NULL, 'Type of payment method', 10),
('p1a1y1m1-0011-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'is_default', 'Is Default', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Default payment method', 11),

-- Card Details (NEVER store full card numbers or CVV!)
('p1a1y1m1-0012-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'card_brand', 'Card Brand', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Card brand (Visa, Mastercard, etc.)', 12),
('p1a1y1m1-0013-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'card_last_four', 'Card Last Four', 'text', 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Last 4 digits of card', 13),
('p1a1y1m1-0014-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'card_expiry_month', 'Card Expiry Month', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Card expiration month', 14),
('p1a1y1m1-0015-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'card_expiry_year', 'Card Expiry Year', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Card expiration year', 15),
('p1a1y1m1-0016-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'cardholder_name', 'Cardholder Name', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Name on card', 16),

-- Payment Gateway Integration
('p1a1y1m1-0017-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'payment_gateway', 'Payment Gateway', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Payment gateway (Stripe, PayPal)', 17),
('p1a1y1m1-0018-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'gateway_customer_id', 'Gateway Customer ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer ID in payment gateway', 18),
('p1a1y1m1-0019-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'gateway_payment_method_id', 'Gateway Payment Method ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Payment method ID in gateway', 19),

-- Status
('p1a1y1m1-0020-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, '["ACTIVE","EXPIRED","INVALID"]', NULL, 'Payment method status', 20),
('p1a1y1m1-0021-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 21);

-- =========================================
-- 3. Entity Relationships: PAYMENT_METHOD
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
('p1a1y1m1-rel1-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'payment_method_to_person', 'person_id', 'Payment method owner'),

-- To POSTAL_ADDRESS
('p1a1y1m1-rel2-0000-0000-000000000001', 'p1a1y1m1-e1t1-4h1d-o111-111111111111', 'p1o1s1t1-a1d1-4d1r-s111-111111111111', 'many-to-one', 'payment_method_to_billing_address', 'billing_address_id', 'Billing address');
