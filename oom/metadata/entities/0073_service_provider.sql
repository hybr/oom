-- =====================================================================
-- SERVICE_PROVIDER Entity Metadata
-- Service provider management for service offerings
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: SERVICE_PROVIDER
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
    's1p1r1o1-v1i1-4d1e-r111-111111111111',
    'SERVICE_PROVIDER',
    'Service Provider',
    'Individual service provider for service item variants',
    'MARKETPLACE_COMMERCE',
    'service_provider',
    1
);

-- =========================================
-- 2. Entity Attributes: SERVICE_PROVIDER
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
('s1p1r1o1-0001-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('s1p1r1o1-0002-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('s1p1r1o1-0003-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('s1p1r1o1-0004-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('s1p1r1o1-0005-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('s1p1r1o1-0006-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('s1p1r1o1-0007-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('s1p1r1o1-0008-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'item_variant_id', 'Item Variant ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Service offering (FK to ITEM_VARIANT)', 8),
('s1p1r1o1-0009-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'person_id', 'Person ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Service provider person (FK to PERSON)', 9),
('s1p1r1o1-0010-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization (FK to ORGANIZATION)', 10),

-- Core Fields
('s1p1r1o1-0011-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'provider_code', 'Provider Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique provider identifier', 11),
('s1p1r1o1-0012-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'specialization', 'Specialization', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Area of expertise', 12),
('s1p1r1o1-0013-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'experience_years', 'Experience Years', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Years of experience', 13),
('s1p1r1o1-0014-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'qualifications', 'Qualifications', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Degrees, certifications (JSON array)', 14),
('s1p1r1o1-0015-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'license_number', 'License Number', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Professional license number', 15),
('s1p1r1o1-0016-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'license_expiry_date', 'License Expiry Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'License expiration date', 16),

-- Pricing and Capacity
('s1p1r1o1-0017-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'hourly_rate', 'Hourly Rate', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Hourly service rate', 17),
('s1p1r1o1-0018-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'service_capacity', 'Service Capacity', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Maximum concurrent appointments', 18),

-- Availability
('s1p1r1o1-0019-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'availability_schedule', 'Availability Schedule', 'json', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Weekly availability schedule (JSON)', 19),
('s1p1r1o1-0020-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'advance_booking_days', 'Advance Booking Days', 'integer', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'How many days in advance to book', 20),
('s1p1r1o1-0021-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'cancellation_policy', 'Cancellation Policy', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Cancellation policy terms', 21),

-- Metrics
('s1p1r1o1-0022-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'rating_average', 'Rating Average', 'number', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Average rating from reviews', 22),
('s1p1r1o1-0023-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'review_count', 'Review Count', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Number of reviews', 23),
('s1p1r1o1-0024-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'completed_appointments_count', 'Completed Appointments Count', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Total completed appointments', 24),

-- Status
('s1p1r1o1-0025-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'ACTIVE', NULL, NULL, '["ACTIVE","INACTIVE","ON_LEAVE","SUSPENDED"]', NULL, 'Provider status', 25),
('s1p1r1o1-0026-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 26);

-- =========================================
-- 3. Entity Relationships: SERVICE_PROVIDER
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
('s1p1r1o1-rel1-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'i1v1a1r1-i1a1-4n1t-a111-111111111111', 'many-to-one', 'service_provider_to_item_variant', 'item_variant_id', 'Service offering'),

-- To PERSON
('s1p1r1o1-rel2-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'service_provider_to_person', 'person_id', 'Service provider person'),

-- To ORGANIZATION
('s1p1r1o1-rel3-0000-0000-000000000001', 's1p1r1o1-v1i1-4d1e-r111-111111111111', 'o1r1g1a1-n1z1-4t1n-a111-111111111111', 'many-to-one', 'service_provider_to_organization', 'organization_id', 'Provider organization');
