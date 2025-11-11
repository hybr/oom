-- =====================================================================
-- PROCESS_EDGE Entity Metadata - Transitions between nodes
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1e1d1g1-e111-4111-e111-d111g111e111', 'PROCESS_EDGE', 'Process Edge',
        'Transitions/connections between process nodes', 'PROCESS_FLOW', 'process_edge', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1e1d1g1-0001-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1e1d1g1-0002-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('p1e1d1g1-0003-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('p1e1d1g1-0004-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('p1e1d1g1-0005-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('p1e1d1g1-0006-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('p1e1d1g1-0007-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('p1e1d1g1-0008-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'graph_id', 'Graph ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_GRAPH', 8),
('p1e1d1g1-0009-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'from_node_id', 'From Node ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_NODE (source)', 9),
('p1e1d1g1-0010-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'to_node_id', 'To Node ID', 'text', 1, 0, 0, 0, NULL, 'FK to PROCESS_NODE (target)', 10),
('p1e1d1g1-0011-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'edge_code', 'Edge Code', 'text', 1, 0, 0, 1, NULL, 'Edge code', 11),
('p1e1d1g1-0012-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'edge_label', 'Edge Label', 'text', 0, 0, 0, 1, NULL, 'Edge label', 12),
('p1e1d1g1-0013-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'sequence_number', 'Sequence Number', 'integer', 0, 0, 0, 0, NULL, 'Sequence order', 13),
('p1e1d1g1-0014-0000-0000-000000000001', 'p1e1d1g1-e111-4111-e111-d111g111e111', 'is_default', 'Is Default', 'boolean', 0, 0, 0, 0, '0', 'Default path', 14);
