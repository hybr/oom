-- =====================================================================
-- ORGANIZATION Entity Metadata
-- Organization profiles and company information
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ORGANIZATION
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
    'o1r1g1a1-n1z1-4a11-t111-i111o111n111',
    'ORGANIZATION',
    'Organization',
    'Organization profiles including company details, legal structure, and admin information',
    'ORGANIZATION',
    'organization',
    1
);

-- =========================================
-- 2. Entity Attributes: ORGANIZATION
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
('o1r1g1a1-0001-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('o1r1g1a1-0002-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('o1r1g1a1-0003-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('o1r1g1a1-0004-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('o1r1g1a1-0005-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('o1r1g1a1-0006-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('o1r1g1a1-0007-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('o1r1g1a1-0008-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'main_admin_id', 'Main Admin ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization owner (FK to PERSON)', 8),
('o1r1g1a1-0009-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'legal_type_id', 'Legal Type ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'FK to POPULAR_ORGANIZATION_LEGAL_TYPES', 9),
('o1r1g1a1-0010-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'industry_category_id', 'Industry Category ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'FK to POPULAR_INDUSTRY_CATEGORY', 10),
('o1r1g1a1-0011-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'logo_media_file_id', 'Logo Media File ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization logo (FK to MEDIA_FILE)', 11),

-- Core Fields
('o1r1g1a1-0012-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'short_name', 'Short Name', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Organization short name', 12),
('o1r1g1a1-0013-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'tag_line', 'Tag Line', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization tag line or slogan', 13),
('o1r1g1a1-0014-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'registration_number', 'Registration Number', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Official registration number', 14),
('o1r1g1a1-0015-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'tax_id', 'Tax ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Tax identification number', 15),
('o1r1g1a1-0016-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization description', 16),
('o1r1g1a1-0017-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'website', 'Website', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization website URL', 17),
('o1r1g1a1-0018-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'public_email_address', 'Public Email Address', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$', 'Public contact email', 18),
('o1r1g1a1-0019-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'public_phone_address', 'Public Phone Address', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, '^\\+?[0-9]{10,15}$', 'Public contact phone', 19),
('o1r1g1a1-0020-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'founded_date', 'Founded Date', 'date', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Organization founding date', 20),
('o1r1g1a1-0021-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'employee_count', 'Employee Count', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Number of employees', 21),
('o1r1g1a1-0022-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether organization is active', 22);

-- =========================================
-- 3. Entity Relationships: ORGANIZATION
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
-- To PERSON (main admin)
('o1r1g1a1-rel1-0000-0000-000000000001', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'organization_to_main_admin', 'main_admin_id', 'Organization owner');
