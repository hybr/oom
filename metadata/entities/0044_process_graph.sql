-- =====================================================================
-- PROCESS_GRAPH Entity Metadata - Process templates
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1g1r1a1-p111-4h11-g111-r111a111p111', 'PROCESS_GRAPH', 'Process Graph',
        'Process template definitions with versioning', 'PROCESS_FLOW', 'process_graph', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1g1r1a1-0001-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1g1r1a1-0002-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('p1g1r1a1-0003-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('p1g1r1a1-0004-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('p1g1r1a1-0005-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('p1g1r1a1-0006-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('p1g1r1a1-0007-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('p1g1r1a1-0008-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'entity_id', 'Entity ID', 'text', 0, 0, 0, 0, NULL, 'FK to ENTITY_DEFINITION (optional linked entity)', 8),
('p1g1r1a1-0009-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Process code (e.g. VACANCY_CREATION)', 9),
('p1g1r1a1-0010-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, 'Process name', 10),
('p1g1r1a1-0011-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Process description', 11),
('p1g1r1a1-0012-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'version_number', 'Version Number', 'integer', 1, 0, 0, 0, '1', 'Process version', 12),
('p1g1r1a1-0013-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 13),
('p1g1r1a1-0014-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'is_published', 'Is Published', 'boolean', 1, 0, 0, 0, '0', 'Published status', 14),
('p1g1r1a1-0015-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'category', 'Category', 'text', 0, 0, 0, 0, NULL, 'Process category', 15),
('p1g1r1a1-0016-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'published_at', 'Published At', 'datetime', 0, 0, 0, 0, NULL, 'Publication date', 16),
('p1g1r1a1-0017-0000-0000-000000000001', 'p1g1r1a1-p111-4h11-g111-r111a111p111', 'published_by', 'Published By', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (publisher)', 17);
