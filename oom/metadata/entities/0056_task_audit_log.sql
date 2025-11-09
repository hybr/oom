-- =====================================================================
-- TASK_AUDIT_LOG Entity Metadata - Immutable audit trail
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('t1a1u1d1-i111-4t11-l111-o111g111a111', 'TASK_AUDIT_LOG', 'Task Audit Log',
        'Immutable audit trail for all task and flow actions', 'PROCESS_FLOW', 'task_audit_log', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('t1a1u1d1-0001-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('t1a1u1d1-0002-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('t1a1u1d1-0003-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'flow_instance_id', 'Flow Instance ID', 'text', 0, 0, 0, 0, NULL, 'FK to TASK_FLOW_INSTANCE', 3),
('t1a1u1d1-0004-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'task_instance_id', 'Task Instance ID', 'text', 0, 0, 0, 0, NULL, 'FK to TASK_INSTANCE', 4),
('t1a1u1d1-0005-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'actor_id', 'Actor ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (who performed action)', 5),
('t1a1u1d1-0006-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'action', 'Action', 'enum_strings', 1, 0, 0, 0, NULL, 'Action performed', 6),
('t1a1u1d1-0007-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Action description', 7),
('t1a1u1d1-0008-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'previous_value', 'Previous Value', 'text', 0, 0, 0, 0, NULL, 'Previous state', 8),
('t1a1u1d1-0009-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'new_value', 'New Value', 'text', 0, 0, 0, 0, NULL, 'New state', 9),
('t1a1u1d1-0010-0000-0000-000000000001', 't1a1u1d1-i111-4t11-l111-o111g111a111', 'metadata', 'Metadata', 'json', 0, 0, 0, 0, NULL, 'Additional metadata', 10);

UPDATE entity_attribute SET enum_values = '["CREATED","ASSIGNED","STARTED","COMPLETED","CANCELLED","REASSIGNED","COMMENTED","STATUS_CHANGED"]' WHERE id = 't1a1u1d1-0006-0000-0000-000000000001';
