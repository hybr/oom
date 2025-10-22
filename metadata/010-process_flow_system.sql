-- =====================================================================
-- Process Flow System - Graph-based workflow engine with position-based permissions
-- Generated: 2025-10-22
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- DDL: Process Flow System Tables
-- =========================================

-- =========================================
-- 1. PROCESS_GRAPH: Process template definitions with versioning
-- =========================================
CREATE TABLE IF NOT EXISTS process_graph (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    code TEXT NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    entity_id TEXT,  -- Which entity this process operates on (nullable for generic processes)
    version_number INTEGER NOT NULL DEFAULT 1,
    is_active INTEGER NOT NULL DEFAULT 1,
    is_published INTEGER NOT NULL DEFAULT 0,
    category TEXT,  -- e.g., 'APPROVAL', 'HIRING', 'PROCUREMENT'
    created_by TEXT NOT NULL,
    published_at TEXT,
    published_by TEXT,

    UNIQUE(code, version_number),
    FOREIGN KEY(entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY(created_by) REFERENCES person(id),
    FOREIGN KEY(published_by) REFERENCES person(id)
);

CREATE INDEX IF NOT EXISTS idx_process_graph_code ON process_graph(code);
CREATE INDEX IF NOT EXISTS idx_process_graph_entity ON process_graph(entity_id);
CREATE INDEX IF NOT EXISTS idx_process_graph_active ON process_graph(is_active, is_published);

-- =========================================
-- 2. PROCESS_NODE: Individual steps/nodes in a process graph
-- =========================================
CREATE TABLE IF NOT EXISTS process_node (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    graph_id TEXT NOT NULL,
    node_code TEXT NOT NULL,
    node_name TEXT NOT NULL,
    node_type TEXT NOT NULL,  -- START, TASK, DECISION, FORK, JOIN, END
    description TEXT,

    -- Task assignment (for TASK nodes)
    position_id TEXT,  -- Which position should handle this task
    permission_type_id TEXT,  -- Required permission (REQUEST, APPROVE, DEVELOP, etc.)

    -- SLA and timing
    sla_hours INTEGER,  -- Service Level Agreement in hours
    estimated_duration_hours INTEGER,

    -- UI and display
    display_x INTEGER,  -- X coordinate for visual designer
    display_y INTEGER,  -- Y coordinate for visual designer
    form_template TEXT,  -- Optional: custom form for this task
    instructions TEXT,  -- Instructions for task performer

    -- Notification settings
    notify_on_assignment INTEGER DEFAULT 1,
    notify_on_due INTEGER DEFAULT 1,
    escalate_after_hours INTEGER,
    escalate_to_position_id TEXT,  -- Escalation position if SLA breached

    UNIQUE(graph_id, node_code),
    FOREIGN KEY(graph_id) REFERENCES process_graph(id) ON DELETE CASCADE,
    FOREIGN KEY(position_id) REFERENCES popular_organization_position(id),
    FOREIGN KEY(permission_type_id) REFERENCES enum_entity_permission_type(id),
    FOREIGN KEY(escalate_to_position_id) REFERENCES popular_organization_position(id)
);

CREATE INDEX IF NOT EXISTS idx_process_node_graph ON process_node(graph_id);
CREATE INDEX IF NOT EXISTS idx_process_node_type ON process_node(node_type);
CREATE INDEX IF NOT EXISTS idx_process_node_position ON process_node(position_id);

-- =========================================
-- 3. PROCESS_EDGE: Transitions/connections between nodes
-- =========================================
CREATE TABLE IF NOT EXISTS process_edge (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    graph_id TEXT NOT NULL,
    from_node_id TEXT NOT NULL,
    to_node_id TEXT NOT NULL,

    edge_label TEXT,  -- e.g., "Approved", "Rejected", "High Value"
    edge_order INTEGER DEFAULT 0,  -- Order of evaluation (lower = higher priority)
    is_default INTEGER DEFAULT 0,  -- Default path if no conditions match

    description TEXT,

    FOREIGN KEY(graph_id) REFERENCES process_graph(id) ON DELETE CASCADE,
    FOREIGN KEY(from_node_id) REFERENCES process_node(id) ON DELETE CASCADE,
    FOREIGN KEY(to_node_id) REFERENCES process_node(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_process_edge_graph ON process_edge(graph_id);
CREATE INDEX IF NOT EXISTS idx_process_edge_from ON process_edge(from_node_id);
CREATE INDEX IF NOT EXISTS idx_process_edge_to ON process_edge(to_node_id);

-- =========================================
-- 4. PROCESS_EDGE_CONDITION: Structured conditions for edge transitions
-- =========================================
CREATE TABLE IF NOT EXISTS process_edge_condition (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    edge_id TEXT NOT NULL,
    condition_order INTEGER DEFAULT 0,

    -- Field to evaluate
    field_source TEXT NOT NULL,  -- 'ENTITY_FIELD', 'FLOW_VARIABLE', 'TASK_VARIABLE', 'SYSTEM'
    field_name TEXT NOT NULL,  -- Name of field/variable to check

    -- Comparison
    operator TEXT NOT NULL,  -- EQ, NEQ, GT, GTE, LT, LTE, IN, NOT_IN, CONTAINS, STARTS_WITH, ENDS_WITH, IS_NULL, IS_NOT_NULL
    value_type TEXT NOT NULL,  -- STRING, NUMBER, BOOLEAN, DATE, LIST
    compare_value TEXT,  -- Value to compare against (JSON for lists)

    -- Logical grouping
    logic_operator TEXT DEFAULT 'AND',  -- AND, OR (combines with next condition)
    condition_group INTEGER DEFAULT 0,  -- Group conditions together

    FOREIGN KEY(edge_id) REFERENCES process_edge(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_edge_condition_edge ON process_edge_condition(edge_id);

-- =========================================
-- 5. TASK_FLOW_INSTANCE: Running instance of a process
-- =========================================
CREATE TABLE IF NOT EXISTS task_flow_instance (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    graph_id TEXT NOT NULL,
    graph_version INTEGER NOT NULL,  -- Snapshot version at creation

    -- Context
    entity_code TEXT,  -- Entity this flow is processing
    entity_record_id TEXT,  -- Specific record being processed
    organization_id TEXT,  -- Organization context

    -- State
    current_node_id TEXT,
    status TEXT NOT NULL,  -- RUNNING, COMPLETED, CANCELLED, ERROR, SUSPENDED

    -- Timing
    started_at TEXT NOT NULL,
    started_by TEXT NOT NULL,
    completed_at TEXT,
    suspended_at TEXT,
    suspended_reason TEXT,

    -- Variables (JSON object for dynamic data)
    flow_variables TEXT,  -- Store dynamic values during execution

    -- Priority and tracking
    priority INTEGER DEFAULT 0,  -- Higher number = higher priority
    reference_number TEXT,  -- Human-readable reference

    FOREIGN KEY(graph_id) REFERENCES process_graph(id),
    FOREIGN KEY(current_node_id) REFERENCES process_node(id),
    FOREIGN KEY(started_by) REFERENCES person(id),
    FOREIGN KEY(organization_id) REFERENCES organization(id)
);

CREATE INDEX IF NOT EXISTS idx_flow_instance_graph ON task_flow_instance(graph_id);
CREATE INDEX IF NOT EXISTS idx_flow_instance_status ON task_flow_instance(status);
CREATE INDEX IF NOT EXISTS idx_flow_instance_organization ON task_flow_instance(organization_id);
CREATE INDEX IF NOT EXISTS idx_flow_instance_entity ON task_flow_instance(entity_code, entity_record_id);

-- =========================================
-- 6. TASK_INSTANCE: Individual task within a flow instance
-- =========================================
CREATE TABLE IF NOT EXISTS task_instance (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    flow_instance_id TEXT NOT NULL,
    node_id TEXT NOT NULL,

    -- Assignment
    assigned_to TEXT,  -- Person currently assigned
    assigned_at TEXT,
    assigned_by TEXT,  -- Who made the assignment
    assignment_type TEXT,  -- AUTO, MANUAL, ESCALATED, FALLBACK

    -- Status
    status TEXT NOT NULL,  -- PENDING, IN_PROGRESS, COMPLETED, CANCELLED, ESCALATED

    -- Timing
    due_date TEXT,
    started_at TEXT,
    completed_at TEXT,

    -- Completion details
    completion_action TEXT,  -- APPROVE, REJECT, COMPLETE, FORWARD, etc.
    completion_comments TEXT,
    completion_data TEXT,  -- JSON data from form submission

    -- Task-specific variables
    task_variables TEXT,  -- JSON object for task-specific data

    -- Tracking
    attempts INTEGER DEFAULT 0,  -- Number of reassignments
    time_spent_minutes INTEGER,  -- Actual time spent

    FOREIGN KEY(flow_instance_id) REFERENCES task_flow_instance(id) ON DELETE CASCADE,
    FOREIGN KEY(node_id) REFERENCES process_node(id),
    FOREIGN KEY(assigned_to) REFERENCES person(id),
    FOREIGN KEY(assigned_by) REFERENCES person(id)
);

CREATE INDEX IF NOT EXISTS idx_task_instance_flow ON task_instance(flow_instance_id);
CREATE INDEX IF NOT EXISTS idx_task_instance_assigned ON task_instance(assigned_to, status);
CREATE INDEX IF NOT EXISTS idx_task_instance_status ON task_instance(status);
CREATE INDEX IF NOT EXISTS idx_task_instance_due ON task_instance(due_date);

-- =========================================
-- 7. TASK_AUDIT_LOG: Immutable audit trail of all process actions
-- =========================================
CREATE TABLE IF NOT EXISTS task_audit_log (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),

    flow_instance_id TEXT NOT NULL,
    task_instance_id TEXT,  -- Nullable for flow-level events

    -- Action details
    action TEXT NOT NULL,  -- FLOW_START, FLOW_COMPLETE, TASK_CREATE, TASK_ASSIGN, TASK_COMPLETE, etc.
    actor_id TEXT NOT NULL,  -- Who performed the action

    -- State changes
    from_status TEXT,
    to_status TEXT,
    from_node_id TEXT,
    to_node_id TEXT,

    -- Data changes
    old_values TEXT,  -- JSON snapshot of old values
    new_values TEXT,  -- JSON snapshot of new values

    -- Context
    ip_address TEXT,
    user_agent TEXT,
    comments TEXT,

    FOREIGN KEY(flow_instance_id) REFERENCES task_flow_instance(id) ON DELETE CASCADE,
    FOREIGN KEY(task_instance_id) REFERENCES task_instance(id) ON DELETE CASCADE,
    FOREIGN KEY(actor_id) REFERENCES person(id),
    FOREIGN KEY(from_node_id) REFERENCES process_node(id),
    FOREIGN KEY(to_node_id) REFERENCES process_node(id)
);

CREATE INDEX IF NOT EXISTS idx_audit_log_flow ON task_audit_log(flow_instance_id);
CREATE INDEX IF NOT EXISTS idx_audit_log_task ON task_audit_log(task_instance_id);
CREATE INDEX IF NOT EXISTS idx_audit_log_actor ON task_audit_log(actor_id);
CREATE INDEX IF NOT EXISTS idx_audit_log_action ON task_audit_log(action);
CREATE INDEX IF NOT EXISTS idx_audit_log_created ON task_audit_log(created_at);

-- =========================================
-- 8. PROCESS_FALLBACK_ASSIGNMENT: Fallback assignments when position is vacant
-- =========================================
CREATE TABLE IF NOT EXISTS process_fallback_assignment (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    organization_id TEXT NOT NULL,
    position_id TEXT NOT NULL,  -- Position that might be vacant
    fallback_type TEXT NOT NULL,  -- PERSON, POSITION, AUTO_ADMIN
    fallback_person_id TEXT,  -- Specific person to fallback to
    fallback_position_id TEXT,  -- Alternative position to fallback to
    priority INTEGER DEFAULT 0,  -- Order of fallback (lower = higher priority)
    is_active INTEGER DEFAULT 1,

    UNIQUE(organization_id, position_id, fallback_type, priority),
    FOREIGN KEY(organization_id) REFERENCES organization(id),
    FOREIGN KEY(position_id) REFERENCES popular_organization_position(id),
    FOREIGN KEY(fallback_person_id) REFERENCES person(id),
    FOREIGN KEY(fallback_position_id) REFERENCES popular_organization_position(id)
);

CREATE INDEX IF NOT EXISTS idx_fallback_org_position ON process_fallback_assignment(organization_id, position_id);
CREATE INDEX IF NOT EXISTS idx_fallback_active ON process_fallback_assignment(is_active);

-- =========================================
-- Entity Definitions for Process Flow System
-- =========================================

-- PROCESS_GRAPH entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000001',
    'PROCESS_GRAPH',
    'Process Graph',
    'Process template definitions with versioning',
    'WORKFLOW',
    'process_graph',
    1
);

-- PROCESS_NODE entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000002',
    'PROCESS_NODE',
    'Process Node',
    'Individual steps/nodes in a process graph',
    'WORKFLOW',
    'process_node',
    1
);

-- PROCESS_EDGE entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000003',
    'PROCESS_EDGE',
    'Process Edge',
    'Transitions/connections between nodes',
    'WORKFLOW',
    'process_edge',
    1
);

-- PROCESS_EDGE_CONDITION entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000004',
    'PROCESS_EDGE_CONDITION',
    'Process Edge Condition',
    'Structured conditions for edge transitions',
    'WORKFLOW',
    'process_edge_condition',
    1
);

