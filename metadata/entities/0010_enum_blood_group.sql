-- =====================================================================
-- ENUM_BLOOD_GROUP Entity Metadata
-- Enumeration of blood group types
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ENUM_BLOOD_GROUP
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
    'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2',
    'ENUM_BLOOD_GROUP',
    'Blood Group',
    'Enumeration of blood group types (A+, A-, B+, B-, O+, O-, AB+, AB-)',
    'PERSON_IDENTITY',
    'enum_blood_group',
    1
);

-- =========================================
-- 2. Entity Attributes: ENUM_BLOOD_GROUP
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
('e1d2a2b2-0001-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('e1d2a2b2-0002-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('e1d2a2b2-0003-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('e1d2a2b2-0004-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('e1d2a2b2-0005-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('e1d2a2b2-0006-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('e1d2a2b2-0007-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Core Fields
('e1d2a2b2-0008-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique blood group code', 8),
('e1d2a2b2-0009-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Blood group display name', 9),
('e1d2a2b2-0010-0000-0000-000000000001', 'e1d2a2b2-c2d2-4e2f-a2b2-c2d2e2f2a2b2', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Blood group description', 10);
