-- =====================================================================
-- POPULAR_ORGANIZATION_DESIGNATION Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1d1s1g1-n111-4111-d111-e111s111g111', 'POPULAR_ORGANIZATION_DESIGNATION', 'Popular Organization Designation',
        'Generic designations with seniority levels (Staff, Manager, Director, CEO, etc.)', 'POPULAR_ORG_STRUCTURE', 'popular_organization_designation', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1d1s1g1-0001-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1d1s1g1-0002-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Record creation timestamp', 2),
('p1d1s1g1-0003-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Record last update timestamp', 3),
('p1d1s1g1-0004-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('p1d1s1g1-0005-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Optimistic locking version', 5),
('p1d1s1g1-0006-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'User who created', 6),
('p1d1s1g1-0007-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'User who updated', 7),
('p1d1s1g1-0008-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, 'Designation name', 8),
('p1d1s1g1-0009-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Designation code', 9),
('p1d1s1g1-0010-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'level', 'Seniority Level', 'enum_strings', 0, 0, 0, 0, NULL, 'Seniority level', 10),
('p1d1s1g1-0011-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Designation description', 11),
('p1d1s1g1-0012-0000-0000-000000000001', 'p1d1s1g1-n111-4111-d111-e111s111g111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 12);

UPDATE entity_attribute SET enum_values = '["Entry","Junior","Mid","Senior","Lead","Principal","Executive"]'
WHERE id = 'p1d1s1g1-0010-0000-0000-000000000001';