-- TASK_FLOW_INSTANCE entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000005',
    'TASK_FLOW_INSTANCE',
    'Task Flow Instance',
    'Running instance of a process',
    'WORKFLOW',
    'task_flow_instance',
    1
);

-- TASK_INSTANCE entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000006',
    'TASK_INSTANCE',
    'Task Instance',
    'Individual task within a flow instance',
    'WORKFLOW',
    'task_instance',
    1
);

-- TASK_AUDIT_LOG entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000007',
    'TASK_AUDIT_LOG',
    'Task Audit Log',
    'Immutable audit trail of all process actions',
    'WORKFLOW',
    'task_audit_log',
    1
);

-- PROCESS_FALLBACK_ASSIGNMENT entity
INSERT INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '10000000-0000-4000-8000-000000000008',
    'PROCESS_FALLBACK_ASSIGNMENT',
    'Process Fallback Assignment',
    'Fallback assignments when position is vacant',
    'WORKFLOW',
    'process_fallback_assignment',
    1
);

-- =========================================
-- Attributes for PROCESS_GRAPH
-- =========================================
INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_system, is_label, display_order)
VALUES
    ('10001000-0000-4000-8000-000000000001', '10000000-0000-4000-8000-000000000001', 'code', 'Code', 'text', 1, 0, 1, 1),
    ('10001000-0000-4000-8000-000000000002', '10000000-0000-4000-8000-000000000001', 'name', 'Name', 'text', 1, 0, 1, 2),
    ('10001000-0000-4000-8000-000000000003', '10000000-0000-4000-8000-000000000001', 'description', 'Description', 'text', 0, 0, 0, 3),
    ('10001000-0000-4000-8000-000000000004', '10000000-0000-4000-8000-000000000001', 'entity_id', 'Entity', 'uuid', 0, 0, 0, 4),
    ('10001000-0000-4000-8000-000000000005', '10000000-0000-4000-8000-000000000001', 'version_number', 'Version Number', 'integer', 1, 0, 0, 5),
    ('10001000-0000-4000-8000-000000000006', '10000000-0000-4000-8000-000000000001', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 6),
    ('10001000-0000-4000-8000-000000000007', '10000000-0000-4000-8000-000000000001', 'is_published', 'Is Published', 'boolean', 1, 0, 0, 7),
    ('10001000-0000-4000-8000-000000000008', '10000000-0000-4000-8000-000000000001', 'category', 'Category', 'text', 0, 0, 0, 8),
    ('10001000-0000-4000-8000-000000000009', '10000000-0000-4000-8000-000000000001', 'created_by', 'Created By', 'uuid', 1, 0, 0, 9);

