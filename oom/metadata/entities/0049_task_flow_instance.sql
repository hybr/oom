-- =====================================================================
-- TASK_FLOW_INSTANCE Entity Metadata - Running process instances
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('t1f1l1w1-i111-4n11-s111-t111a111n111', 'TASK_FLOW_INSTANCE', 'Task Flow Instance',
        'Running instances of process graphs', 'PROCESS_FLOW', 'task_flow_instance', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('t1f1l1w1-0001-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('t1f1l1w1-0002-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('t1f1l1w1-0003-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('t1f1l1w1-0004-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('t1f1l1w1-0005-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('t1f1l1w1-0006-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('t1f1l1w1-0007-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('t1f1l1w1-0008-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'graph_id', 'Graph ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_GRAPH', 8),
('t1f1l1w1-0009-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, 'FK to ORGANIZATION', 9),
('t1f1l1w1-0010-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'entity_id', 'Entity ID', 'text', 0, 0, 0, 0, NULL, 'Related entity ID (polymorphic)', 10),
('t1f1l1w1-0011-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'entity_type', 'Entity Type', 'text', 0, 0, 0, 0, NULL, 'Related entity type', 11),
('t1f1l1w1-0012-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'instance_code', 'Instance Code', 'text', 1, 1, 0, 1, NULL, 'Unique instance code', 12),
('t1f1l1w1-0013-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'started_at', 'Started At', 'datetime', 1, 0, 0, 0, 'datetime("now")', 'Start time', 13),
('t1f1l1w1-0014-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'completed_at', 'Completed At', 'datetime', 0, 0, 0, 0, NULL, 'Completion time', 14),
('t1f1l1w1-0015-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'RUNNING', 'Instance status', 15),
('t1f1l1w1-0016-0000-0000-000000000001', 't1f1l1w1-i111-4n11-s111-t111a111n111', 'context_data', 'Context Data', 'json', 0, 0, 0, 0, NULL, 'Process context variables', 16);

UPDATE entity_attribute SET enum_values = '["RUNNING","COMPLETED","CANCELLED","SUSPENDED","FAILED"]' WHERE id = 't1f1l1w1-0015-0000-0000-000000000001';
