-- =====================================================================
-- Entity: CONTINENT
-- Domain: GEOGRAPHY
-- Description: Continents of the world
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523', 'CONTINENT', 'Continent', 'Continents of the world', 'GEOGRAPHY', 'continent', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b111','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','name','Continent Name','text',1,1,1,'Human-readable continent name',1, datetime('now'), datetime('now')),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b112','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','code','Continent Code','text',1,1,1,'Short code for continent (e.g., AS, EU)',2, datetime('now'), datetime('now')),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b113','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','area_sq_km','Area (sq km)','number',0,0,0,'Geographic area',3, datetime('now'), datetime('now')),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b114','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','population','Population','number',0,0,0,'Total population estimate',4, datetime('now'), datetime('now')),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b115','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','gdp_usd','GDP (USD)','number',0,0,0,'Aggregate GDP estimate',5, datetime('now'), datetime('now')),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b116','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','description','Description','text',0,0,0,'Free-text description',6, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- Relationship defined in COUNTRY entity (CONTINENT → COUNTRY: OneToMany)

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-ct-create','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','create','Create Continent','Create a new continent','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-ct-read','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','read','Read Continent','Read continent by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-ct-update','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','update','Update Continent','Update continent by id','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-ct-delete','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','delete','Delete Continent','Delete by id','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-ct-search','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','search','Search Continents','Search with filters','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-ct-export','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','export','Export Continents','Export matched continents','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-ct-validate-code','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','validate_continent_code','Validate Continent Code','Ensure code uniqueness/format','[{"name":"code","type":"text"}]','boolean',1,1, datetime('now'), datetime('now')),
('func-ct-summary','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','get_continent_summary','Continent Summary','Return aggregated counts and stats','[{"name":"id","type":"text"}]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-ct-create','func-ct-create','sql','sp_create_continent',1, datetime('now')),
('h-ct-read','func-ct-read','sql','sp_read_continent',1, datetime('now')),
('h-ct-update','func-ct-update','sql','sp_update_continent',1, datetime('now')),
('h-ct-delete','func-ct-delete','sql','sp_delete_continent',1, datetime('now')),
('h-ct-search','func-ct-search','sql','sp_search_continent',1, datetime('now')),
('h-ct-export','func-ct-export','script','scripts/export_continents.py',1, datetime('now')),
('h-ct-validate-code','func-ct-validate-code','sql','sp_validate_continent_code',1, datetime('now')),
('h-ct-summary','func-ct-summary','sql','sp_get_continent_summary',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for CONTINENT

-- =========================================
-- 7. Entity Data
-- =========================================
INSERT OR IGNORE INTO continent (id, name, code, area_sq_km, population, gdp_usd, description, created_at, updated_at)
VALUES
('c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'Africa', 'AF', 30370000, 1340000000, 2960000000000, 'The second-largest and second-most populous continent', datetime('now'), datetime('now')),
('c2a3b4c5-d6e7-4f8a-9b0c-1d2e3f4a5b6c', 'Antarctica', 'AN', 14000000, 1000, 0, 'The southernmost continent containing the geographic South Pole', datetime('now'), datetime('now')),
('c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'Asia', 'AS', 44579000, 4640000000, 36000000000000, 'The largest and most populous continent', datetime('now'), datetime('now')),
('c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'Europe', 'EU', 10180000, 747000000, 23000000000000, 'A continent comprising the westernmost peninsulas of Eurasia', datetime('now'), datetime('now')),
('c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'North America', 'NA', 24709000, 592000000, 28000000000000, 'A continent in the Northern Hemisphere', datetime('now'), datetime('now')),
('c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'Oceania', 'OC', 8525989, 43000000, 1700000000000, 'A geographic region comprising Australasia, Melanesia, Micronesia, and Polynesia', datetime('now'), datetime('now')),
('c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'South America', 'SA', 17840000, 434000000, 3900000000000, 'A continent in the Western Hemisphere', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'CONTINENT entity metadata and data installed' AS status;
SELECT COUNT(*) AS continent_count FROM continent;