-- =========================================
-- Attributes for PROCESS_NODE
-- =========================================
INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_system, is_label, display_order)
VALUES
    ('10002000-0000-4000-8000-000000000001', '10000000-0000-4000-8000-000000000002', 'graph_id', 'Process Graph', 'uuid', 1, 0, 0, 1),
    ('10002000-0000-4000-8000-000000000002', '10000000-0000-4000-8000-000000000002', 'node_code', 'Node Code', 'text', 1, 0, 1, 2),
    ('10002000-0000-4000-8000-000000000003', '10000000-0000-4000-8000-000000000002', 'node_name', 'Node Name', 'text', 1, 0, 1, 3),
    ('10002000-0000-4000-8000-000000000004', '10000000-0000-4000-8000-000000000002', 'node_type', 'Node Type', 'text', 1, 0, 0, 4),
    ('10002000-0000-4000-8000-000000000005', '10000000-0000-4000-8000-000000000002', 'position_id', 'Position', 'uuid', 0, 0, 0, 5),
    ('10002000-0000-4000-8000-000000000006', '10000000-0000-4000-8000-000000000002', 'permission_type_id', 'Permission Type', 'uuid', 0, 0, 0, 6),
    ('10002000-0000-4000-8000-000000000007', '10000000-0000-4000-8000-000000000002', 'sla_hours', 'SLA Hours', 'integer', 0, 0, 0, 7),
    ('10002000-0000-4000-8000-000000000008', '10000000-0000-4000-8000-000000000002', 'instructions', 'Instructions', 'text', 0, 0, 0, 8);

