-- =====================================================================
-- PERSON Entity Metadata
-- Individual person profile and personal details
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: PERSON
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
    'p1e1r1s1-o1n1-4111-a111-b111c111d111',
    'PERSON',
    'Person',
    'Individual person profile and personal details',
    'PERSON_IDENTITY',
    'person',
    1
);

-- =========================================
-- 2. Entity Attributes: PERSON
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
('p1e1r1s1-0001-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('p1e1r1s1-0002-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('p1e1r1s1-0003-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('p1e1r1s1-0004-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('p1e1r1s1-0005-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('p1e1r1s1-0006-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('p1e1r1s1-0007-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Core Name Fields
('p1e1r1s1-0008-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'name_prefix', 'Name Prefix', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Name prefix (Mr., Mrs., Dr., etc.)', 8),
('p1e1r1s1-0009-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'first_name', 'First Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Person first name', 9),
('p1e1r1s1-0010-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'middle_name', 'Middle Name', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person middle name', 10),
('p1e1r1s1-0011-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'last_name', 'Last Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Person last name', 11),
('p1e1r1s1-0012-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'name_suffix', 'Name Suffix', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Name suffix (Jr., Sr., III, etc.)', 12),

-- Personal Information
('p1e1r1s1-0013-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'date_of_birth', 'Date of Birth', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person date of birth', 13),
('p1e1r1s1-0014-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'gender', 'Gender', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person gender (FK to ENUM_GENDER)', 14),
('p1e1r1s1-0015-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'blood_group', 'Blood Group', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person blood group (FK to ENUM_BLOOD_GROUP)', 15),
('p1e1r1s1-0016-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'nationality', 'Nationality', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person nationality (FK to COUNTRY)', 16),

-- Contact Information
('p1e1r1s1-0017-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'primary_phone_number', 'Primary Phone Number', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, '^\\+?[0-9]{10,15}$', 'Primary phone number', 17),
('p1e1r1s1-0018-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'primary_email_address', 'Primary Email Address', 'text', 0, 1, 0, 0, NULL, NULL, NULL, NULL, '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$', 'Primary email address', 18),

-- Profile Picture
('p1e1r1s1-0019-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'profile_picture_media_file_id', 'Profile Picture', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Profile picture media file (FK to MEDIA_FILE)', 19);

-- =========================================
-- 3. Entity Relationships: PERSON
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
-- To ENUM_GENDER
('p1e1r1s1-rel1-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'e1d1a1b1-c1d1-4e1f-a1b1-c1d1e1f1a1b1', 'many-to-one', 'person_to_gender', 'gender', 'Person gender reference'),

-- To ENUM_BLOOD_GROUP
('p1e1r1s1-rel2-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'many-to-one', 'person_to_blood_group', 'blood_group', 'Person blood group reference'),

-- To COUNTRY (for nationality)
('p1e1r1s1-rel3-0000-0000-000000000001', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'c1o1u1n1-t1r1-4y11-a111-b111c111d111', 'many-to-one', 'person_to_country', 'nationality', 'Person nationality reference');
