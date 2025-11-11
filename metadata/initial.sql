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
-- Indexes
-- =========================================
CREATE INDEX IF NOT EXISTS idx_entity_attribute_entity_id ON entity_attribute(entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_relationship_from ON entity_relationship(from_entity_id);
CREATE INDEX IF NOT EXISTS idx_entity_relationship_to ON entity_relationship(to_entity_id);
CREATE INDEX IF NOT EXISTS idx_process_step_process_id ON process_step(process_id);