-- =========================================
-- Attributes for TASK_FLOW_INSTANCE
-- =========================================
INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_system, is_label, display_order)
VALUES
    ('10005000-0000-4000-8000-000000000001', '10000000-0000-4000-8000-000000000005', 'graph_id', 'Process Graph', 'uuid', 1, 0, 0, 1),
    ('10005000-0000-4000-8000-000000000002', '10000000-0000-4000-8000-000000000005', 'entity_code', 'Entity Code', 'text', 0, 0, 0, 2),
    ('10005000-0000-4000-8000-000000000003', '10000000-0000-4000-8000-000000000005', 'entity_record_id', 'Entity Record ID', 'text', 0, 0, 0, 3),
    ('10005000-0000-4000-8000-000000000004', '10000000-0000-4000-8000-000000000005', 'status', 'Status', 'text', 1, 0, 1, 4),
    ('10005000-0000-4000-8000-000000000005', '10000000-0000-4000-8000-000000000005', 'started_by', 'Started By', 'uuid', 1, 0, 0, 5),
    ('10005000-0000-4000-8000-000000000006', '10000000-0000-4000-8000-000000000005', 'reference_number', 'Reference Number', 'text', 0, 0, 1, 6);

-- =========================================
-- Attributes for TASK_INSTANCE
-- =========================================
INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_system, is_label, display_order)
VALUES
    ('10006000-0000-4000-8000-000000000001', '10000000-0000-4000-8000-000000000006', 'flow_instance_id', 'Flow Instance', 'uuid', 1, 0, 0, 1),
    ('10006000-0000-4000-8000-000000000002', '10000000-0000-4000-8000-000000000006', 'node_id', 'Node', 'uuid', 1, 0, 0, 2),
    ('10006000-0000-4000-8000-000000000003', '10000000-0000-4000-8000-000000000006', 'assigned_to', 'Assigned To', 'uuid', 0, 0, 1, 3),
    ('10006000-0000-4000-8000-000000000004', '10000000-0000-4000-8000-000000000006', 'status', 'Status', 'text', 1, 0, 1, 4),
    ('10006000-0000-4000-8000-000000000005', '10000000-0000-4000-8000-000000000006', 'due_date', 'Due Date', 'datetime', 0, 0, 0, 5),
    ('10006000-0000-4000-8000-000000000006', '10000000-0000-4000-8000-000000000006', 'completion_action', 'Completion Action', 'text', 0, 0, 0, 6);

