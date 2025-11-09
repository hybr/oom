-- =====================================================================
-- PERSON_CREDENTIAL Entity Metadata
-- Person authentication credentials and login information
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: PERSON_CREDENTIAL
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
    'p1c1r1e1-d111-4111-a111-b111c111d111',
    'PERSON_CREDENTIAL',
    'Person Credential',
    'Person authentication credentials including username, password, and OTP-based recovery',
    'SECURITY',
    'person_credential',
    1
);

-- =========================================
-- 2. Entity Attributes: PERSON_CREDENTIAL
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
('p1c1r1e1-0001-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1c1r1e1-0002-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1c1r1e1-0003-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1c1r1e1-0004-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1c1r1e1-0005-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1c1r1e1-0006-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1c1r1e1-0007-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('p1c1r1e1-0008-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'person_id', 'Person ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to PERSON entity', 8),

-- Core Credential Fields
('p1c1r1e1-0009-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'username', 'Username', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Login username', 9),
('p1c1r1e1-0010-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'password_hash', 'Password Hash', 'text', 1, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Hashed password (Argon2ID)', 10),
('p1c1r1e1-0011-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether credential is active', 11),

-- Login Tracking
('p1c1r1e1-0012-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'last_login', 'Last Login', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Last successful login timestamp', 12),
('p1c1r1e1-0013-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'failed_login_attempts', 'Failed Login Attempts', 'integer', 0, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Count of failed login attempts', 13),

-- OTP Recovery Fields
('p1c1r1e1-0014-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'otp_code', 'OTP Code', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Hashed OTP for password recovery', 14),
('p1c1r1e1-0015-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'otp_expiry', 'OTP Expiry', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'OTP expiration timestamp', 15),
('p1c1r1e1-0016-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'otp_attempts', 'OTP Attempts', 'integer', 0, 0, 1, 0, '0', NULL, NULL, NULL, NULL, 'Failed OTP verification attempts', 16),
('p1c1r1e1-0017-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'otp_verified_at', 'OTP Verified At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Timestamp when OTP was verified', 17);

-- =========================================
-- 3. Entity Relationships: PERSON_CREDENTIAL
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
('p1c1r1e1-rel1-0000-0000-000000000001', 'p1c1r1e1-d111-4111-a111-b111c111d111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'person_credential_to_person', 'person_id', 'Credential belongs to person');
