-- =====================================================================
-- Entity: DISTRICT
-- Domain: GEOGRAPHY
-- Description: Districts or counties within states/provinces
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c', 'DISTRICT', 'District / County', 'Districts or counties within a state', 'GEOGRAPHY', 'district', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e6f','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','name','District / County Name','text',1,0,1,'Name of district or county',1, datetime('now'), datetime('now')),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e70','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','code','District Code','text',0,0,1,'Optional district code',2, datetime('now'), datetime('now')),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e71','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','state_id','State / Province','text',1,0,0,'FK to State',3, datetime('now'), datetime('now')),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e72','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','population','Population','number',0,0,0,'Population estimate',4, datetime('now'), datetime('now')),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e73','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',5, datetime('now'), datetime('now')),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e74','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','description','Description','text',0,0,0,'Free-text',6, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- DISTRICT belongs to STATE (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0003','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','OneToMany','districts','state_id','A state has multiple districts', datetime('now'));

-- DISTRICT has many CITIES (1:Many) - defined in CITY entity

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-dt-create','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','create','Create District','Create a district record','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-dt-read','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','read','Read District','Read district by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-dt-update','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','update','Update District','Update district','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-dt-delete','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','delete','Delete District','Delete district','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-dt-search','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','search','Search Districts','Search districts','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-dt-validate-code','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','validate_district_code','Validate District Code','Ensure unique within state','[{"name":"district_code","type":"text"},{"name":"state_id","type":"text"}]','boolean',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-dt-create','func-dt-create','sql','sp_create_district',1, datetime('now')),
('h-dt-read','func-dt-read','sql','sp_read_district',1, datetime('now')),
('h-dt-update','func-dt-update','sql','sp_update_district',1, datetime('now')),
('h-dt-delete','func-dt-delete','sql','sp_delete_district',1, datetime('now')),
('h-dt-search','func-dt-search','sql','sp_search_district',1, datetime('now')),
('h-dt-validate-code','func-dt-validate-code','sql','sp_validate_district_code',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for DISTRICT

-- =========================================
-- 7. Entity Data (from district data files)
-- =========================================
-- NOTE: The full district data is extensive. For complete data, reference:
-- C:\Users\fwyog\oom\metadata\data\016-india-districts.sql
-- C:\Users\fwyog\oom\metadata\data\020-madhya-pradesh-districts.sql
-- C:\Users\fwyog\oom\metadata\data\021-other-states-districts.sql
--
-- The data files include:
-- - Rajasthan: 50 districts
-- - Madhya Pradesh: 55 districts
-- - Maharashtra: 36 districts
-- - Manipur: 16 districts
-- - Meghalaya: 12 districts
-- - Tamil Nadu: 38 districts
-- And many more...
--
-- Sample data included below. For production use, execute the full data files.

INSERT OR IGNORE INTO district (id, name, code, state_id, population, area_sq_km, description, created_at, updated_at)
VALUES
-- Sample: Rajasthan Districts
('d00000000-0218-4000-0000-0000000002180000000', 'Jaipur', 'JPR', 's00000000-0048-4000-0000-000480000000', 6626178, 14068, 'Capital district with Pink City', datetime('now'), datetime('now')),
('d00000000-021d-4000-0000-00000000021d0000000', 'Jodhpur', 'JDH', 's00000000-0048-4000-0000-000480000000', 3687165, 22850, 'Blue City district', datetime('now'), datetime('now')),
('d00000000-0221-4000-0000-0000000002210000000', 'Kota', 'KOT', 's00000000-0048-4000-0000-000480000000', 1950491, 12436, 'Industrial and education hub', datetime('now'), datetime('now')),
('d00000000-0231-4000-0000-0000000002310000000', 'Udaipur', 'UDP', 's00000000-0048-4000-0000-000480000000', 3068420, 17279, 'City of Lakes district', datetime('now'), datetime('now')),
('d00000000-0203-4000-0000-0000000002030000000', 'Ajmer', 'AJM', 's00000000-0048-4000-0000-000480000000', 2583052, 8481, 'Historic city with Ajmer Sharif Dargah', datetime('now'), datetime('now')),

-- Sample: Madhya Pradesh Districts
('d00000000-0122-4000-0000-0000000001220000000', 'Bhopal', 'BPL', 's00000000-0040-4000-0000-000400000000', 2371061, 2772, 'Capital district', datetime('now'), datetime('now')),
('d00000000-012f-4000-0000-00000000012f0000000', 'Indore', 'IDR', 's00000000-0040-4000-0000-000400000000', 3272335, 3898, 'Commercial capital', datetime('now'), datetime('now')),
('d00000000-0130-4000-0000-0000000001300000000', 'Jabalpur', 'JBP', 's00000000-0040-4000-0000-000400000000', 2460714, 10160, 'Marble city', datetime('now'), datetime('now')),
('d00000000-012c-4000-0000-00000000012c0000000', 'Gwalior', 'GWL', 's00000000-0040-4000-0000-000400000000', 2030543, 4564, 'Historic fort city', datetime('now'), datetime('now')),
('d00000000-014b-4000-0000-00000000014b0000000', 'Ujjain', 'UJN', 's00000000-0040-4000-0000-000400000000', 1986864, 6091, 'Religious city', datetime('now'), datetime('now')),

-- Sample: Maharashtra Districts
('d00000000-0161-4000-0000-0000000001610000000', 'Mumbai', 'MUM', 's00000000-0041-4000-0000-000410000000', 12442373, 603, 'Financial capital', datetime('now'), datetime('now')),
('d00000000-016a-4000-0000-00000000016a0000000', 'Pune', 'PUN', 's00000000-0041-4000-0000-000410000000', 9429408, 15643, 'Oxford of the East', datetime('now'), datetime('now')),
('d00000000-0163-4000-0000-0000000001630000000', 'Nagpur', 'NAG', 's00000000-0041-4000-0000-000410000000', 4653570, 9892, 'Orange city', datetime('now'), datetime('now')),
('d00000000-0171-4000-0000-0000000001710000000', 'Thane', 'THA', 's00000000-0041-4000-0000-000410000000', 11060148, 9558, 'City of lakes', datetime('now'), datetime('now')),

-- Sample: Tamil Nadu Districts
('d00000000-0193-4000-0000-0000000001930000000', 'Chennai', 'CHN', 's00000000-004a-4000-0000-0004a0000000', 7088000, 426, 'Capital district', datetime('now'), datetime('now')),
('d00000000-0194-4000-0000-0000000001940000000', 'Coimbatore', 'COI', 's00000000-004a-4000-0000-0004a0000000', 3458045, 7469, 'Manchester of South India', datetime('now'), datetime('now')),
('d00000000-019d-4000-0000-00000000019d0000000', 'Madurai', 'MAD', 's00000000-004a-4000-0000-0004a0000000', 3038252, 3741, 'Temple city', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'DISTRICT entity metadata and sample data installed' AS status;
SELECT COUNT(*) AS district_count FROM district;
SELECT 'NOTE: For complete district data, execute district data files from C:\Users\fwyog\oom\metadata\data\' AS note;
