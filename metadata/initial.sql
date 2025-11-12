-- =====================================================================
-- Initial Schema for V4L Database
-- Creates the metadata tables used to define entities dynamically
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- Entity Definition Table
-- =========================================
CREATE TABLE IF NOT EXISTS entity_definition (
    id TEXT PRIMARY KEY,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    description TEXT,
    domain TEXT,
    table_name TEXT NOT NULL UNIQUE,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

-- =========================================
-- Entity Attribute Table
-- =========================================
CREATE TABLE IF NOT EXISTS entity_attribute (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,
    code TEXT NOT NULL,
    name TEXT NOT NULL,
    data_type TEXT NOT NULL,
    is_required INTEGER DEFAULT 0,
    is_unique INTEGER DEFAULT 0,
    is_system INTEGER DEFAULT 0,
    is_label INTEGER DEFAULT 0,
    default_value TEXT,
    min_value TEXT,
    max_value TEXT,
    enum_values TEXT,
    validation_regex TEXT,
    description TEXT,
    display_order INTEGER,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (entity_id) REFERENCES entity_definition(id),
    UNIQUE(entity_id, code)
);

-- =========================================
-- Entity Relationship Table
-- =========================================
CREATE TABLE IF NOT EXISTS entity_relationship (
    id TEXT PRIMARY KEY,
    from_entity_id TEXT NOT NULL,
    to_entity_id TEXT NOT NULL,
    relation_type TEXT NOT NULL,
    relation_name TEXT NOT NULL,
    fk_field TEXT,
    description TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (from_entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY (to_entity_id) REFERENCES entity_definition(id)
);

-- =========================================
-- Entity Function Table
-- =========================================
CREATE TABLE IF NOT EXISTS entity_function (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,
    function_code TEXT NOT NULL,
    function_name TEXT,
    function_description TEXT,
    parameters TEXT,
    return_type TEXT,
    is_system INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (entity_id) REFERENCES entity_definition(id),
    UNIQUE(entity_id, function_code)
);

-- =========================================
-- Entity Function Handler Table
-- =========================================
CREATE TABLE IF NOT EXISTS entity_function_handler (
    id TEXT PRIMARY KEY,
    function_id TEXT NOT NULL,
    handler_type TEXT,
    handler_reference TEXT,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (function_id) REFERENCES entity_function(id)
);

-- =========================================
-- Entity Validation Rule Table
-- =========================================
CREATE TABLE IF NOT EXISTS entity_validation_rule (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,
    attribute_id TEXT NOT NULL,
    rule_name TEXT,
    rule_expression TEXT,
    error_message TEXT,
    severity TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY (attribute_id) REFERENCES entity_attribute(id)
);

-- =========================================
-- Process Definition Table
-- =========================================
CREATE TABLE IF NOT EXISTS process_definition (
    id TEXT PRIMARY KEY,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    description TEXT,
    domain TEXT,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

-- =========================================
-- Process Step Table
-- =========================================
CREATE TABLE IF NOT EXISTS process_step (
    id TEXT PRIMARY KEY,
    process_id TEXT NOT NULL,
    code TEXT NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    step_order INTEGER NOT NULL,
    step_type TEXT,
    handler_class TEXT,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY (process_id) REFERENCES process_definition(id),
    UNIQUE(process_id, code)
);

-- =========================================
-- Process Graph Table (Workflow Definitions)
-- =========================================
CREATE TABLE IF NOT EXISTS process_graph (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT,
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    created_by TEXT,
    updated_by TEXT,
    entity_id TEXT,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    description TEXT,
    version_number INTEGER DEFAULT 1,
    is_active INTEGER DEFAULT 1,
    is_published INTEGER,
    category TEXT,
    published_at TEXT,
    published_by TEXT
);

-- =========================================
-- Process Node Table (Workflow Steps)
-- =========================================
CREATE TABLE IF NOT EXISTS process_node (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT,
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    created_by TEXT,
    updated_by TEXT,
    graph_id TEXT NOT NULL,
    node_code TEXT NOT NULL,
    node_name TEXT NOT NULL,
    node_type TEXT NOT NULL,
    description TEXT,
    position_id TEXT,
    permission_type_id TEXT,
    sla_hours INTEGER,
    escalate_after_hours INTEGER,
    form_entities TEXT,
    display_x INTEGER,
    display_y INTEGER,
    FOREIGN KEY (graph_id) REFERENCES process_graph(id)
);

-- =========================================
-- Process Edge Table (Workflow Transitions)
-- =========================================
CREATE TABLE IF NOT EXISTS process_edge (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT,
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    created_by TEXT,
    updated_by TEXT,
    graph_id TEXT NOT NULL,
    from_node_id TEXT NOT NULL,
    to_node_id TEXT NOT NULL,
    edge_label TEXT,
    edge_order INTEGER,
    requires_approval INTEGER DEFAULT 0,
    approval_position_id TEXT,
    condition_expression TEXT,
    completion_action TEXT,
    FOREIGN KEY (graph_id) REFERENCES process_graph(id),
    FOREIGN KEY (from_node_id) REFERENCES process_node(id),
    FOREIGN KEY (to_node_id) REFERENCES process_node(id)
);

-- =========================================
-- Indexes
-- =========================================
CREATE INDEX IF NOT EXISTS idx_entity_attribute_entity_id ON entity_attribute(entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_relationship_from ON entity_relationship(from_entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_relationship_to ON entity_relationship(to_entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_function_entity_id ON entity_function(entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_function_handler_function_id ON entity_function_handler(function_id);
CREATE INDEX IF NOT EXISTS idx_entity_validation_rule_entity_id ON entity_validation_rule(entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_validation_rule_attribute_id ON entity_validation_rule(attribute_id);
CREATE INDEX IF NOT EXISTS idx_process_step_process_id ON process_step(process_id);
CREATE INDEX IF NOT EXISTS idx_process_node_graph_id ON process_node(graph_id);
CREATE INDEX IF NOT EXISTS idx_process_edge_graph_id ON process_edge(graph_id);
CREATE INDEX IF NOT EXISTS idx_process_edge_from_node ON process_edge(from_node_id);
CREATE INDEX IF NOT EXISTS idx_process_edge_to_node ON process_edge(to_node_id);
