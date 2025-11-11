-- =====================================================================
-- ORGANIZATION_BUILDING Entity Metadata
-- Organization buildings and facilities
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: ORGANIZATION_BUILDING
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
    'o1b1l1d1-g111-4111-b111-l111d111g111',
    'ORGANIZATION_BUILDING',
    'Organization Building',
    'Organization buildings and facilities with floor information and amenities',
    'ORGANIZATION',
    'organization_building',
    1
);

-- =========================================
-- 2. Entity Attributes: ORGANIZATION_BUILDING
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
('o1b1l1d1-0001-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('o1b1l1d1-0002-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('o1b1l1d1-0003-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('o1b1l1d1-0004-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('o1b1l1d1-0005-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('o1b1l1d1-0006-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('o1b1l1d1-0007-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('o1b1l1d1-0008-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ORGANIZATION', 8),
('o1b1l1d1-0009-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'branch_id', 'Branch ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Optional branch (FK to ORGANIZATION_BRANCH)', 9),
('o1b1l1d1-0010-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'postal_address_id', 'Postal Address ID', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Building address (FK to POSTAL_ADDRESS)', 10),

-- Core Fields
('o1b1l1d1-0011-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Building name', 11),
('o1b1l1d1-0012-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Building code', 12),
('o1b1l1d1-0013-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'building_type', 'Building Type', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Building type (office, warehouse, etc.)', 13),
('o1b1l1d1-0014-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'total_floors', 'Total Floors', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Number of floors in building', 14),
('o1b1l1d1-0015-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'facilities', 'Facilities', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Building facilities and amenities', 15);

-- =========================================
-- 3. Entity Relationships: ORGANIZATION_BUILDING
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
('o1b1l1d1-rel1-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'o1r1g1a1-n1z1-4a11-t111-i111o111n111', 'many-to-one', 'organization_building_to_organization', 'organization_id', 'Building belongs to organization'),

-- To ORGANIZATION_BRANCH
('o1b1l1d1-rel2-0000-0000-000000000001', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'o1b1r1a1-n1c1-4h11-b111-r111a111n111', 'many-to-one', 'organization_building_to_branch', 'branch_id', 'Building optionally belongs to branch');
