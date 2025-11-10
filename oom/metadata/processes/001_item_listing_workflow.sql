-- =====================================================================
-- ITEM LISTING WORKFLOW - Product/Service Listing Process
-- Guides vendors through listing new items with review and approval
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. PROCESS GRAPH: Item Listing Workflow
-- =========================================
INSERT OR IGNORE INTO process_graph (
    id,
    code,
    name,
    description,
    entity_id,
    version_number,
    is_active,
    is_published,
    category,
    created_at
) VALUES (
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM_LISTING_WORKFLOW',
    'Product/Service Listing Process',
    'Guide vendors through listing new items with review and approval',
    'i1t1e1m1-0000-4111-a111-111111111111',  -- ITEM entity
    1,
    1,
    1,
    'MARKETPLACE_COMMERCE',
    datetime('now')
);

-- =========================================
-- 2. PROCESS NODES
-- =========================================

-- START Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, display_x, display_y, created_at
) VALUES (
    'ITEM0001-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'START',
    'Start',
    'START',
    'Process start',
    100, 100,
    datetime('now')
);

-- DRAFT_ITEM Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours, escalate_after_hours,
    form_entities,
    display_x, display_y, created_at
) VALUES (
    'ITEM0002-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'DRAFT_ITEM',
    'Draft Item Details',
    'TASK',
    'Vendor creates item listing with details, images, and pricing',
    NULL,  -- Vendor/Seller position
    NULL,  -- REQUEST permission
    72,  -- 3 days
    96,
    '["ITEM_VARIANT", "ITEM_IMAGE"]',
    200, 100,
    datetime('now')
);

-- QUALITY_REVIEW Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours, escalate_after_hours,
    display_x, display_y, created_at
) VALUES (
    'ITEM0003-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'QUALITY_REVIEW',
    'Quality Review',
    'TASK',
    'Review item content, images, and description for compliance',
    NULL,  -- Content Moderator position
    NULL,  -- APPROVER permission
    24,
    48,
    300, 100,
    datetime('now')
);

-- PRICING_CHECK Decision Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, display_x, display_y, created_at
) VALUES (
    'ITEM0004-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'PRICING_CHECK',
    'Pricing Validation Check',
    'DECISION',
    'Check if pricing requires additional approval',
    400, 100,
    datetime('now')
);

-- PRICING_APPROVAL Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours, escalate_after_hours,
    display_x, display_y, created_at
) VALUES (
    'ITEM0005-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'PRICING_APPROVAL',
    'Pricing Approval',
    'TASK',
    'Approve pricing for high-value items',
    NULL,  -- Pricing Manager position
    NULL,  -- APPROVER permission
    48,
    72,
    400, 200,
    datetime('now')
);

-- PUBLISH_ITEM Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ITEM0006-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'PUBLISH_ITEM',
    'Publish to Catalog',
    'TASK',
    'Publish item to marketplace catalog',
    NULL,  -- Catalog Manager position
    NULL,  -- IMPLEMENTOR permission
    24,
    500, 100,
    datetime('now')
);

-- END Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, display_x, display_y, created_at
) VALUES (
    'ITEM0007-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'END',
    'End',
    'END',
    'Process complete',
    600, 100,
    datetime('now')
);

-- =========================================
-- 3. PROCESS EDGES
-- =========================================

-- START → DRAFT_ITEM
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, created_at
) VALUES (
    'ITEME001-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0001-L1ST-4111-W111-000000000001',
    'ITEM0002-L1ST-4111-W111-000000000001',
    'Begin',
    1,
    datetime('now')
);

-- DRAFT_ITEM → QUALITY_REVIEW (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ITEME002-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0002-L1ST-4111-W111-000000000001',
    'ITEM0003-L1ST-4111-W111-000000000001',
    'Submit for Review',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- QUALITY_REVIEW → PRICING_CHECK (APPROVE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ITEME003-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0003-L1ST-4111-W111-000000000001',
    'ITEM0004-L1ST-4111-W111-000000000001',
    'Approved',
    1,
    1,
    'APPROVE',
    datetime('now')
);

-- QUALITY_REVIEW → DRAFT_ITEM (REJECT)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ITEME004-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0003-L1ST-4111-W111-000000000001',
    'ITEM0002-L1ST-4111-W111-000000000001',
    'Rejected - Needs Revision',
    2,
    1,
    'REJECT',
    datetime('now')
);

-- PRICING_CHECK → PRICING_APPROVAL (High Price)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, is_default, created_at
) VALUES (
    'ITEME005-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0004-L1ST-4111-W111-000000000001',
    'ITEM0005-L1ST-4111-W111-000000000001',
    'High Value Item',
    1,
    0,
    datetime('now')
);

-- PRICING_CHECK → PUBLISH_ITEM (Normal Price)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, is_default, created_at
) VALUES (
    'ITEME006-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0004-L1ST-4111-W111-000000000001',
    'ITEM0006-L1ST-4111-W111-000000000001',
    'Standard Item',
    2,
    1,
    datetime('now')
);

-- PRICING_APPROVAL → PUBLISH_ITEM (APPROVE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ITEME007-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0005-L1ST-4111-W111-000000000001',
    'ITEM0006-L1ST-4111-W111-000000000001',
    'Price Approved',
    1,
    1,
    'APPROVE',
    datetime('now')
);

-- PRICING_APPROVAL → DRAFT_ITEM (REJECT)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ITEME008-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0005-L1ST-4111-W111-000000000001',
    'ITEM0002-L1ST-4111-W111-000000000001',
    'Price Rejected',
    2,
    1,
    'REJECT',
    datetime('now')
);

-- PUBLISH_ITEM → END
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ITEME009-L1ST-4111-W111-000000000001',
    'ITEM0000-L1ST-4111-W111-000000000001',
    'ITEM0006-L1ST-4111-W111-000000000001',
    'ITEM0007-L1ST-4111-W111-000000000001',
    'Published',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- =========================================
-- 4. EDGE CONDITIONS
-- =========================================

-- Pricing check condition: item price > $500 requires approval
INSERT OR IGNORE INTO process_edge_condition (
    id, edge_id, condition_order,
    source_entity_code, source_field_name,
    comparison_operator, compare_value,
    created_at
) VALUES (
    'ITEMC001-L1ST-4111-W111-000000000001',
    'ITEME005-L1ST-4111-W111-000000000001',
    1,
    'ITEM_VARIANT',
    'price',
    'GREATER_THAN',
    '500',
    datetime('now')
);