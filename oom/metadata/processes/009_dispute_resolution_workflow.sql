-- =====================================================================
-- CUSTOMER DISPUTE RESOLUTION - Handle complaints and disputes
-- From complaint filing to resolution
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO process_graph (
    id, code, name, description, entity_id, version_number,
    is_active, is_published, category, created_at
) VALUES (
    'DISP0000-RSLT-4111-W111-000000000001',
    'DISPUTE_RESOLUTION_WORKFLOW',
    'Customer Dispute Resolution',
    'Handle customer complaints and disputes',
    'o1r1d1e1-r000-4111-a111-111111111111',  -- ORDER entity
    1, 1, 1, 'MARKETPLACE_COMMERCE', datetime('now')
);

-- Nodes
INSERT OR IGNORE INTO process_node (id, graph_id, node_code, node_name, node_type, description, position_id, permission_type_id, sla_hours, display_x, display_y, created_at) VALUES
('DISP0001-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'START', 'Start', 'START', 'Dispute filed', NULL, NULL, NULL, 100, 100, datetime('now')),
('DISP0002-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'FILE_DISPUTE', 'File Dispute', 'TASK', 'Customer files dispute', NULL, NULL, 48, 200, 100, datetime('now')),
('DISP0003-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'ASSIGN_CASE', 'Assign to Support Agent', 'TASK', 'Assign case to support agent', NULL, NULL, 4, 300, 100, datetime('now')),
('DISP0004-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'CONTACT_VENDOR', 'Contact Vendor for Response', 'TASK', 'Get vendor side of story', NULL, NULL, 48, 400, 100, datetime('now')),
('DISP0005-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'REVIEW_EVIDENCE', 'Review Evidence', 'TASK', 'Review evidence from both parties', NULL, NULL, 24, 500, 100, datetime('now')),
('DISP0006-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'MEDIATION', 'Attempt Mediation', 'TASK', 'Try to mediate resolution', NULL, NULL, 72, 600, 100, datetime('now')),
('DISP0007-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'FINAL_DECISION', 'Make Final Decision', 'TASK', 'Issue final decision on dispute', NULL, NULL, 24, 700, 100, datetime('now')),
('DISP0008-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'EXECUTE_RESOLUTION', 'Execute Resolution', 'TASK', 'Process refund/replacement', NULL, NULL, 48, 800, 100, datetime('now')),
('DISP0009-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'CLOSE_CASE', 'Close Case & Notify', 'TASK', 'Close case and notify parties', NULL, NULL, 4, 900, 100, datetime('now')),
('DISP0010-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'END', 'End', 'END', 'Dispute resolved', NULL, NULL, NULL, 1000, 100, datetime('now'));

-- Edges
INSERT OR IGNORE INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, edge_order, completion_action, created_at) VALUES
('DISPE001-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0001-RSLT-4111-W111-000000000001', 'DISP0002-RSLT-4111-W111-000000000001', 'Begin', 1, NULL, datetime('now')),
('DISPE002-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0002-RSLT-4111-W111-000000000001', 'DISP0003-RSLT-4111-W111-000000000001', 'Filed', 1, 'COMPLETE', datetime('now')),
('DISPE003-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0003-RSLT-4111-W111-000000000001', 'DISP0004-RSLT-4111-W111-000000000001', 'Assigned', 1, 'COMPLETE', datetime('now')),
('DISPE004-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0004-RSLT-4111-W111-000000000001', 'DISP0005-RSLT-4111-W111-000000000001', 'Vendor Responded', 1, 'COMPLETE', datetime('now')),
('DISPE005-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0005-RSLT-4111-W111-000000000001', 'DISP0006-RSLT-4111-W111-000000000001', 'Evidence Reviewed', 1, 'COMPLETE', datetime('now')),
('DISPE006-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0006-RSLT-4111-W111-000000000001', 'DISP0007-RSLT-4111-W111-000000000001', 'Mediation Complete', 1, 'COMPLETE', datetime('now')),
('DISPE007-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0007-RSLT-4111-W111-000000000001', 'DISP0008-RSLT-4111-W111-000000000001', 'Decision Made', 1, 'COMPLETE', datetime('now')),
('DISPE008-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0008-RSLT-4111-W111-000000000001', 'DISP0009-RSLT-4111-W111-000000000001', 'Resolution Executed', 1, 'COMPLETE', datetime('now')),
('DISPE009-RSLT-4111-W111-000000000001', 'DISP0000-RSLT-4111-W111-000000000001', 'DISP0009-RSLT-4111-W111-000000000001', 'DISP0010-RSLT-4111-W111-000000000001', 'Case Closed', 1, 'COMPLETE', datetime('now'));
