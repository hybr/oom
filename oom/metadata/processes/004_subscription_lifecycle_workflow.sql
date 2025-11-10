-- =====================================================================
-- SUBSCRIPTION LIFECYCLE WORKFLOW - Recurring subscription management
-- From signup to cancellation with billing cycles
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. PROCESS GRAPH: Subscription Lifecycle Workflow
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
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBSCRIPTION_LIFECYCLE_WORKFLOW',
    'Subscription Lifecycle Management',
    'Manage recurring subscriptions from signup to cancellation',
    's1u1b1p1-l1a1-4n11-a111-111111111111',  -- SUBSCRIPTION_PLAN entity (could also use CUSTOMER_SUBSCRIPTION)
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
    'SUBS0001-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'START',
    'Start',
    'START',
    'Customer initiates subscription',
    100, 100,
    datetime('now')
);

-- CUSTOMER_SIGNUP Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0002-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'CUSTOMER_SIGNUP',
    'Customer Signup',
    'TASK',
    'Customer signs up for subscription plan',
    NULL,  -- Customer (PERSON)
    NULL,  -- REQUEST permission
    24,
    200, 100,
    datetime('now')
);

-- TRIAL_PERIOD Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0003-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'TRIAL_PERIOD',
    'Trial Period',
    'TASK',
    'Free trial period (if applicable)',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    168,  -- 7 days typical trial
    300, 100,
    datetime('now')
);

-- FIRST_BILLING Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0004-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'FIRST_BILLING',
    'First Billing',
    'TASK',
    'Process first subscription payment',
    NULL,  -- Billing System
    NULL,  -- IMPLEMENTOR permission
    2,
    400, 100,
    datetime('now')
);

-- ACTIVATE_SUBSCRIPTION Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0005-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'ACTIVATE_SUBSCRIPTION',
    'Activate Subscription',
    'TASK',
    'Activate full subscription benefits',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    1,
    500, 100,
    datetime('now')
);

-- RECURRING_BILLING Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0006-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'RECURRING_BILLING',
    'Recurring Billing',
    'TASK',
    'Process recurring billing cycle',
    NULL,  -- Billing System
    NULL,  -- IMPLEMENTOR permission
    730,  -- Monthly cycle (30 days)
    600, 100,
    datetime('now')
);

-- SEND_RENEWAL_REMINDER Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0007-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SEND_RENEWAL_REMINDER',
    'Send Renewal Reminder',
    'TASK',
    'Remind customer before renewal',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    1,
    700, 200,
    datetime('now')
);

-- HANDLE_PAYMENT_FAILURE Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0008-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'HANDLE_PAYMENT_FAILURE',
    'Handle Payment Failure',
    'TASK',
    'Retry payment and notify customer',
    NULL,  -- Billing System
    NULL,  -- IMPLEMENTOR permission
    72,  -- 3 days to resolve
    600, 300,
    datetime('now')
);

-- PROCESS_CANCELLATION Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0009-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'PROCESS_CANCELLATION',
    'Process Cancellation',
    'TASK',
    'Handle subscription cancellation request',
    NULL,  -- Customer Service
    NULL,  -- IMPLEMENTOR permission
    48,
    800, 100,
    datetime('now')
);

-- EXIT_SURVEY Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'SUBS0010-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'EXIT_SURVEY',
    'Exit Survey',
    'TASK',
    'Collect cancellation feedback',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    168,
    900, 100,
    datetime('now')
);

-- END Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, display_x, display_y, created_at
) VALUES (
    'SUBS0011-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'END',
    'End',
    'END',
    'Subscription ended',
    1000, 100,
    datetime('now')
);

-- =========================================
-- 3. PROCESS EDGES
-- =========================================

-- START → CUSTOMER_SIGNUP
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, created_at
) VALUES (
    'SUBSE001-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0001-LIFE-4111-W111-000000000001',
    'SUBS0002-LIFE-4111-W111-000000000001',
    'Begin',
    1,
    datetime('now')
);

-- CUSTOMER_SIGNUP → TRIAL_PERIOD
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE002-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0002-LIFE-4111-W111-000000000001',
    'SUBS0003-LIFE-4111-W111-000000000001',
    'Signed Up',
    1,
    'COMPLETE',
    datetime('now')
);

-- TRIAL_PERIOD → FIRST_BILLING
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE003-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0003-LIFE-4111-W111-000000000001',
    'SUBS0004-LIFE-4111-W111-000000000001',
    'Trial Ended',
    1,
    'COMPLETE',
    datetime('now')
);

-- FIRST_BILLING → ACTIVATE_SUBSCRIPTION (Success)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE004-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0004-LIFE-4111-W111-000000000001',
    'SUBS0005-LIFE-4111-W111-000000000001',
    'Payment Successful',
    1,
    'COMPLETE',
    datetime('now')
);

-- ACTIVATE_SUBSCRIPTION → RECURRING_BILLING
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE005-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0005-LIFE-4111-W111-000000000001',
    'SUBS0006-LIFE-4111-W111-000000000001',
    'Activated',
    1,
    'COMPLETE',
    datetime('now')
);

-- RECURRING_BILLING → SEND_RENEWAL_REMINDER
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE006-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0006-LIFE-4111-W111-000000000001',
    'SUBS0007-LIFE-4111-W111-000000000001',
    'Billing Successful',
    1,
    'COMPLETE',
    datetime('now')
);

-- RECURRING_BILLING → HANDLE_PAYMENT_FAILURE (Failure)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE007-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0006-LIFE-4111-W111-000000000001',
    'SUBS0008-LIFE-4111-W111-000000000001',
    'Payment Failed',
    2,
    'REJECT',
    datetime('now')
);

-- SEND_RENEWAL_REMINDER → RECURRING_BILLING (Loop)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE008-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0007-LIFE-4111-W111-000000000001',
    'SUBS0006-LIFE-4111-W111-000000000001',
    'Next Cycle',
    1,
    'COMPLETE',
    datetime('now')
);

-- HANDLE_PAYMENT_FAILURE → RECURRING_BILLING (Resolved)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE009-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0008-LIFE-4111-W111-000000000001',
    'SUBS0006-LIFE-4111-W111-000000000001',
    'Payment Resolved',
    1,
    'COMPLETE',
    datetime('now')
);

-- HANDLE_PAYMENT_FAILURE → PROCESS_CANCELLATION (Failed to resolve)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE010-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0008-LIFE-4111-W111-000000000001',
    'SUBS0009-LIFE-4111-W111-000000000001',
    'Auto-Cancel',
    2,
    'REJECT',
    datetime('now')
);

-- RECURRING_BILLING → PROCESS_CANCELLATION (Customer cancels)
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, created_at
) VALUES (
    'SUBSE011-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0006-LIFE-4111-W111-000000000001',
    'SUBS0009-LIFE-4111-W111-000000000001',
    'Cancel Request',
    3,
    datetime('now')
);

-- PROCESS_CANCELLATION → EXIT_SURVEY
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE012-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0009-LIFE-4111-W111-000000000001',
    'SUBS0010-LIFE-4111-W111-000000000001',
    'Cancelled',
    1,
    'COMPLETE',
    datetime('now')
);

-- EXIT_SURVEY → END
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, completion_action, created_at
) VALUES (
    'SUBSE013-LIFE-4111-W111-000000000001',
    'SUBS0000-LIFE-4111-W111-000000000001',
    'SUBS0010-LIFE-4111-W111-000000000001',
    'SUBS0011-LIFE-4111-W111-000000000001',
    'Survey Completed',
    1,
    'COMPLETE',
    datetime('now')
);
