-- =====================================================================
-- Entity: COUNTRY
-- Domain: GEOGRAPHY
-- Description: Countries within continents
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'COUNTRY', 'Country', 'Countries within continents', 'GEOGRAPHY', 'country', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a001','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','name','Country Name','text',1,1,1,'Human-readable country name',1, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a002','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','code','Country Code','text',1,1,1,'Short code (e.g., IND)',2, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a003','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','continent_id','Continent','text',1,0,0,'FK to Continent',3, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a004','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','iso_alpha2','ISO Alpha-2 Code','text',1,1,0,'ISO 3166-1 alpha-2',4, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a005','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','iso_alpha3','ISO Alpha-3 Code','text',1,1,0,'ISO 3166-1 alpha-3',5, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a006','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','iso_numeric','ISO Numeric Code','number',0,0,0,'ISO numeric code',6, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a007','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','population','Population','number',0,0,0,'Population estimate',7, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a008','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',8, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a009','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','official_languages','official_languages','text',0,0,0,'Comma-separated official languages',9, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a010','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','currency_id','Currency','text',0,0,0,'FK to Currency',10, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a011','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','timezone_id','Time Zone','text',0,0,0,'FK to Timezone',11, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a012','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','gdp_usd','GDP (USD)','number',0,0,0,'GDP in USD',12, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a013','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','flag_url','Flag Image URL','text',0,0,0,'URL to flag image',13, datetime('now'), datetime('now')),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a014','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','description','Description','text',0,0,0,'Free-text',14, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0001','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','OneToMany','countries','continent_id','A continent has multiple countries', datetime('now')),
('rel-0002','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','OneToMany','states','country_id','A country has multiple states/provinces', datetime('now')),
('rel-0005','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','OneToMany','languages','country_id','A country can have multiple languages', datetime('now')),
('rel-0006','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','OneToMany','currencies','country_id','A country can have multiple currencies', datetime('now')),
('rel-0007','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','OneToMany','timezones','country_id','A country can have multiple timezones', datetime('now'));

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-cy-create','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','create','Create Country','Create country record','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-cy-read','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','read','Read Country','Read by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-cy-update','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','update','Update Country','Update country','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-cy-delete','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','delete','Delete Country','Delete country','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-cy-search','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','search','Search Countries','Search countries','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-cy-export','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','export','Export Countries','Export matched countries','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-cy-import','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','import','Import Countries CSV/JSON','Bulk import countries','[{"name":"file","type":"text"}]','json',1,1, datetime('now'), datetime('now')),
('func-cy-validate-iso','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','validate_country_iso','Validate Country ISO','Validate ISO alpha2/alpha3','[{"name":"iso_alpha2","type":"text"},{"name":"iso_alpha3","type":"text"}]','boolean',1,1, datetime('now'), datetime('now')),
('func-cy-gdp-summary','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','get_country_gdp_summary','Country GDP Summary','Aggregate GDP from states','[{"name":"country_id","type":"text"}]','number',1,1, datetime('now'), datetime('now')),
('func-cy-primary-tz','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','get_primary_timezone','Get Primary Timezone','Return primary timezone for country','[{"name":"country_id","type":"text"}]','text',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-cy-create','func-cy-create','sql','sp_create_country',1, datetime('now')),
('h-cy-read','func-cy-read','sql','sp_read_country',1, datetime('now')),
('h-cy-update','func-cy-update','sql','sp_update_country',1, datetime('now')),
('h-cy-delete','func-cy-delete','sql','sp_delete_country',1, datetime('now')),
('h-cy-search','func-cy-search','sql','sp_search_country',1, datetime('now')),
('h-cy-export','func-cy-export','script','scripts/export_countries.py',1, datetime('now')),
('h-cy-import','func-cy-import','script','scripts/import_countries.py',1, datetime('now')),
('h-cy-validate-iso','func-cy-validate-iso','api','https://api.example.com/validate_iso',1, datetime('now')),
('h-cy-gdp-summary','func-cy-gdp-summary','sql','sp_get_country_gdp_summary',1, datetime('now')),
('h-cy-primary-tz','func-cy-primary-tz','sql','sp_get_primary_timezone',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity, created_at) VALUES
('vr-country-iso-1','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a004','iso_alpha2_format','length(value) = 2','ISO alpha-2 must be 2 characters','error', datetime('now')),
('vr-country-iso-2','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a005','iso_alpha3_format','length(value) = 3','ISO alpha-3 must be 3 characters','error', datetime('now'));

-- =========================================
-- 7. Entity Data
-- =========================================
INSERT OR IGNORE INTO country (id, name, code, continent_id, iso_alpha2, iso_alpha3, iso_numeric, population, area_sq_km, official_languages, gdp_usd, flag_url, description, created_at, updated_at)
VALUES
-- AFRICA (continent_id: c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c)
('a1f2e3d4-c5b6-47a8-9b0c-1d2e3f4a5b6c', 'Algeria', 'DZA', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'DZ', 'DZA', 12, 44000000, 2381741, 'Arabic,Berber', 169000000000, NULL, 'Largest country in Africa', datetime('now'), datetime('now')),
('a2f3e4d5-c6b7-48a9-0b1c-2d3e4f5a6b7c', 'Egypt', 'EGY', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'EG', 'EGY', 818, 102000000, 1002450, 'Arabic', 404000000000, NULL, 'Ancient civilization along the Nile', datetime('now'), datetime('now')),
('a3f4e5d6-c7b8-49a0-1b2c-3d4e5f6a7b8c', 'Ethiopia', 'ETH', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'ET', 'ETH', 231, 115000000, 1104300, 'Amharic', 96000000000, NULL, 'Ancient African nation', datetime('now'), datetime('now')),
('a4f5e6d7-c8b9-40a1-2b3c-4d5e6f7a8b9c', 'Kenya', 'KEN', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'KE', 'KEN', 404, 54000000, 580367, 'English,Swahili', 106000000000, NULL, 'East African hub', datetime('now'), datetime('now')),
('a5f6e7d8-c9b0-41a2-3b4c-5d6e7f8a9b0c', 'Morocco', 'MAR', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'MA', 'MAR', 504, 37000000, 446550, 'Arabic,Berber', 124000000000, NULL, 'Gateway to Africa', datetime('now'), datetime('now')),
('a6f7e8d9-c0b1-42a3-4b5c-6d7e8f9a0b1c', 'Nigeria', 'NGA', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'NG', 'NGA', 566, 206000000, 923768, 'English', 448000000000, NULL, 'Most populous African country', datetime('now'), datetime('now')),
('a7f8e9d0-c1b2-43a4-5b6c-7d8e9f0a1b2c', 'South Africa', 'ZAF', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'ZA', 'ZAF', 710, 59000000, 1221037, 'English,Afrikaans,Zulu,Xhosa', 351000000000, NULL, 'Rainbow nation at the southern tip', datetime('now'), datetime('now')),
('a8f9e0d1-c2b3-44a5-6b7c-8d9e0f1a2b3c', 'Tanzania', 'TZA', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'TZ', 'TZA', 834, 60000000, 947303, 'Swahili,English', 63000000000, NULL, 'Home to Mount Kilimanjaro', datetime('now'), datetime('now')),
('a9f0e1d2-c3b4-45a6-7b8c-9d0e1f2a3b4c', 'Uganda', 'UGA', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'UG', 'UGA', 800, 46000000, 241038, 'English,Swahili', 37000000000, NULL, 'Pearl of Africa', datetime('now'), datetime('now')),
('a0f1e2d3-c4b5-46a7-8b9c-0d1e2f3a4b5c', 'Ghana', 'GHA', 'c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'GH', 'GHA', 288, 31000000, 238533, 'English', 68000000000, NULL, 'First sub-Saharan country to gain independence', datetime('now'), datetime('now')),
-- ASIA (continent_id: c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c)
('b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'China', 'CHN', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'CN', 'CHN', 156, 1400000000, 9596961, 'Mandarin', 17700000000000, NULL, 'Most populous country in the world', datetime('now'), datetime('now')),
('b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'India', 'IND', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'IN', 'IND', 356, 1380000000, 3287263, 'Hindi,English', 3200000000000, NULL, 'Largest democracy in the world', datetime('now'), datetime('now')),
('b3a4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Indonesia', 'IDN', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'ID', 'IDN', 360, 273000000, 1904569, 'Indonesian', 1100000000000, NULL, 'Largest archipelagic country', datetime('now'), datetime('now')),
('b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Japan', 'JPN', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'JP', 'JPN', 392, 126000000, 377975, 'Japanese', 5100000000000, NULL, 'Land of the Rising Sun', datetime('now'), datetime('now')),
('b5a6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'South Korea', 'KOR', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'KR', 'KOR', 410, 52000000, 100210, 'Korean', 1600000000000, NULL, 'Technological powerhouse', datetime('now'), datetime('now')),
('b6a7c8d9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Pakistan', 'PAK', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'PK', 'PAK', 586, 220000000, 881913, 'Urdu,English', 278000000000, NULL, 'Islamic Republic of Pakistan', datetime('now'), datetime('now')),
('b7a8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Bangladesh', 'BGD', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'BD', 'BGD', 50, 165000000, 148460, 'Bengali', 324000000000, NULL, 'Land of rivers', datetime('now'), datetime('now')),
('b8a9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Philippines', 'PHL', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'PH', 'PHL', 608, 110000000, 300000, 'Filipino,English', 377000000000, NULL, 'Pearl of the Orient Seas', datetime('now'), datetime('now')),
('b9a0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Vietnam', 'VNM', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'VN', 'VNM', 704, 97000000, 331212, 'Vietnamese', 343000000000, NULL, 'Socialist Republic of Vietnam', datetime('now'), datetime('now')),
('b0a1c2d3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Thailand', 'THA', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'TH', 'THA', 764, 70000000, 513120, 'Thai', 506000000000, NULL, 'Land of Smiles', datetime('now'), datetime('now')),
('b1a2c3d4-e5f6-47a8-9b0c-2d3e4f5a6b7c', 'Turkey', 'TUR', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'TR', 'TUR', 792, 84000000, 783562, 'Turkish', 754000000000, NULL, 'Bridge between Europe and Asia', datetime('now'), datetime('now')),
('b2a3c4d5-e6f7-48a9-0b1c-3d4e5f6a7b8c', 'Iran', 'IRN', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'IR', 'IRN', 364, 84000000, 1648195, 'Persian', 445000000000, NULL, 'Islamic Republic of Iran', datetime('now'), datetime('now')),
('b3a4c5d6-e7f8-49a0-1b2c-4d5e6f7a8b9c', 'Saudi Arabia', 'SAU', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'SA', 'SAU', 682, 35000000, 2149690, 'Arabic', 793000000000, NULL, 'Home of Islam holy cities', datetime('now'), datetime('now')),
('b4a5c6d7-e8f9-40a1-2b3c-5d6e7f8a9b0c', 'Iraq', 'IRQ', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'IQ', 'IRQ', 368, 40000000, 438317, 'Arabic,Kurdish', 234000000000, NULL, 'Cradle of civilization', datetime('now'), datetime('now')),
('b5a6c7d8-e9f0-41a2-3b4c-6d7e8f9a0b1c', 'Malaysia', 'MYS', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'MY', 'MYS', 458, 32000000, 330803, 'Malay', 365000000000, NULL, 'Truly Asia', datetime('now'), datetime('now')),
('b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c', 'Singapore', 'SGP', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'SG', 'SGP', 702, 6000000, 728, 'English,Malay,Mandarin,Tamil', 372000000000, NULL, 'Lion City', datetime('now'), datetime('now')),
('b7a8c9d0-e1f2-43a4-5b6c-8d9e0f1a2b3c', 'Israel', 'ISR', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'IL', 'ISR', 376, 9000000, 22072, 'Hebrew,Arabic', 395000000000, NULL, 'Start-up Nation', datetime('now'), datetime('now')),
('b8a9c0d1-e2f3-44a5-6b7c-9d0e1f2a3b4c', 'United Arab Emirates', 'ARE', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'AE', 'ARE', 784, 10000000, 83600, 'Arabic', 421000000000, NULL, 'Federation of seven emirates', datetime('now'), datetime('now')),
('b9a0c1d2-e3f4-45a6-7b8c-0d1e2f3a4b5c', 'Sri Lanka', 'LKA', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'LK', 'LKA', 144, 22000000, 65610, 'Sinhala,Tamil', 84000000000, NULL, 'Pearl of the Indian Ocean', datetime('now'), datetime('now')),
('b0a1c2d3-e4f5-46a7-8b9c-1d2e3f4a5b6c', 'Myanmar', 'MMR', 'c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'MM', 'MMR', 104, 54000000, 676578, 'Burmese', 76000000000, NULL, 'Land of Golden Pagodas', datetime('now'), datetime('now')),
-- EUROPE (continent_id: c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c)
('e1f2a3b4-c5d6-47e8-9f0a-1b2c3d4e5f6a', 'Russia', 'RUS', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'RU', 'RUS', 643, 146000000, 17098246, 'Russian', 1700000000000, NULL, 'Largest country by area', datetime('now'), datetime('now')),
('e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'Germany', 'DEU', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'DE', 'DEU', 276, 83000000, 357022, 'German', 3800000000000, NULL, 'Economic powerhouse of Europe', datetime('now'), datetime('now')),
('e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'United Kingdom', 'GBR', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'GB', 'GBR', 826, 67000000, 242495, 'English', 2800000000000, NULL, 'Island nation of Great Britain', datetime('now'), datetime('now')),
('e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'France', 'FRA', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'FR', 'FRA', 250, 67000000, 551695, 'French', 2600000000000, NULL, 'Hexagon with rich culture', datetime('now'), datetime('now')),
('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'Italy', 'ITA', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'IT', 'ITA', 380, 60000000, 301340, 'Italian', 2000000000000, NULL, 'Boot-shaped peninsula', datetime('now'), datetime('now')),
('e6f7a8b9-c0d1-42e3-4f5a-6b7c8d9e0f1a', 'Spain', 'ESP', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'ES', 'ESP', 724, 47000000, 505992, 'Spanish', 1300000000000, NULL, 'Iberian Peninsula nation', datetime('now'), datetime('now')),
('e7f8a9b0-c1d2-43e4-5f6a-7b8c9d0e1f2a', 'Poland', 'POL', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'PL', 'POL', 616, 38000000, 312679, 'Polish', 595000000000, NULL, 'Heart of Europe', datetime('now'), datetime('now')),
('e8f9a0b1-c2d3-44e5-6f7a-8b9c0d1e2f3a', 'Ukraine', 'UKR', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'UA', 'UKR', 804, 44000000, 603628, 'Ukrainian', 155000000000, NULL, 'Breadbasket of Europe', datetime('now'), datetime('now')),
('e9f0a1b2-c3d4-45e6-7f8a-9b0c1d2e3f4a', 'Romania', 'ROU', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'RO', 'ROU', 642, 19000000, 238397, 'Romanian', 250000000000, NULL, 'Land of Dracula', datetime('now'), datetime('now')),
('e0f1a2b3-c4d5-46e7-8f9a-0b1c2d3e4f5a', 'Netherlands', 'NLD', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'NL', 'NLD', 528, 17000000, 41543, 'Dutch', 909000000000, NULL, 'Land of windmills and tulips', datetime('now'), datetime('now')),
('e1f2a3b4-c5d6-47e8-9f0a-2b3c4d5e6f7a', 'Belgium', 'BEL', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'BE', 'BEL', 56, 11000000, 30528, 'Dutch,French,German', 529000000000, NULL, 'Heart of European Union', datetime('now'), datetime('now')),
('e2f3a4b5-c6d7-48e9-0f1a-3b4c5d6e7f8a', 'Greece', 'GRC', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'GR', 'GRC', 300, 11000000, 131957, 'Greek', 189000000000, NULL, 'Cradle of Western civilization', datetime('now'), datetime('now')),
('e3f4a5b6-c7d8-49e0-1f2a-4b5c6d7e8f9a', 'Portugal', 'PRT', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'PT', 'PRT', 620, 10000000, 92212, 'Portuguese', 228000000000, NULL, 'Age of Discovery pioneer', datetime('now'), datetime('now')),
('e4f5a6b7-c8d9-40e1-2f3a-5b6c7d8e9f0a', 'Sweden', 'SWE', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'SE', 'SWE', 752, 10000000, 450295, 'Swedish', 531000000000, NULL, 'Nordic welfare state', datetime('now'), datetime('now')),
('e5f6a7b8-c9d0-41e2-3f4a-6b7c8d9e0f1a', 'Austria', 'AUT', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'AT', 'AUT', 40, 9000000, 83879, 'German', 446000000000, NULL, 'Alpine republic', datetime('now'), datetime('now')),
('e6f7a8b9-c0d1-42e3-4f5a-7b8c9d0e1f2a', 'Switzerland', 'CHE', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'CH', 'CHE', 756, 9000000, 41285, 'German,French,Italian', 703000000000, NULL, 'Alpine banking center', datetime('now'), datetime('now')),
('e7f8a9b0-c1d2-43e4-5f6a-8b9c0d1e2f3a', 'Denmark', 'DNK', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'DK', 'DNK', 208, 6000000, 42933, 'Danish', 356000000000, NULL, 'Happiest country', datetime('now'), datetime('now')),
('e8f9a0b1-c2d3-44e5-6f7a-9b0c1d2e3f4a', 'Finland', 'FIN', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'FI', 'FIN', 246, 6000000, 338424, 'Finnish,Swedish', 269000000000, NULL, 'Land of a thousand lakes', datetime('now'), datetime('now')),
('e9f0a1b2-c3d4-45e6-7f8a-0b1c2d3e4f5a', 'Norway', 'NOR', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'NO', 'NOR', 578, 5000000, 385207, 'Norwegian', 403000000000, NULL, 'Land of the midnight sun', datetime('now'), datetime('now')),
('e0f1a2b3-c4d5-46e7-8f9a-1b2c3d4e5f6a', 'Ireland', 'IRL', 'c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'IE', 'IRL', 372, 5000000, 70273, 'English,Irish', 425000000000, NULL, 'Emerald Isle', datetime('now'), datetime('now')),
-- NORTH AMERICA (continent_id: c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c)
('n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'United States', 'USA', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'US', 'USA', 840, 331000000, 9833517, 'English', 21000000000000, NULL, 'Superpower with 50 states', datetime('now'), datetime('now')),
('n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Canada', 'CAN', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'CA', 'CAN', 124, 38000000, 9984670, 'English,French', 1700000000000, NULL, 'Second largest country by area', datetime('now'), datetime('now')),
('n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Mexico', 'MEX', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'MX', 'MEX', 484, 129000000, 1964375, 'Spanish', 1300000000000, NULL, 'Land of Aztecs and Mayans', datetime('now'), datetime('now')),
('n4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Cuba', 'CUB', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'CU', 'CUB', 192, 11000000, 109884, 'Spanish', 100000000000, NULL, 'Largest Caribbean island', datetime('now'), datetime('now')),
('n5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Guatemala', 'GTM', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'GT', 'GTM', 320, 18000000, 108889, 'Spanish', 77000000000, NULL, 'Heart of Mayan civilization', datetime('now'), datetime('now')),
('n6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'Haiti', 'HTI', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'HT', 'HTI', 332, 11000000, 27750, 'French,Haitian Creole', 14000000000, NULL, 'First Black republic', datetime('now'), datetime('now')),
('n7a8b9c0-d1e2-43f4-5a6b-7c8d9e0f1a2b', 'Dominican Republic', 'DOM', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'DO', 'DOM', 214, 11000000, 48671, 'Spanish', 89000000000, NULL, 'Caribbean paradise', datetime('now'), datetime('now')),
('n8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'Honduras', 'HND', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'HN', 'HND', 340, 10000000, 112492, 'Spanish', 25000000000, NULL, 'Central American nation', datetime('now'), datetime('now')),
('n9a0b1c2-d3e4-45f6-7a8b-9c0d1e2f3a4b', 'Nicaragua', 'NIC', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'NI', 'NIC', 558, 7000000, 130373, 'Spanish', 12000000000, NULL, 'Land of lakes and volcanoes', datetime('now'), datetime('now')),
('n0a1b2c3-d4e5-46f7-8a9b-0c1d2e3f4a5b', 'Costa Rica', 'CRI', 'c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'CR', 'CRI', 188, 5000000, 51100, 'Spanish', 62000000000, NULL, 'Pura Vida nation', datetime('now'), datetime('now')),
-- OCEANIA (continent_id: c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c)
('o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Australia', 'AUS', 'c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'AU', 'AUS', 36, 26000000, 7692024, 'English', 1400000000000, NULL, 'Island continent Down Under', datetime('now'), datetime('now')),
('o2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'New Zealand', 'NZL', 'c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'NZ', 'NZL', 554, 5000000, 268021, 'English,Maori', 207000000000, NULL, 'Land of the Long White Cloud', datetime('now'), datetime('now')),
('o3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Papua New Guinea', 'PNG', 'c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'PG', 'PNG', 598, 9000000, 462840, 'English,Tok Pisin', 24000000000, NULL, 'Most linguistically diverse', datetime('now'), datetime('now')),
('o4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Fiji', 'FJI', 'c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'FJ', 'FJI', 242, 900000, 18272, 'English,Fijian', 5000000000, NULL, 'Paradise island nation', datetime('now'), datetime('now')),
('o5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Solomon Islands', 'SLB', 'c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'SB', 'SLB', 90, 700000, 28896, 'English', 1500000000, NULL, 'Melanesian archipelago', datetime('now'), datetime('now')),
-- SOUTH AMERICA (continent_id: c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c)
('s1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Brazil', 'BRA', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'BR', 'BRA', 76, 213000000, 8515767, 'Portuguese', 1800000000000, NULL, 'Largest country in South America', datetime('now'), datetime('now')),
('s2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Argentina', 'ARG', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'AR', 'ARG', 32, 45000000, 2780400, 'Spanish', 450000000000, NULL, 'Land of tango and gauchos', datetime('now'), datetime('now')),
('s3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Colombia', 'COL', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'CO', 'COL', 170, 51000000, 1141748, 'Spanish', 323000000000, NULL, 'Gateway to South America', datetime('now'), datetime('now')),
('s4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Peru', 'PER', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'PE', 'PER', 604, 33000000, 1285216, 'Spanish,Quechua', 227000000000, NULL, 'Home of Machu Picchu', datetime('now'), datetime('now')),
('s5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Venezuela', 'VEN', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'VE', 'VEN', 862, 28000000, 912050, 'Spanish', 482000000000, NULL, 'Oil-rich nation', datetime('now'), datetime('now')),
('s6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'Chile', 'CHL', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'CL', 'CHL', 152, 19000000, 756102, 'Spanish', 253000000000, NULL, 'Long narrow country', datetime('now'), datetime('now')),
('s7a8b9c0-d1e2-43f4-5a6b-7c8d9e0f1a2b', 'Ecuador', 'ECU', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'EC', 'ECU', 218, 18000000, 283561, 'Spanish', 107000000000, NULL, 'Named after the Equator', datetime('now'), datetime('now')),
('s8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'Bolivia', 'BOL', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'BO', 'BOL', 68, 12000000, 1098581, 'Spanish,Quechua,Aymara', 40000000000, NULL, 'Plurinational state', datetime('now'), datetime('now')),
('s9a0b1c2-d3e4-45f6-7a8b-9c0d1e2f3a4b', 'Paraguay', 'PRY', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'PY', 'PRY', 600, 7000000, 406752, 'Spanish,Guarani', 38000000000, NULL, 'Heart of South America', datetime('now'), datetime('now')),
('s0a1b2c3-d4e5-46f7-8a9b-0c1d2e3f4a5b', 'Uruguay', 'URY', 'c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'UY', 'URY', 858, 3000000, 176215, 'Spanish', 56000000000, NULL, 'Switzerland of South America', datetime('now'), datetime('now'));

-- =========================================
-- 8. Verification
-- =========================================
SELECT 'COUNTRY entity metadata and data installed' AS status;
SELECT COUNT(*) AS country_count FROM country;
