-- =====================================================================
-- SERVICE APPOINTMENT WORKFLOW - Service booking and scheduling
-- From appointment request to completion with feedback
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. PROCESS GRAPH: Service Appointment Workflow
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
    'APPT0000-MGMT-4111-W111-000000000001',
    'SERVICE_APPOINTMENT_WORKFLOW',
    'Service Appointment Management',
    'Handle service booking, scheduling, and completion',
    's1a1p1p1-o1i1-4n1t-m111-111111111111',  -- SERVICE_APPOINTMENT entity
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
    'APPT0001-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'START',
    'Start',
    'START',
    'Customer requests appointment',
    100, 100,
    datetime('now')
);

-- REQUEST_APPOINTMENT Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0002-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'REQUEST_APPOINTMENT',
    'Customer Requests Appointment',
    'TASK',
    'Customer submits appointment request with preferred date/time',
    NULL,  -- Customer (PERSON)
    NULL,  -- REQUEST permission
    24,  -- 24 hours to submit
    200, 100,
    datetime('now')
);

-- CHECK_AVAILABILITY Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0003-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'CHECK_AVAILABILITY',
    'Check Provider Availability',
    'TASK',
    'Verify service provider availability for requested time',
    NULL,  -- Scheduler position
    NULL,  -- IMPLEMENTOR permission
    4,  -- 4 hours
    300, 100,
    datetime('now')
);

-- SCHEDULE_APPOINTMENT Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0004-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'SCHEDULE_APPOINTMENT',
    'Schedule Appointment',
    'TASK',
    'Confirm appointment date, time, and location',
    NULL,  -- Scheduler position
    NULL,  -- IMPLEMENTOR permission
    2,  -- 2 hours
    400, 100,
    datetime('now')
);

-- SEND_CONFIRMATION Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0005-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'SEND_CONFIRMATION',
    'Send Confirmation',
    'TASK',
    'Send appointment confirmation to customer and provider',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    1,  -- 1 hour
    500, 100,
    datetime('now')
);

-- SEND_REMINDER Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0006-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'SEND_REMINDER',
    'Send Reminder',
    'TASK',
    'Send appointment reminder 24 hours before',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    1,
    600, 100,
    datetime('now')
);

-- DELIVER_SERVICE Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0007-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'DELIVER_SERVICE',
    'Deliver Service',
    'TASK',
    'Service provider delivers the service',
    NULL,  -- Service Provider position
    NULL,  -- IMPLEMENTOR permission
    2,  -- Duration of appointment
    700, 100,
    datetime('now')
);

-- MARK_COMPLETED Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0008-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'MARK_COMPLETED',
    'Mark as Completed',
    'TASK',
    'Confirm service completion and mark appointment complete',
    NULL,  -- Service Provider position
    NULL,  -- IMPLEMENTOR permission
    1,
    800, 100,
    datetime('now')
);

-- COLLECT_FEEDBACK Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description,
    position_id, permission_type_id,
    sla_hours,
    display_x, display_y, created_at
) VALUES (
    'APPT0009-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'COLLECT_FEEDBACK',
    'Collect Customer Feedback',
    'TASK',
    'Request rating and feedback from customer',
    NULL,  -- System/Automated
    NULL,  -- IMPLEMENTOR permission
    168,  -- 7 days to provide feedback
    900, 100,
    datetime('now')
);

-- END Node
INSERT OR IGNORE INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, display_x, display_y, created_at
) VALUES (
    'APPT0010-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'END',
    'End',
    'END',
    'Appointment completed',
    1000, 100,
    datetime('now')
);

-- =========================================
-- 3. PROCESS EDGES
-- =========================================

-- START → REQUEST_APPOINTMENT
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, created_at
) VALUES (
    'APPTE001-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0001-MGMT-4111-W111-000000000001',
    'APPT0002-MGMT-4111-W111-000000000001',
    'Begin',
    1,
    datetime('now')
);

-- REQUEST_APPOINTMENT → CHECK_AVAILABILITY
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE002-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0002-MGMT-4111-W111-000000000001',
    'APPT0003-MGMT-4111-W111-000000000001',
    'Request Submitted',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- CHECK_AVAILABILITY → SCHEDULE_APPOINTMENT
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE003-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0003-MGMT-4111-W111-000000000001',
    'APPT0004-MGMT-4111-W111-000000000001',
    'Availability Confirmed',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- SCHEDULE_APPOINTMENT → SEND_CONFIRMATION
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE004-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0004-MGMT-4111-W111-000000000001',
    'APPT0005-MGMT-4111-W111-000000000001',
    'Scheduled',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- SEND_CONFIRMATION → SEND_REMINDER
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE005-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0005-MGMT-4111-W111-000000000001',
    'APPT0006-MGMT-4111-W111-000000000001',
    'Confirmed',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- SEND_REMINDER → DELIVER_SERVICE
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE006-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0006-MGMT-4111-W111-000000000001',
    'APPT0007-MGMT-4111-W111-000000000001',
    'Reminded',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- DELIVER_SERVICE → MARK_COMPLETED
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE007-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0007-MGMT-4111-W111-000000000001',
    'APPT0008-MGMT-4111-W111-000000000001',
    'Service Delivered',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- MARK_COMPLETED → COLLECT_FEEDBACK
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE008-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0008-MGMT-4111-W111-000000000001',
    'APPT0009-MGMT-4111-W111-000000000001',
    'Completed',
    1,
    0,
    'COMPLETE',
    datetime('now')
);

-- COLLECT_FEEDBACK → END
INSERT OR IGNORE INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order, requires_approval,
    completion_action, created_at
) VALUES (
    'APPTE009-MGMT-4111-W111-000000000001',
    'APPT0000-MGMT-4111-W111-000000000001',
    'APPT0009-MGMT-4111-W111-000000000001',
    'APPT0010-MGMT-4111-W111-000000000001',
    'Feedback Collected',
    1,
    0,
    'COMPLETE',
    datetime('now')
);
