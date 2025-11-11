-- =====================================================================
-- ORGANIZATION_ADMIN Entity Metadata
-- Organization admin membership and roles
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ORGANIZATION_ADMIN
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
    'o1a1d1m1-i1n1-4111-a111-d111m111i111',
    'ORGANIZATION_ADMIN',
    'Organization Admin',
    'Organization administrative members with role-based permissions (SUPER_ADMIN, ADMIN, MODERATOR)',
    'ORGANIZATION',
    'organization_admin',
    1
);

-- =========================================
-- 2. Entity Attributes: ORGANIZATION_ADMIN
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
('o1a1d1m1-0001-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('o1a1d1m1-0002-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('o1a1d1m1-0003-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('o1a1d1m1-0004-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('o1a1d1m1-0005-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('o1a1d1m1-0006-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('o1a1d1m1-0007-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('o1a1d1m1-0008-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ORGANIZATION', 8),
('o1a1d1m1-0009-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'person_id', 'Person ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Admin person (FK to PERSON)', 9),
('o1a1d1m1-0010-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'appointed_by', 'Appointed By', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Person who appointed this admin (FK to PERSON)', 10),

-- Core Fields
('o1a1d1m1-0011-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'role', 'Role', 'enum_strings', 1, 0, 0, 0, NULL, NULL, NULL, '["SUPER_ADMIN","ADMIN","MODERATOR"]', NULL, 'Admin role level', 11),
('o1a1d1m1-0012-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'appointed_at', 'Appointed At', 'datetime', 1, 0, 0, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'When admin was appointed', 12),
('o1a1d1m1-0013-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether admin is active', 13),
('o1a1d1m1-0014-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Admin notes', 14);

-- =========================================
-- 3. Entity Relationships: ORGANIZATION_ADMIN
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
-- To ORGANIZATION
('o1a1d1m1-rel1-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'many-to-one', 'organization_admin_to_organization', 'organization_id', 'Admin belongs to organization'),

-- To PERSON
('o1a1d1m1-rel2-0000-0000-000000000001', 'o1a1d1m1-i1n1-4111-a111-d111m111i111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'organization_admin_to_person', 'person_id', 'Admin is a person');
