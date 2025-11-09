-- =====================================================================
-- WORKSTATION Entity Metadata
-- Individual workstations within organization buildings
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: WORKSTATION
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
    'w1o1r1k1-s1t1-4a11-t111-i111o111n111',
    'WORKSTATION',
    'Workstation',
    'Individual workstations (desks, cubicles, offices) within organization buildings',
    'ORGANIZATION',
    'workstation',
    1
);

-- =========================================
-- 2. Entity Attributes: WORKSTATION
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
('w1o1r1k1-0001-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('w1o1r1k1-0002-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('w1o1r1k1-0003-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('w1o1r1k1-0004-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('w1o1r1k1-0005-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('w1o1r1k1-0006-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('w1o1r1k1-0007-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('w1o1r1k1-0008-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'organization_building_id', 'Organization Building ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reference to ORGANIZATION_BUILDING', 8),

-- Core Fields
('w1o1r1k1-0009-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'workstation_code', 'Workstation Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique workstation code', 9),
('w1o1r1k1-0010-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'floor_number', 'Floor Number', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Floor number', 10),
('w1o1r1k1-0011-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'area_section', 'Area Section', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Section or zone within floor', 11),
('w1o1r1k1-0012-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'workstation_type', 'Workstation Type', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Type (desk, cubicle, office, etc.)', 12),
('w1o1r1k1-0013-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'capacity', 'Capacity', 'integer', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Seating capacity', 13),
('w1o1r1k1-0014-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'amenities', 'Amenities', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Workstation amenities', 14);

-- =========================================
-- 3. Entity Relationships: WORKSTATION
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
-- To ORGANIZATION_BUILDING
('w1o1r1k1-rel1-0000-0000-000000000001', 'w1o1r1k1-s1t1-4a11-t111-i111o111n111', 'o1b1l1d1-g111-4111-b111-l111d111g111', 'many-to-one', 'workstation_to_organization_building', 'organization_building_id', 'Workstation belongs to building');
