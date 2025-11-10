-- =====================================================================
-- ORDER FULFILLMENT WORKFLOW - Complete order lifecycle management
-- From order placement to delivery confirmation
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. PROCESS GRAPH: Order Fulfillment Workflow
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
    'ORD00000-FULL-4111-W111-000000000001',
    'ORDER_FULFILLMENT_WORKFLOW',
    'Order Fulfillment Process',
    'Manage complete order lifecycle from placement to delivery',
    'o1r1d1e1-r000-4111-a111-111111111111',  -- ORDER entity
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
    'ORD00001-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'START',
    'Start',
    'START',
    'Order placed',
    100, 100,
    datetime('now')
);

-- VALIDATE_ORDER Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00002-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'VALIDATE_ORDER',
    'Validate Order',
    'TASK',
    'Validate order details, inventory, and payment',
    NULL,  -- Order Processor position
    NULL,  -- IMPLEMENTOR permission
    2,  -- 2 hours
    200, 100,
    datetime('now')
);

-- PROCESS_PAYMENT Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00003-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'PROCESS_PAYMENT',
    'Process Payment',
    'TASK',
    'Authorize and capture payment',
    NULL,  -- Payment Processor position
    NULL,  -- IMPLEMENTOR permission
    1,  -- 1 hour
    300, 100,
    datetime('now')
);

-- ALLOCATE_INVENTORY Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00004-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ALLOCATE_INVENTORY',
    'Allocate Inventory',
    'TASK',
    'Reserve inventory for order items',
    NULL,  -- Inventory Manager position
    NULL,  -- IMPLEMENTOR permission
    4,  -- 4 hours
    400, 100,
    datetime('now')
);

-- NOTIFY_VENDOR Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00005-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'NOTIFY_VENDOR',
    'Notify Vendor',
    'TASK',
    'Send order notification to vendor/fulfillment center',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    1,  -- 1 hour
    500, 100,
    datetime('now')
);

-- PREPARE_ITEMS Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours, escalate_after_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00006-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'PREPARE_ITEMS',
    'Prepare & Package Items',
    'TASK',
    'Pick, pack, and label order items',
    NULL,  -- Warehouse Staff position
    NULL,  -- IMPLEMENTOR permission
    24,  -- 1 day
    48,
    600, 100,
    datetime('now')
);

-- HANDOFF_SHIPMENT Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00007-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'HANDOFF_SHIPMENT',
    'Handoff to Shipping',
    'TASK',
    'Transfer package to shipping carrier',
    NULL,  -- Warehouse Manager position
    NULL,  -- IMPLEMENTOR permission
    12,  -- 12 hours
    700, 100,
    datetime('now')
);

-- TRACK_DELIVERY Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00008-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'TRACK_DELIVERY',
    'Track Delivery',
    'TASK',
    'Monitor shipment and update tracking status',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    168,  -- 7 days
    800, 100,
    datetime('now')
);

-- CONFIRM_DELIVERY Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00009-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'CONFIRM_DELIVERY',
    'Confirm Delivery',
    'TASK',
    'Verify delivery completion and customer receipt',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    24,
    900, 100,
    datetime('now')
);

-- REQUEST_REVIEW Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'ORD00010-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'REQUEST_REVIEW',
    'Request Customer Review',
    'TASK',
    'Send review request to customer',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    1,
    1000, 100,
    datetime('now')
);

-- END Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, display_x, display_y, created_at
) VALUES (
    'ORD00011-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'END',
    'End',
    'END',
    'Order fulfilled',
    1100, 100,
    datetime('now')
);

-- =========================================
-- 3. PROCESS EDGES
-- =========================================

-- START → VALIDATE_ORDER
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, created_at
) VALUES (
    'ORDE0001-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00001-FULL-4111-W111-000000000001',
    'ORD00002-FULL-4111-W111-000000000001',
    'Order Received',
    1,
    datetime('now')
);

-- VALIDATE_ORDER → PROCESS_PAYMENT (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0002-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00002-FULL-4111-W111-000000000001',
    'ORD00003-FULL-4111-W111-000000000001',
    'Validated',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- PROCESS_PAYMENT → ALLOCATE_INVENTORY (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0003-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00003-FULL-4111-W111-000000000001',
    'ORD00004-FULL-4111-W111-000000000001',
    'Payment Successful',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- ALLOCATE_INVENTORY → NOTIFY_VENDOR (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0004-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00004-FULL-4111-W111-000000000001',
    'ORD00005-FULL-4111-W111-000000000001',
    'Inventory Reserved',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- NOTIFY_VENDOR → PREPARE_ITEMS (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0005-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00005-FULL-4111-W111-000000000001',
    'ORD00006-FULL-4111-W111-000000000001',
    'Vendor Notified',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- PREPARE_ITEMS → HANDOFF_SHIPMENT (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0006-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00006-FULL-4111-W111-000000000001',
    'ORD00007-FULL-4111-W111-000000000001',
    'Items Packaged',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- HANDOFF_SHIPMENT → TRACK_DELIVERY (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0007-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00007-FULL-4111-W111-000000000001',
    'ORD00008-FULL-4111-W111-000000000001',
    'Shipped',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- TRACK_DELIVERY → CONFIRM_DELIVERY (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0008-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00008-FULL-4111-W111-000000000001',
    'ORD00009-FULL-4111-W111-000000000001',
    'In Transit',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- CONFIRM_DELIVERY → REQUEST_REVIEW (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0009-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00009-FULL-4111-W111-000000000001',
    'ORD00010-FULL-4111-W111-000000000001',
    'Delivered',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- REQUEST_REVIEW → END (COMPLETE)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'ORDE0010-FULL-4111-W111-000000000001',
    'ORD00000-FULL-4111-W111-000000000001',
    'ORD00010-FULL-4111-W111-000000000001',
    'ORD00011-FULL-4111-W111-000000000001',
    'Review Requested',
    1,
    0,
    'COMPLETE',
    datetime('now')
);