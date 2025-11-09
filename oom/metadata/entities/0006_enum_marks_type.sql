-- =====================================================================
-- Entity: ENUM_MARKS_TYPE
-- Domain: EDUCATION
-- Description: Common examination marking systems used worldwide
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f', 'ENUM_MARKS_TYPE', 'Marks Type', 'Common examination marking systems used worldwide', 'EDUCATION', 'enum_marks_type', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('attr-mrktyp-001','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','code','Code','text',1,1,1,'Unique code for marks type (e.g., PERCENTAGE)',1, datetime('now'), datetime('now')),
('attr-mrktyp-002','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','name','Name','text',1,0,1,'Human-readable marks type name',2, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- No direct relationships (enum type)

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-mrktyp-create','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','create','Create Marks Type','Create a new marks type','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-mrktyp-read','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','read','Read Marks Type','Read marks type by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-mrktyp-update','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','update','Update Marks Type','Update marks type by id','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-mrktyp-delete','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','delete','Delete Marks Type','Delete by id','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-mrktyp-search','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','search','Search Marks Types','Search with filters','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-mrktyp-list-all','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','list_all','List All Marks Types','List all available marks types','[]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-mrktyp-create','func-mrktyp-create','sql','sp_create_enum_marks_type',1, datetime('now')),
('h-mrktyp-read','func-mrktyp-read','sql','sp_read_enum_marks_type',1, datetime('now')),
('h-mrktyp-update','func-mrktyp-update','sql','sp_update_enum_marks_type',1, datetime('now')),
('h-mrktyp-delete','func-mrktyp-delete','sql','sp_delete_enum_marks_type',1, datetime('now')),
('h-mrktyp-search','func-mrktyp-search','sql','sp_search_enum_marks_type',1, datetime('now')),
('h-mrktyp-list-all','func-mrktyp-list-all','sql','sp_list_all_enum_marks_type',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity, created_at) VALUES
('vr-mrktyp-code-format','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','attr-mrktyp-001','code_uppercase','value = UPPER(value)','Marks type code must be uppercase','error', datetime('now')),
('vr-mrktyp-name-length','8e4a0f3c-5b9d-4a02-c7e6-3f9b0d2e4c5f','attr-mrktyp-002','name_min_length','length(value) >= 3','Marks type name must be at least 3 characters','error', datetime('now'));

-- =========================================
-- 7. Entity Data
-- =========================================
INSERT OR IGNORE INTO enum_marks_type (id, code, name, created_at, updated_at)
VALUES
('mt-00000001-0000-4000-0000-000000000001', 'PERCENTAGE', 'Percentage (0-100%)', datetime('now'), datetime('now')),
('mt-00000002-0000-4000-0000-000000000002', 'GRADE_LETTER', 'Letter Grade (A, B, C, D, F)', datetime('now'), datetime('now')),
('mt-00000003-0000-4000-0000-000000000003', 'GRADE_PLUS_MINUS', 'Letter Grade with +/- (A+, A, A-, B+, etc.)', datetime('now'), datetime('now')),
('mt-00000004-0000-4000-0000-000000000004', 'GPA_4', 'GPA 4.0 Scale', datetime('now'), datetime('now')),
('mt-00000005-0000-4000-0000-000000000005', 'GPA_5', 'GPA 5.0 Scale', datetime('now'), datetime('now')),
('mt-00000006-0000-4000-0000-000000000006', 'GPA_10', 'GPA 10.0 Scale (CGPA)', datetime('now'), datetime('now')),
('mt-00000007-0000-4000-0000-000000000007', 'MARKS_OUT_OF', 'Marks out of Total (e.g., 85/100)', datetime('now'), datetime('now')),
('mt-00000008-0000-4000-0000-000000000008', 'PASS_FAIL', 'Pass/Fail', datetime('now'), datetime('now')),
('mt-00000009-0000-4000-0000-000000000009', 'DISTINCTION', 'Distinction/Merit/Pass', datetime('now'), datetime('now')),
('mt-00000010-0000-4000-0000-000000000010', 'GRADE_1_TO_9', 'Grade 1-9 Scale (UK GCSE)', datetime('now'), datetime('now')),
('mt-00000011-0000-4000-0000-000000000011', 'ECTS', 'ECTS Grades (A, B, C, D, E, F)', datetime('now'), datetime('now')),
('mt-00000012-0000-4000-0000-000000000012', 'PERCENTILE', 'Percentile Rank', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'ENUM_MARKS_TYPE entity metadata and data installed' AS status;
SELECT COUNT(*) AS marks_type_count FROM enum_marks_type;
