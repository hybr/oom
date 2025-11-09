-- =====================================================================
-- POPULAR_ORGANIZATION_POSITION_EDUCATION Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1p1e1d1-u111-4111-e111-d111u111c111', 'POPULAR_ORGANIZATION_POSITION_EDUCATION', 'Popular Organization Position Education',
        'Education level requirements for positions', 'POPULAR_ORG_STRUCTURE', 'popular_organization_position_education', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1p1e1d1-0001-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1p1e1d1-0002-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Record creation timestamp', 2),
('p1p1e1d1-0003-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Record last update timestamp', 3),
('p1p1e1d1-0004-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('p1p1e1d1-0005-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Optimistic locking version', 5),
('p1p1e1d1-0006-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'User who created', 6),
('p1p1e1d1-0007-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'User who updated', 7),
('p1p1e1d1-0008-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'position_id', 'Position ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION', 8),
('p1p1e1d1-0009-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'education_level_id', 'Education Level ID', 'text', 1, 0, 0, 0, NULL, 'FK to ENUM_EDUCATION_LEVELS', 9),
('p1p1e1d1-0010-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'is_required', 'Is Required', 'boolean', 1, 0, 0, 0, '1', 'Whether education level is required', 10),
('p1p1e1d1-0011-0000-0000-000000000001', 'p1p1e1d1-u111-4111-e111-d111u111c111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Education requirement description', 11);
