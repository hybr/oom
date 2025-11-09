-- =====================================================================
-- PROCESS_EDGE_CONDITION Entity Metadata - Edge routing logic
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1e1c1n1-d111-4111-c111-o111n111d111', 'PROCESS_EDGE_CONDITION', 'Process Edge Condition',
        'Structured conditions for edge routing decisions', 'PROCESS_FLOW', 'process_edge_condition', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1e1c1n1-0001-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1e1c1n1-0002-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('p1e1c1n1-0003-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('p1e1c1n1-0004-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('p1e1c1n1-0005-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('p1e1c1n1-0006-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('p1e1c1n1-0007-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('p1e1c1n1-0008-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'edge_id', 'Edge ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_EDGE', 8),
('p1e1c1n1-0009-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'field_name', 'Field Name', 'text', 1, 0, 0, 0, NULL, 'Field to evaluate', 9),
('p1e1c1n1-0010-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'operator', 'Operator', 'enum_strings', 1, 0, 0, 0, NULL, 'Comparison operator', 10),
('p1e1c1n1-0011-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'value', 'Value', 'text', 1, 0, 0, 0, NULL, 'Comparison value', 11),
('p1e1c1n1-0012-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'logical_operator', 'Logical Operator', 'enum_strings', 0, 0, 0, 0, NULL, 'AND/OR for multiple conditions', 12),
('p1e1c1n1-0013-0000-0000-000000000001', 'p1e1c1n1-d111-4111-c111-o111n111d111', 'sequence_number', 'Sequence Number', 'integer', 1, 0, 0, 0, NULL, 'Condition order', 13);

UPDATE entity_attribute SET enum_values = '["EQUALS","NOT_EQUALS","GREATER_THAN","LESS_THAN","CONTAINS","IN"]' WHERE id = 'p1e1c1n1-0010-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["AND","OR"]' WHERE id = 'p1e1c1n1-0012-0000-0000-000000000001';
