-- =====================================================================
-- ITEM QUALITY REVIEW WORKFLOW - Review items before going live
-- Content moderation and compliance checking
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO process_graph (
    id, code, name, description, entity_id, version_number,
    is_active, is_published, category, created_at
) VALUES (
    'QUAL0000-REVW-4111-W111-000000000001',
    'ITEM_QUALITY_REVIEW_WORKFLOW',
    'Product Quality Review Process',
    'Review and approve items before marketplace publication',
    'i1t1e1m1-0000-4111-a111-111111111111',  -- ITEM entity
    1, 1, 1, 'MARKETPLACE_COMMERCE', datetime('now')
);

-- Nodes
INSERT OR IGNORE INTO process_node (id, graph_id, node_code, node_name, node_type, description, position_id, permission_type_id, sla_hours, display_x, display_y, created_at) VALUES
('QUAL0001-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'START', 'Start', 'START', 'Item submitted for review', NULL, NULL, NULL, 100, 100, datetime('now')),
('QUAL0002-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'ASSIGN_REVIEWER', 'Assign Reviewer', 'TASK', 'Assign item to content reviewer', NULL, NULL, 2, 200, 100, datetime('now')),
('QUAL0003-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'CHECK_COMPLIANCE', 'Check Content Compliance', 'TASK', 'Review for prohibited content', NULL, NULL, 12, 300, 100, datetime('now')),
('QUAL0004-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'VERIFY_INFO', 'Verify Product Information', 'TASK', 'Check accuracy of product details', NULL, NULL, 12, 400, 100, datetime('now')),
('QUAL0005-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'REVIEW_IMAGES', 'Review Images & Descriptions', 'TASK', 'Check image quality and descriptions', NULL, NULL, 8, 500, 100, datetime('now')),
('QUAL0006-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'MAKE_DECISION', 'Approve or Request Changes', 'TASK', 'Final review decision', NULL, NULL, 4, 600, 100, datetime('now')),
('QUAL0007-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'PUBLISH_ITEM', 'Publish to Marketplace', 'TASK', 'Make item live on marketplace', NULL, NULL, 2, 700, 100, datetime('now')),
('QUAL0008-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'END', 'End', 'END', 'Review complete', NULL, NULL, NULL, 800, 100, datetime('now'));

-- Edges
INSERT OR IGNORE INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, edge_order, completion_action, created_at) VALUES
('QUALE001-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0001-REVW-4111-W111-000000000001', 'QUAL0002-REVW-4111-W111-000000000001', 'Begin', 1, NULL, datetime('now')),
('QUALE002-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0002-REVW-4111-W111-000000000001', 'QUAL0003-REVW-4111-W111-000000000001', 'Assigned', 1, 'COMPLETE', datetime('now')),
('QUALE003-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0003-REVW-4111-W111-000000000001', 'QUAL0004-REVW-4111-W111-000000000001', 'Compliant', 1, 'COMPLETE', datetime('now')),
('QUALE004-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0004-REVW-4111-W111-000000000001', 'QUAL0005-REVW-4111-W111-000000000001', 'Info Verified', 1, 'COMPLETE', datetime('now')),
('QUALE005-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0005-REVW-4111-W111-000000000001', 'QUAL0006-REVW-4111-W111-000000000001', 'Images Reviewed', 1, 'COMPLETE', datetime('now')),
('QUALE006-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0006-REVW-4111-W111-000000000001', 'QUAL0007-REVW-4111-W111-000000000001', 'Approved', 1, 'APPROVE', datetime('now')),
('QUALE007-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0006-REVW-4111-W111-000000000001', 'QUAL0008-REVW-4111-W111-000000000001', 'Changes Requested', 2, 'REJECT', datetime('now')),
('QUALE008-REVW-4111-W111-000000000001', 'QUAL0000-REVW-4111-W111-000000000001', 'QUAL0007-REVW-4111-W111-000000000001', 'QUAL0008-REVW-4111-W111-000000000001', 'Published', 1, 'COMPLETE', datetime('now'));
