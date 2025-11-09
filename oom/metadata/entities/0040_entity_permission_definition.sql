-- =====================================================================
-- ENTITY_PERMISSION_DEFINITION Entity Metadata - Position-based permission definitions
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('e1p1d1f1-n111-4111-p111-e111r111m111', 'ENTITY_PERMISSION_DEFINITION', 'Entity Permission Definition',
        'Defines which positions have which permissions on which entities', 'PERMISSIONS', 'entity_permission_definition', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1p1d1f1-0001-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1p1d1f1-0002-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', 'entity_id', 'Entity ID', 'text', 1, 0, 0, 0, NULL, 'FK to ENTITY_DEFINITION', 2),
('e1p1d1f1-0003-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', 'permission_type_id', 'Permission Type ID', 'text', 1, 0, 0, 0, NULL, 'FK to ENUM_ENTITY_PERMISSION_TYPE', 3),
('e1p1d1f1-0004-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', 'position_id', 'Position ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION', 4),
('e1p1d1f1-0005-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', 'is_allowed', 'Is Allowed', 'boolean', 1, 0, 0, 0, '1', 'Permission allowed (1) or denied (0)', 5);

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('e1p1d1f1-r001-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', (SELECT id FROM entity_definition WHERE code = 'ENTITY_DEFINITION'), 'MANY_TO_ONE', 'Entity Permission to Entity Definition', 'entity_id', 'Permissions defined for entity'),
('e1p1d1f1-r002-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', 'e1p1t1y1-p111-4e11-r111-m111t111y111', 'MANY_TO_ONE', 'Permission Definition to Permission Type', 'permission_type_id', 'Type of permission'),
('e1p1d1f1-r003-0000-0000-000000000001', 'e1p1d1f1-n111-4111-p111-e111r111m111', (SELECT id FROM entity_definition WHERE code = 'POPULAR_ORGANIZATION_POSITION'), 'MANY_TO_ONE', 'Permission Definition to Position', 'position_id', 'Position granted permission');
