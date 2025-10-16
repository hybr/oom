-- =========================================================
-- Entity Permission System
-- =========================================================
-- This script defines the permission system for entities,
-- allowing fine-grained control over who can perform
-- what actions on different entities.
-- =========================================================

-- =========================================================
-- ENUM_ENTITY_PERMISSION_TYPE
-- Defines different permission roles for entities
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'ENUM_ENTITY_PERMISSION_TYPE', 'Entity Permission Type',
 'Defines different permission roles for entities like Request, Approver, Developer, etc.',
 'ORGANIZATION', 'enum_entity_permission_type');

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('d8c1b2a3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'code', 'Permission Code', 'text', 1, 0, 'Short permission identifier (REQUEST, APPROVER, DEVELOPER, TESTER, etc.)', 1),
('e9d0c1b2-f3a4-47b8-9c0d-1e2f3a4b5c6d', 'a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'name', 'Permission Name', 'text', 1, 1, 'Readable permission name', 2);

-- Sample permission types that can be created:
-- REQUEST, FEASIBLE_ANALYST, APPROVER, DESIGNER, DEVELOPER, TESTER, IMPLEMENTOR, SUPPORTER

-- =========================================================
-- ENTITY_PERMISSION_DEFINITION
-- Defines which permissions are applicable for an entity
-- and which positions can perform them
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'ENTITY_PERMISSION_DEFINITION', 'Entity Permission Definition',
 'Defines which permissions are applicable for an entity and which positions can perform them.',
 'ORGANIZATION', 'entity_permission_definition');

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('a1b2c3d4-e5f6-47g8-9h0i-1j2k3l4m5n6o', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'entity_id', 'Entity', 'uuid', 1, 0, 'Reference to entity_definition', 1),
('p2q3r4s5-t6u7-48v9-w0x1-y2z3a4b5c6d7', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'permission_type_id', 'Permission Type', 'uuid', 1, 0, 'Reference to ENUM_ENTITY_PERMISSION_TYPE', 2),
('r1s2t3u4-v5w6-49x7-y8z9-a0b1c2d3e4f5', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'position_id', 'Allowed Position', 'uuid', 1, 0, 'Reference to POPULAR_ORGANIZATION_POSITION', 3),
('s2t3u4v5-w6x7-50y8-z9a0-b1c2d3e4f5g6', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'is_allowed', 'Is Allowed', 'boolean', 1, 0, 'Whether permission is granted to the position', 4);

-- =========================================================
-- ENTITY_PERMISSION_DEFINITION Relationships
-- =========================================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field)
VALUES
('rel-epd-entity', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'entity_definition', 'ManyToOne', 'entity', 'entity_id'),
('rel-epd-permtype', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'ManyToOne', 'permission_type', 'permission_type_id'),
('rel-epd-position', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'ManyToOne', 'position', 'position_id');
