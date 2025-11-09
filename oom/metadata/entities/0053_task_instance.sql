-- =====================================================================
-- TASK_INSTANCE Entity Metadata - Active tasks
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('t1i1n1s1-t111-4111-t111-a111s111k111', 'TASK_INSTANCE', 'Task Instance',
        'Individual task instances within process flow instances', 'PROCESS_FLOW', 'task_instance', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('t1i1n1s1-0001-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('t1i1n1s1-0002-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('t1i1n1s1-0003-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('t1i1n1s1-0004-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('t1i1n1s1-0005-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('t1i1n1s1-0006-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('t1i1n1s1-0007-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('t1i1n1s1-0008-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'flow_instance_id', 'Flow Instance ID', 'text', 1, 0, 0, 0, NULL, 'FK to TASK_FLOW_INSTANCE', 8),
('t1i1n1s1-0009-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'node_id', 'Node ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_NODE', 9),
('t1i1n1s1-0010-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'assigned_to', 'Assigned To', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (assignee)', 10),
('t1i1n1s1-0011-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'task_code', 'Task Code', 'text', 1, 1, 0, 1, NULL, 'Unique task code', 11),
('t1i1n1s1-0012-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'assigned_at', 'Assigned At', 'datetime', 0, 0, 0, 0, NULL, 'Assignment time', 12),
('t1i1n1s1-0013-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'started_at', 'Started At', 'datetime', 0, 0, 0, 0, NULL, 'Start time', 13),
('t1i1n1s1-0014-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'completed_at', 'Completed At', 'datetime', 0, 0, 0, 0, NULL, 'Completion time', 14),
('t1i1n1s1-0015-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'due_at', 'Due At', 'datetime', 0, 0, 0, 0, NULL, 'Due date', 15),
('t1i1n1s1-0016-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'PENDING', 'Task status', 16),
('t1i1n1s1-0017-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'comments', 'Comments', 'text', 0, 0, 0, 0, NULL, 'Task comments', 17),
('t1i1n1s1-0018-0000-0000-000000000001', 't1i1n1s1-t111-4111-t111-a111s111k111', 'result', 'Result', 'text', 0, 0, 0, 0, NULL, 'Task result/outcome', 18);

UPDATE entity_attribute SET enum_values = '["PENDING","IN_PROGRESS","COMPLETED","CANCELLED","REASSIGNED"]' WHERE id = 't1i1n1s1-0016-0000-0000-000000000001';
