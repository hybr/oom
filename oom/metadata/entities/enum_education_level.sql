-- =====================================================================
-- Entity: ENUM_EDUCATION_LEVEL
-- Domain: EDUCATION
-- Description: Enumeration of education levels
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d', 'ENUM_EDUCATION_LEVEL', 'Education Level', 'Enumeration of education levels', 'EDUCATION', 'enum_education_level', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('attr-edlvl-001','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','code','Code','text',1,1,1,'Unique code for education level (e.g., BACHELOR)',1, datetime('now'), datetime('now')),
('attr-edlvl-002','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','name','Name','text',1,0,1,'Human-readable education level name',2, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- No direct relationships (enum type)

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-edlvl-create','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','create','Create Education Level','Create a new education level','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-edlvl-read','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','read','Read Education Level','Read education level by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-edlvl-update','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','update','Update Education Level','Update education level by id','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-edlvl-delete','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','delete','Delete Education Level','Delete by id','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-edlvl-search','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','search','Search Education Levels','Search with filters','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-edlvl-list-all','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','list_all','List All Education Levels','List all available education levels','[]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-edlvl-create','func-edlvl-create','sql','sp_create_enum_education_level',1, datetime('now')),
('h-edlvl-read','func-edlvl-read','sql','sp_read_enum_education_level',1, datetime('now')),
('h-edlvl-update','func-edlvl-update','sql','sp_update_enum_education_level',1, datetime('now')),
('h-edlvl-delete','func-edlvl-delete','sql','sp_delete_enum_education_level',1, datetime('now')),
('h-edlvl-search','func-edlvl-search','sql','sp_search_enum_education_level',1, datetime('now')),
('h-edlvl-list-all','func-edlvl-list-all','sql','sp_list_all_enum_education_level',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity, created_at) VALUES
('vr-edlvl-code-format','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','attr-edlvl-001','code_uppercase','value = UPPER(value)','Education level code must be uppercase','error', datetime('now')),
('vr-edlvl-name-length','7d3f9e2b-4a8c-4f91-b6d5-2e8a9c1f3b4d','attr-edlvl-002','name_min_length','length(value) >= 3','Education level name must be at least 3 characters','error', datetime('now'));

-- =========================================
-- 7. Entity Data
-- =========================================
INSERT OR IGNORE INTO enum_education_level (id, code, name, created_at, updated_at)
VALUES
('e1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'PRIMARY', 'Primary School', datetime('now'), datetime('now')),
('e2a3b4c5-d6e7-4f8a-9b0c-1d2e3f4a5b6c', 'SECONDARY', 'Secondary School', datetime('now'), datetime('now')),
('e3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'HIGH_SCHOOL', 'High School', datetime('now'), datetime('now')),
('e4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'DIPLOMA', 'Diploma', datetime('now'), datetime('now')),
('e5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'ASSOCIATE', 'Associate Degree', datetime('now'), datetime('now')),
('e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'BACHELOR', 'Bachelor Degree', datetime('now'), datetime('now')),
('e7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'MASTER', 'Master Degree', datetime('now'), datetime('now')),
('e8a9b0c1-d2e3-4f4a-5b6c-7d8e9f0a1b2c', 'DOCTORATE', 'Doctorate/PhD', datetime('now'), datetime('now')),
('e9a0b1c2-d3e4-4f5a-6b7c-8d9e0f1a2b3c', 'POST_DOCTORATE', 'Post-Doctorate', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'ENUM_EDUCATION_LEVEL entity metadata and data installed' AS status;
SELECT COUNT(*) AS education_level_count FROM enum_education_level;
