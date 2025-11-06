-- =====================================================================
-- Entity: CITY
-- Domain: GEOGRAPHY
-- Description: Cities or towns within districts
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad', 'CITY', 'City / Town', 'Cities or towns within a district', 'GEOGRAPHY', 'city', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0011','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','name','City / Town Name','text',1,0,1,'Name of city or town',1, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0012','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','code','City Code','text',0,0,0,'Optional city code',2, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0021','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','district_id','District / County','text',1,0,0,'FK to District',3, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0013','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','state_id','State / Province','text',1,0,0,'FK to State',4, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0014','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','country_id','Country','text',1,0,0,'FK to Country',5, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0015','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','population','Population','number',0,0,0,'Population',6, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0016','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',7, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0017','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','latitude','Latitude','text',0,0,0,'Decimal degrees',8, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0018','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','longitude','Longitude','text',0,0,0,'Decimal degrees',9, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0019','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','timezone_id','Time Zone','text',0,0,0,'FK to Timezone',10, datetime('now'), datetime('now')),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0020','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','description','Description','text',0,0,0,'Free-text',11, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- CITY belongs to DISTRICT (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0009','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','OneToMany','cities','district_id','A district has multiple cities', datetime('now'));

-- CITY has many POSTAL_ADDRESSES (1:Many) - defined in POSTAL_ADDRESS entity

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-ci-create','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','create','Create City','Create a city','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-ci-read','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','read','Read City','Read city','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-ci-update','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','update','Update City','Update city','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-ci-delete','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','delete','Delete City','Delete city','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-ci-search','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','search','Search Cities','Search cities','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-ci-get-coords','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','get_city_coordinates','Get City Coordinates','Return lat/lon for city','[{"name":"city_id","type":"text"}]','json',1,1, datetime('now'), datetime('now')),
('func-ci-by-pop','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','get_cities_by_population','Get Cities by Population','Top cities by population','[{"name":"state_id","type":"text"},{"name":"limit","type":"number"}]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-ci-create','func-ci-create','sql','sp_create_city',1, datetime('now')),
('h-ci-read','func-ci-read','sql','sp_read_city',1, datetime('now')),
('h-ci-update','func-ci-update','sql','sp_update_city',1, datetime('now')),
('h-ci-delete','func-ci-delete','sql','sp_delete_city',1, datetime('now')),
('h-ci-search','func-ci-search','sql','sp_search_city',1, datetime('now')),
('h-ci-get-coords','func-ci-get-coords','api','https://api.geo.example.com/city_coords',1, datetime('now')),
('h-ci-by-pop','func-ci-by-pop','sql','sp_get_cities_by_population',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for CITY

-- =========================================
-- 7. Entity Data (from 017-rajasthan-cities.sql)
-- =========================================
-- NOTE: The full city data is extensive. For complete data, reference:
-- C:\Users\fwyog\oom\metadata\data\017-rajasthan-cities.sql
--
-- The data file includes:
-- - Rajasthan: 97 major cities across 50 districts
--   Including Jaipur, Jodhpur, Udaipur, Kota, Ajmer, Bikaner, and many more
--
-- Sample data included below. For production use, execute the full data file.

INSERT OR IGNORE INTO city (id, name, code, district_id, state_id, country_id, population, area_sq_km, latitude, longitude, description, created_at, updated_at)
VALUES
-- Sample: Major Rajasthan Cities
('c00000000-0028-4000-0000-000280000000', 'Jaipur', 'JPR', 'd00000000-0218-4000-0000-0000000002180000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 3046163, 467, '26.9124', '75.7873', 'Pink City, state capital with UNESCO heritage sites', datetime('now'), datetime('now')),
('c00000000-0034-4000-0000-000340000000', 'Jodhpur', 'JDH', 'd00000000-021d-4000-0000-00000000021d0000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1033918, 223, '26.2389', '73.0243', 'Blue City with Mehrangarh Fort, second largest city', datetime('now'), datetime('now')),
('c00000000-0039-4000-0000-000390000000', 'Kota', 'KOT', 'd00000000-0221-4000-0000-0000000002210000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1001694, 218, '25.2138', '75.8648', 'Industrial city and education hub for IIT-JEE coaching', datetime('now'), datetime('now')),
('c00000000-004e-4000-0000-0004e0000000', 'Udaipur', 'UDP', 'd00000000-0231-4000-0000-0000000002310000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 475150, 120, '24.5854', '73.7125', 'City of Lakes, Venice of the East, major tourist destination', datetime('now'), datetime('now')),
('c00000000-0001-4000-0000-000010000000', 'Ajmer', 'AJM', 'd00000000-0203-4000-0000-0000000002030000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 542321, 55, '26.4499', '74.6399', 'Historic city with Ajmer Sharif Dargah', datetime('now'), datetime('now')),
('c00000000-0013-4000-0000-000130000000', 'Bikaner', 'BKN', 'd00000000-020d-4000-0000-00000000020d0000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 644406, 155, '28.0229', '73.3119', 'Desert city famous for Junagarh Fort and snacks', datetime('now'), datetime('now')),
('c00000000-0010-4000-0000-000100000000', 'Bhilwara', 'BHI', 'd00000000-020c-4000-0000-00000000020c0000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 360009, 40, '25.3470', '74.6355', 'Textile city of India', datetime('now'), datetime('now')),
('c00000000-002b-4000-0000-0002b0000000', 'Jaisalmer', 'JSM', 'd00000000-0219-4000-0000-0000000002190000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 78214, 95, '26.9157', '70.9083', 'Golden City with desert fort and sand dunes', datetime('now'), datetime('now')),
('c00000000-0002-4000-0000-000020000000', 'Pushkar', 'PSK', 'd00000000-0203-4000-0000-0000000002030000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 21626, 10, '26.4897', '74.5511', 'Sacred town with Brahma Temple and holy lake', datetime('now'), datetime('now')),
('c00000000-004b-4000-0000-0004b0000000', 'Mount Abu', 'MBA', 'd00000000-022e-4000-0000-00000000022e0000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 22943, 18, '24.5925', '72.7156', 'Only hill station in Rajasthan with Dilwara temples', datetime('now'), datetime('now')),
('c00000000-0017-4000-0000-000170000000', 'Chittorgarh', 'CTG', 'd00000000-020f-4000-0000-00000000020f0000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 116406, 30, '24.8887', '74.6269', 'Historic fort city, symbol of Rajput valor', datetime('now'), datetime('now')),
('c00000000-0047-4000-0000-000470000000', 'Sikar', 'SKR', 'd00000000-022d-4000-0000-00000000022d0000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 236671, 48, '27.6119', '75.1397', 'Shekhawati region commercial hub', datetime('now'), datetime('now')),
('c00000000-0022-4000-0000-000220000000', 'Sri Ganganagar', 'SGN', 'd00000000-0216-4000-0000-0000000002160000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 249914, 60, '29.9033', '73.8772', 'Food basket of Rajasthan, agricultural hub', datetime('now'), datetime('now')),
('c00000000-003e-4000-0000-0003e0000000', 'Pali', 'PAL', 'd00000000-0225-4000-0000-0000000002250000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 229956, 50, '25.7711', '73.3234', 'Industrial city and textile hub', datetime('now'), datetime('now')),
('c00000000-003b-4000-0000-0003b0000000', 'Nagaur', 'NGR', 'd00000000-0223-4000-0000-0000000002230000000', 's00000000-0048-4000-0000-000480000000', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 100585, 25, '27.2020', '73.7340', 'Historic city with cattle fair and fort', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'CITY entity metadata and sample data installed' AS status;
SELECT COUNT(*) AS city_count FROM city;
SELECT 'NOTE: For complete city data, execute C:\Users\fwyog\oom\metadata\data\017-rajasthan-cities.sql' AS note;
