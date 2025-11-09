-- =====================================================================
-- SERVICE_APPOINTMENT Entity Metadata
-- Service appointment booking and scheduling
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: SERVICE_APPOINTMENT
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
    's1a1p1p1-o1i1-4n1t-m111-111111111111',
    'SERVICE_APPOINTMENT',
    'Service Appointment',
    'Scheduled service appointment with provider',
    'MARKETPLACE_COMMERCE',
    'service_appointment',
    1
);

-- =========================================
-- 2. Entity Attributes: SERVICE_APPOINTMENT
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
('s1a1p1p1-0001-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('s1a1p1p1-0002-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('s1a1p1p1-0003-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('s1a1p1p1-0004-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('s1a1p1p1-0005-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('s1a1p1p1-0006-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('s1a1p1p1-0007-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('s1a1p1p1-0008-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'service_provider_id', 'Service Provider ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Service provider (FK to SERVICE_PROVIDER)', 8),
('s1a1p1p1-0009-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'customer_id', 'Customer ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer (FK to PERSON)', 9),
('s1a1p1p1-0010-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'order_item_id', 'Order Item ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Related order item (FK to ORDER_ITEM) - optional', 10),
('s1a1p1p1-0011-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'shopping_cart_item_id', 'Shopping Cart Item ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Cart item if pre-booked (FK to SHOPPING_CART_ITEM) - optional', 11),
('s1a1p1p1-0012-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'service_address_id', 'Service Address ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Service location (FK to POSTAL_ADDRESS) - optional', 12),
('s1a1p1p1-0013-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'rescheduled_from_appointment_id', 'Rescheduled From Appointment ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Original appointment if rescheduled (FK to SERVICE_APPOINTMENT)', 13),
('s1a1p1p1-0014-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'scheduled_by', 'Scheduled By', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person who scheduled (FK to PERSON)', 14),

-- Core Fields
('s1a1p1p1-0015-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'appointment_code', 'Appointment Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique appointment code', 15),
('s1a1p1p1-0016-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'appointment_date', 'Appointment Date', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Date of service', 16),
('s1a1p1p1-0017-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'appointment_time', 'Appointment Time', 'time', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Start time of service', 17),
('s1a1p1p1-0018-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'duration_minutes', 'Duration Minutes', 'integer', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Service duration in minutes', 18),
('s1a1p1p1-0019-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'end_time', 'End Time', 'time', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'End time (computed)', 19),

-- Location
('s1a1p1p1-0020-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'location_type', 'Location Type', 'enum_strings', 1, 0, 0, 0, 'ON_SITE', NULL, NULL, '["ON_SITE","PROVIDER_LOCATION","CUSTOMER_LOCATION","ONLINE"]', NULL, 'Type of service location', 20),
('s1a1p1p1-0021-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'meeting_link', 'Meeting Link', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Link for online appointments', 21),

-- Notes
('s1a1p1p1-0022-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer notes/requests', 22),
('s1a1p1p1-0023-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'provider_notes', 'Provider Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Internal provider notes', 23),

-- Pricing
('s1a1p1p1-0024-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'price_snapshot', 'Price Snapshot', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Price at booking time', 24),

-- Status and Timestamps
('s1a1p1p1-0025-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'status', 'Status', 'text', 1, 0, 0, 0, 'SCHEDULED', NULL, NULL, NULL, NULL, 'Appointment status (FK to ENUM_APPOINTMENT_STATUS)', 25),
('s1a1p1p1-0026-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'confirmed_at', 'Confirmed At', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When appointment was confirmed', 26),
('s1a1p1p1-0027-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'completed_at', 'Completed At', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When service was completed', 27),
('s1a1p1p1-0028-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'cancelled_at', 'Cancelled At', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'When appointment was cancelled', 28),
('s1a1p1p1-0029-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'cancellation_reason', 'Cancellation Reason', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reason for cancellation', 29),

-- Feedback
('s1a1p1p1-0030-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'reminder_sent', 'Reminder Sent', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Whether reminder was sent', 30),
('s1a1p1p1-0031-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'feedback_rating', 'Feedback Rating', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer rating (1-5 stars)', 31),
('s1a1p1p1-0032-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'feedback_comment', 'Feedback Comment', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Customer feedback', 32),
('s1a1p1p1-0033-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 33);

-- =========================================
-- 3. Entity Relationships: SERVICE_APPOINTMENT
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
-- To SERVICE_PROVIDER
('s1a1p1p1-rel1-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'many-to-one', 'appointment_to_service_provider', 'service_provider_id', 'Provider for appointment'),

-- To PERSON (customer)
('s1a1p1p1-rel2-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'appointment_to_customer', 'customer_id', 'Customer booking appointment'),

-- To PERSON (scheduled_by)
('s1a1p1p1-rel3-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'appointment_to_scheduler', 'scheduled_by', 'Person who scheduled'),

-- To POSTAL_ADDRESS
('s1a1p1p1-rel4-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'p1o1s1t1-a1d1-4d1r-s111-111111111111', 'many-to-one', 'appointment_to_address', 'service_address_id', 'Service location address'),

-- To ENUM_APPOINTMENT_STATUS
('s1a1p1p1-rel5-0000-0000-000000000001', 's1a1p1p1-o1i1-4n1t-m111-111111111111', 'e1a1p1p1-s1t1-4a1t-u111-111111111111', 'many-to-one', 'appointment_to_status', 'status', 'Appointment status');
