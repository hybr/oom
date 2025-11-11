-- =====================================================================
-- PROCESS_FALLBACK_ASSIGNMENT Entity Metadata - Fallback assignments
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1f1a1l1-l111-4b11-a111-s111s111g111', 'PROCESS_FALLBACK_ASSIGNMENT', 'Process Fallback Assignment',
        'Fallback task assignments when position-based assignment fails', 'PROCESS_FLOW', 'process_fallback_assignment', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1f1a1l1-0001-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1f1a1l1-0002-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('p1f1a1l1-0003-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('p1f1a1l1-0004-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('p1f1a1l1-0005-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('p1f1a1l1-0006-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('p1f1a1l1-0007-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('p1f1a1l1-0008-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, 'FK to ORGANIZATION', 8),
('p1f1a1l1-0009-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'position_id', 'Position ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION', 9),
('p1f1a1l1-0010-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'fallback_person_id', 'Fallback Person ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (fallback assignee)', 10),
('p1f1a1l1-0011-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'priority', 'Priority', 'integer', 1, 0, 0, 0, '1', 'Fallback priority order', 11),
('p1f1a1l1-0012-0000-0000-000000000001', 'p1f1a1l1-l111-4b11-a111-s111s111g111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 12);
