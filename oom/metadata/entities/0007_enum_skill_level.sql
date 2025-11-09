-- =====================================================================
-- Entity: ENUM_SKILL_LEVEL
-- Domain: SKILLS
-- Description: Proficiency levels for skills
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g', 'ENUM_SKILL_LEVEL', 'Skill Level', 'Proficiency levels for skills: Beginner, Novice, Intermediate, Advanced, Expert', 'SKILLS', 'enum_skill_level', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('attr-skllvl-001','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','code','Code','text',1,1,1,'Unique code for skill level (e.g., BEGINNER)',1, datetime('now'), datetime('now')),
('attr-skllvl-002','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','name','Name','text',1,0,1,'Human-readable skill level name',2, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- No direct relationships (enum type)

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-skllvl-create','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','create','Create Skill Level','Create a new skill level','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-skllvl-read','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','read','Read Skill Level','Read skill level by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-skllvl-update','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','update','Update Skill Level','Update skill level by id','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-skllvl-delete','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','delete','Delete Skill Level','Delete by id','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-skllvl-search','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','search','Search Skill Levels','Search with filters','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-skllvl-list-all','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','list_all','List All Skill Levels','List all available skill levels','[]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-skllvl-create','func-skllvl-create','sql','sp_create_enum_skill_level',1, datetime('now')),
('h-skllvl-read','func-skllvl-read','sql','sp_read_enum_skill_level',1, datetime('now')),
('h-skllvl-update','func-skllvl-update','sql','sp_update_enum_skill_level',1, datetime('now')),
('h-skllvl-delete','func-skllvl-delete','sql','sp_delete_enum_skill_level',1, datetime('now')),
('h-skllvl-search','func-skllvl-search','sql','sp_search_enum_skill_level',1, datetime('now')),
('h-skllvl-list-all','func-skllvl-list-all','sql','sp_list_all_enum_skill_level',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity, created_at) VALUES
('vr-skllvl-code-format','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','attr-skllvl-001','code_uppercase','value = UPPER(value)','Skill level code must be uppercase','error', datetime('now')),
('vr-skllvl-name-length','9f5b1a4d-6c0e-4b13-d8f7-4a0c1e3d5b6g','attr-skllvl-002','name_min_length','length(value) >= 3','Skill level name must be at least 3 characters','error', datetime('now'));

-- =========================================
-- 7. Entity Data
-- =========================================
INSERT OR IGNORE INTO enum_skill_level (id, code, name, created_at, updated_at)
VALUES
('sl-00000001-0000-4000-0000-000000000001', 'BEGINNER', 'Beginner', datetime('now'), datetime('now')),
('sl-00000002-0000-4000-0000-000000000002', 'NOVICE', 'Novice', datetime('now'), datetime('now')),
('sl-00000003-0000-4000-0000-000000000003', 'INTERMEDIATE', 'Intermediate', datetime('now'), datetime('now')),
('sl-00000004-0000-4000-0000-000000000004', 'ADVANCED', 'Advanced', datetime('now'), datetime('now')),
('sl-00000005-0000-4000-0000-000000000005', 'EXPERT', 'Expert', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'ENUM_SKILL_LEVEL entity metadata and data installed' AS status;
SELECT COUNT(*) AS skill_level_count FROM enum_skill_level;