-- =========================================
-- Relationships
-- =========================================
INSERT INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, fk_field, relation_name)
VALUES
    ('10100000-0000-4000-8000-000000000001', '10000000-0000-4000-8000-000000000002', '10000000-0000-4000-8000-000000000001', 'many-to-one', 'graph_id', 'process_node_to_graph'),
    ('10100000-0000-4000-8000-000000000002', '10000000-0000-4000-8000-000000000003', '10000000-0000-4000-8000-000000000001', 'many-to-one', 'graph_id', 'process_edge_to_graph'),
    ('10100000-0000-4000-8000-000000000003', '10000000-0000-4000-8000-000000000005', '10000000-0000-4000-8000-000000000001', 'many-to-one', 'graph_id', 'flow_instance_to_graph'),
    ('10100000-0000-4000-8000-000000000004', '10000000-0000-4000-8000-000000000006', '10000000-0000-4000-8000-000000000005', 'many-to-one', 'flow_instance_id', 'task_instance_to_flow');

-- =========================================
-- Sample Data: Simple Approval Process
-- =========================================

-- Create a sample process graph
INSERT INTO process_graph (id, code, name, description, version_number, is_active, is_published, category, created_by)
VALUES (
    '20000000-0000-4000-8000-000000000001',
    'SIMPLE_APPROVAL',
    'Simple Approval Process',
    'Basic two-step approval process with manager approval',
    1,
    1,
    1,
    'APPROVAL',
    '00000000-0000-4000-8000-000000000001'  -- System admin
);

-- =========================================
-- End of Process Flow System Migration
-- =========================================
