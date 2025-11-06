-- =====================================================================
-- Entity: STATE
-- Domain: GEOGRAPHY
-- Description: States, provinces, and first-level administrative divisions within countries
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c', 'STATE', 'State / Province', 'States or provinces within a country', 'GEOGRAPHY', 'state', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0011','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','name','State / Province Name','text',1,0,1,'State or province name',1, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0012','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','code','State Code','text',0,0,1,'Optional state code',2, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0013','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','country_id','Country','text',1,0,0,'FK to Country',3, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0014','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','population','Population','number',0,0,0,'Population estimate',4, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0015','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',5, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0016','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','gdp_usd','GDP (USD)','number',0,0,0,'GDP in USD',6, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0017','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','capital','Capital City','text',0,0,0,'Name of state capital',7, datetime('now'), datetime('now')),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0018','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','description','Description','text',0,0,0,'Free-text',8, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- STATE belongs to COUNTRY (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0002','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','OneToMany','states','country_id','A country has multiple states/provinces', datetime('now'));

-- STATE has many DISTRICTS (1:Many) - defined in DISTRICT entity

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-st-create','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','create','Create State','Create a state record','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-st-read','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','read','Read State','Read state by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-st-update','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','update','Update State','Update state','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-st-delete','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','delete','Delete State','Delete state','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-st-search','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','search','Search States','Search states','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-st-validate-code','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','validate_state_code','Validate State Code','Ensure unique within country','[{"name":"state_code","type":"text"},{"name":"country_id","type":"text"}]','boolean',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-st-create','func-st-create','sql','sp_create_state',1, datetime('now')),
('h-st-read','func-st-read','sql','sp_read_state',1, datetime('now')),
('h-st-update','func-st-update','sql','sp_update_state',1, datetime('now')),
('h-st-delete','func-st-delete','sql','sp_delete_state',1, datetime('now')),
('h-st-search','func-st-search','sql','sp_search_state',1, datetime('now')),
('h-st-validate-code','func-st-validate-code','sql','sp_validate_state_code',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for STATE

-- =========================================
-- 7. Entity Data (from 015-states-provinces.sql)
-- =========================================
-- NOTE: The full state data is extensive. For complete data, reference:
-- C:\Users\fwyog\oom\metadata\data\015-states-provinces.sql
--
-- The data file includes:
-- - United States (50 States + DC)
-- - India (28 States + 8 Union Territories)
-- - Canada (10 Provinces + 3 Territories)
-- - China (23 Provinces + 5 Autonomous Regions + 4 Municipalities + 2 SARs)
-- - Australia (6 States + 2 Territories)
-- - Brazil (26 States + 1 Federal District)
-- - Mexico (32 States)
-- - Germany (16 States / Länder)
-- - United Kingdom (4 Countries)
-- And many more...
--
-- Sample data included below. For production use, execute the full data file.

INSERT OR IGNORE INTO state (id, name, code, country_id, population, area_sq_km, gdp_usd, capital, description, created_at, updated_at)
VALUES
-- Sample: Indian States (for complete list, see data file)
('s00000000-0048-4000-0000-000480000000', 'Rajasthan', 'RJ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 68500000, 342239, NULL, 'Jaipur', 'Land of Kings', datetime('now'), datetime('now')),
('s00000000-0040-4000-0000-000400000000', 'Madhya Pradesh', 'MP', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 72600000, 308245, NULL, 'Bhopal', 'Heart of India', datetime('now'), datetime('now')),
('s00000000-0041-4000-0000-000410000000', 'Maharashtra', 'MH', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 112400000, 307713, NULL, 'Mumbai', 'Economic powerhouse', datetime('now'), datetime('now')),
('s00000000-004a-4000-0000-0004a0000000', 'Tamil Nadu', 'TN', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 72100000, 130060, NULL, 'Chennai', 'Land of Tamils', datetime('now'), datetime('now')),
('s00000000-0053-4000-0000-000530000000', 'Delhi', 'DL', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 16800000, 1484, NULL, 'New Delhi', 'National Capital Territory', datetime('now'), datetime('now')),

-- Sample: US States (for complete list, see data file)
('s00000000-0005-4000-0000-000050000000', 'California', 'CA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 39500000, 423967, NULL, 'Sacramento', 'Golden State', datetime('now'), datetime('now')),
('s00000000-0020-4000-0000-000200000000', 'New York', 'NY', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 20200000, 141297, NULL, 'Albany', 'Empire State', datetime('now'), datetime('now')),
('s00000000-002b-4000-0000-0002b0000000', 'Texas', 'TX', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 29100000, 695662, NULL, 'Austin', 'Lone Star State', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'STATE entity metadata and sample data installed' AS status;
SELECT COUNT(*) AS state_count FROM state;
SELECT 'NOTE: For complete state data, execute C:\Users\fwyog\oom\metadata\data\015-states-provinces.sql' AS note;
