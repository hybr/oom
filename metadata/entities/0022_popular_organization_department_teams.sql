-- =====================================================================
-- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1t1e1a1-m111-4111-t111-e111a111m111', 'POPULAR_ORGANIZATION_DEPARTMENT_TEAMS', 'Popular Organization Department Teams',
        'Teams within departments (Backend Team, Frontend Team, etc.)', 'POPULAR_ORG_STRUCTURE', 'popular_organization_department_teams', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1t1e1a1-0001-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1t1e1a1-0002-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Record creation timestamp', 2),
('p1t1e1a1-0003-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Record last update timestamp', 3),
('p1t1e1a1-0004-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('p1t1e1a1-0005-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Optimistic locking version', 5),
('p1t1e1a1-0006-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'User who created', 6),
('p1t1e1a1-0007-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'User who updated', 7),
('p1t1e1a1-0008-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'department_id', 'Department ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_DEPARTMENTS', 8),
('p1t1e1a1-0009-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, 'Team name', 9),
('p1t1e1a1-0010-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Team code', 10),
('p1t1e1a1-0011-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Team description', 11),
('p1t1e1a1-0012-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'objectives', 'Objectives', 'text', 0, 0, 0, 0, NULL, 'Team objectives', 12),
('p1t1e1a1-0013-0000-0000-000000000001', 'p1t1e1a1-m111-4111-t111-e111a111m111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 13);
