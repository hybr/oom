-- =====================================================================
-- PROCESS_NODE Entity Metadata - Process steps
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1n1o1d1-e111-4111-n111-o111d111e111', 'PROCESS_NODE', 'Process Node',
        'Process steps/nodes (START, TASK, DECISION, FORK, JOIN, END)', 'PROCESS_FLOW', 'process_node', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1n1o1d1-0001-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1n1o1d1-0002-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('p1n1o1d1-0003-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('p1n1o1d1-0004-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('p1n1o1d1-0005-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('p1n1o1d1-0006-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('p1n1o1d1-0007-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('p1n1o1d1-0008-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'graph_id', 'Graph ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_GRAPH', 8),
('p1n1o1d1-0009-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'position_id', 'Position ID', 'text', 0, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION (for TASK nodes)', 9),
('p1n1o1d1-0010-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'permission_type_id', 'Permission Type ID', 'text', 0, 0, 0, 0, NULL, 'FK to ENUM_ENTITY_PERMISSION_TYPE', 10),
('p1n1o1d1-0011-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'escalate_to_position_id', 'Escalate To Position ID', 'text', 0, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION (escalation)', 11),
('p1n1o1d1-0012-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'node_code', 'Node Code', 'text', 1, 0, 0, 1, NULL, 'Node code', 12),
('p1n1o1d1-0013-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'node_name', 'Node Name', 'text', 1, 0, 0, 1, NULL, 'Node name', 13),
('p1n1o1d1-0014-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'node_type', 'Node Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Node type', 14),
('p1n1o1d1-0015-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Node description', 15),
('p1n1o1d1-0016-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'sla_hours', 'SLA Hours', 'integer', 0, 0, 0, 0, NULL, 'Service Level Agreement hours', 16),
('p1n1o1d1-0017-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'estimated_duration_hours', 'Estimated Duration Hours', 'integer', 0, 0, 0, 0, NULL, 'Estimated duration', 17),
('p1n1o1d1-0018-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'display_x', 'Display X', 'integer', 0, 0, 0, 0, NULL, 'Visual X position', 18),
('p1n1o1d1-0019-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'display_y', 'Display Y', 'integer', 0, 0, 0, 0, NULL, 'Visual Y position', 19),
('p1n1o1d1-0020-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'form_entities', 'Form Entities', 'json', 0, 0, 0, 0, NULL, 'Dynamic form configuration', 20),
('p1n1o1d1-0021-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'instructions', 'Instructions', 'text', 0, 0, 0, 0, NULL, 'Task instructions', 21),
('p1n1o1d1-0022-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'notify_on_assignment', 'Notify On Assignment', 'boolean', 0, 0, 0, 0, '1', 'Send notification on assignment', 22),
('p1n1o1d1-0023-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'notify_on_due', 'Notify On Due', 'boolean', 0, 0, 0, 0, '1', 'Send notification when due', 23),
('p1n1o1d1-0024-0000-0000-000000000001', 'p1n1o1d1-e111-4111-n111-o111d111e111', 'escalate_after_hours', 'Escalate After Hours', 'integer', 0, 0, 0, 0, NULL, 'Escalation threshold hours', 24);

UPDATE entity_attribute SET enum_values = '["START","TASK","DECISION","FORK","JOIN","END"]' WHERE id = 'p1n1o1d1-0014-0000-0000-000000000001';
