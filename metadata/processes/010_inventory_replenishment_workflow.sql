-- =====================================================================
-- INVENTORY REPLENISHMENT WORKFLOW - Automated inventory monitoring
-- Monitor stock levels and trigger replenishment
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO process_graph (
    id, code, name, description, entity_id, version_number,
    is_active, is_published, category, created_at
) VALUES (
    'INVT0000-REPL-4111-W111-000000000001',
    'INVENTORY_REPLENISHMENT_WORKFLOW',
    'Inventory Replenishment Process',
    'Automated inventory monitoring and replenishment',
    'f1g1o1o1-d1s1-4i1n-v111-111111111111',  -- FINISHED_GOODS_INVENTORY entity
    1, 1, 1, 'MARKETPLACE_COMMERCE', datetime('now')
);

-- Nodes
INSERT OR IGNORE INTO process_node (id, graph_id, node_code, node_name, node_type, description, position_id, permission_type_id, sla_hours, display_x, display_y, created_at) VALUES
('INVT0001-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'START', 'Start', 'START', 'Monitor inventory', NULL, NULL, NULL, 100, 100, datetime('now')),
('INVT0002-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'MONITOR_LEVELS', 'Monitor Inventory Levels', 'TASK', 'Check inventory against thresholds', NULL, NULL, 24, 200, 100, datetime('now')),
('INVT0003-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'TRIGGER_ALERT', 'Trigger Low Stock Alert', 'TASK', 'Alert when below threshold', NULL, NULL, 1, 300, 100, datetime('now')),
('INVT0004-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'NOTIFY_VENDOR', 'Send Alert to Vendor', 'TASK', 'Notify vendor of low stock', NULL, NULL, 2, 400, 100, datetime('now')),
('INVT0005-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'VENDOR_CONFIRMATION', 'Vendor Confirms Restock', 'TASK', 'Vendor acknowledges and confirms', NULL, NULL, 48, 500, 100, datetime('now')),
('INVT0006-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'UPDATE_ETA', 'Update Expected Restock Date', 'TASK', 'Set expected restock date', NULL, NULL, 4, 600, 100, datetime('now')),
('INVT0007-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'RECEIVE_INVENTORY', 'Receive Inventory', 'TASK', 'Log inventory receipt', NULL, NULL, 336, 700, 100, datetime('now')),
('INVT0008-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'UPDATE_COUNTS', 'Update Inventory Counts', 'TASK', 'Update system inventory', NULL, NULL, 2, 800, 100, datetime('now')),
('INVT0009-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'NOTIFY_RESTOCKED', 'Notify Items Back in Stock', 'TASK', 'Alert customers waiting for item', NULL, NULL, 1, 900, 100, datetime('now')),
('INVT0010-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'END', 'End', 'END', 'Replenishment complete', NULL, NULL, NULL, 1000, 100, datetime('now'));

-- Edges
INSERT OR IGNORE INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, edge_order, completion_action, created_at) VALUES
('INVTE001-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0001-REPL-4111-W111-000000000001', 'INVT0002-REPL-4111-W111-000000000001', 'Begin', 1, NULL, datetime('now')),
('INVTE002-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0002-REPL-4111-W111-000000000001', 'INVT0003-REPL-4111-W111-000000000001', 'Low Stock Detected', 1, 'COMPLETE', datetime('now')),
('INVTE003-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0003-REPL-4111-W111-000000000001', 'INVT0004-REPL-4111-W111-000000000001', 'Alert Triggered', 1, 'COMPLETE', datetime('now')),
('INVTE004-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0004-REPL-4111-W111-000000000001', 'INVT0005-REPL-4111-W111-000000000001', 'Vendor Notified', 1, 'COMPLETE', datetime('now')),
('INVTE005-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0005-REPL-4111-W111-000000000001', 'INVT0006-REPL-4111-W111-000000000001', 'Confirmed', 1, 'COMPLETE', datetime('now')),
('INVTE006-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0006-REPL-4111-W111-000000000001', 'INVT0007-REPL-4111-W111-000000000001', 'ETA Set', 1, 'COMPLETE', datetime('now')),
('INVTE007-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0007-REPL-4111-W111-000000000001', 'INVT0008-REPL-4111-W111-000000000001', 'Inventory Received', 1, 'COMPLETE', datetime('now')),
('INVTE008-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0008-REPL-4111-W111-000000000001', 'INVT0009-REPL-4111-W111-000000000001', 'Counts Updated', 1, 'COMPLETE', datetime('now')),
('INVTE009-REPL-4111-W111-000000000001', 'INVT0000-REPL-4111-W111-000000000001', 'INVT0009-REPL-4111-W111-000000000001', 'INVT0010-REPL-4111-W111-000000000001', 'Customers Notified', 1, 'COMPLETE', datetime('now'));
