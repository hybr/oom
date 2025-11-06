-- =====================================================================
-- Entity: LANGUAGE
-- Domain: GEOGRAPHY
-- Description: Languages spoken in countries
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f', 'LANGUAGE', 'Language', 'Languages spoken in countries', 'GEOGRAPHY', 'language', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('b6f2c3d4-2222-4a6b-8888-112233445566','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','name','Language Name','text',1,0,1,'English name of the language',1, datetime('now'), datetime('now')),
('b6f2c3d4-2222-4a6b-8888-112233445567','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','code','Language Code','text',0,0,0,'ISO language code (e.g., en)',2, datetime('now'), datetime('now')),
('b6f2c3d4-2222-4a6b-8888-112233445568','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','native_name','Native Name','text',0,0,0,'Native script name',3, datetime('now'), datetime('now')),
('b6f2c3d4-2222-4a6b-8888-112233445569','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','country_id','Country','text',0,0,0,'FK to Country',4, datetime('now'), datetime('now')),
('b6f2c3d4-2222-4a6b-8888-112233445570','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','is_official','Is Official','boolean',0,0,0,'1 if official language',5, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- LANGUAGE belongs to COUNTRY (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0005','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','OneToMany','languages','country_id','A country can have multiple languages', datetime('now'));

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-lang-create','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','create','Create Language','Create language record','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-lang-read','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','read','Read Language','Read language','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-lang-search','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','search','Search Languages','Search languages','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-lang-official','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','get_official_languages','Get Official Languages','Official languages for a country','[{"name":"country_id","type":"text"}]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-lang-create','func-lang-create','sql','sp_create_language',1, datetime('now')),
('h-lang-read','func-lang-read','sql','sp_read_language',1, datetime('now')),
('h-lang-search','func-lang-search','sql','sp_search_language',1, datetime('now')),
('h-lang-official','func-lang-official','sql','sp_get_official_languages',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for LANGUAGE

-- =========================================
-- 7. Entity Data
-- =========================================
-- NOTE: Language data should be populated based on country language requirements.
-- Sample data for major languages is provided below.

INSERT OR IGNORE INTO language (id, name, code, native_name, country_id, is_official, created_at, updated_at)
VALUES
-- India (Country ID: b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c)
('lang-001', 'Hindi', 'hi', 'हिन्दी', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1, datetime('now'), datetime('now')),
('lang-002', 'English', 'en', 'English', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1, datetime('now'), datetime('now')),
('lang-003', 'Bengali', 'bn', 'বাংলা', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-004', 'Telugu', 'te', 'తెలుగు', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-005', 'Marathi', 'mr', 'मराठी', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-006', 'Tamil', 'ta', 'தமிழ்', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-007', 'Gujarati', 'gu', 'ગુજરાતી', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-008', 'Urdu', 'ur', 'اردو', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-009', 'Kannada', 'kn', 'ಕನ್ನಡ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-010', 'Malayalam', 'ml', 'മലയാളം', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-011', 'Odia', 'or', 'ଓଡ଼ିଆ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),
('lang-012', 'Punjabi', 'pa', 'ਪੰਜਾਬੀ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 0, datetime('now'), datetime('now')),

-- United States (Country ID: n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b)
('lang-101', 'English', 'en', 'English', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1, datetime('now'), datetime('now')),
('lang-102', 'Spanish', 'es', 'Español', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 0, datetime('now'), datetime('now')),

-- China (Country ID: b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c)
('lang-201', 'Mandarin Chinese', 'zh', '中文', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('lang-202', 'Cantonese', 'yue', '粵語', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 0, datetime('now'), datetime('now')),

-- Canada (Country ID: n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b)
('lang-301', 'English', 'en', 'English', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 1, datetime('now'), datetime('now')),
('lang-302', 'French', 'fr', 'Français', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 1, datetime('now'), datetime('now')),

-- Germany (Country ID: e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a)
('lang-401', 'German', 'de', 'Deutsch', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 1, datetime('now'), datetime('now')),

-- United Kingdom (Country ID: e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a)
('lang-501', 'English', 'en', 'English', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 1, datetime('now'), datetime('now')),
('lang-502', 'Welsh', 'cy', 'Cymraeg', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 1, datetime('now'), datetime('now')),
('lang-503', 'Scottish Gaelic', 'gd', 'Gàidhlig', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 0, datetime('now'), datetime('now')),
('lang-504', 'Irish', 'ga', 'Gaeilge', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 0, datetime('now'), datetime('now')),

-- Australia (Country ID: o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b)
('lang-601', 'English', 'en', 'English', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1, datetime('now'), datetime('now')),

-- Brazil (Country ID: s1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b)
('lang-701', 'Portuguese', 'pt', 'Português', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1, datetime('now'), datetime('now')),

-- Mexico (Country ID: n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b)
('lang-801', 'Spanish', 'es', 'Español', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1, datetime('now'), datetime('now')),

-- France (example - add actual country ID when available)
('lang-901', 'French', 'fr', 'Français', NULL, 1, datetime('now'), datetime('now')),

-- Italy (example - add actual country ID when available)
('lang-1001', 'Italian', 'it', 'Italiano', NULL, 1, datetime('now'), datetime('now')),

-- Spain (example - add actual country ID when available)
('lang-1101', 'Spanish', 'es', 'Español', NULL, 1, datetime('now'), datetime('now')),
('lang-1102', 'Catalan', 'ca', 'Català', NULL, 1, datetime('now'), datetime('now')),
('lang-1103', 'Basque', 'eu', 'Euskara', NULL, 1, datetime('now'), datetime('now')),
('lang-1104', 'Galician', 'gl', 'Galego', NULL, 1, datetime('now'), datetime('now')),

-- Russia (example - add actual country ID when available)
('lang-1201', 'Russian', 'ru', 'Русский', NULL, 1, datetime('now'), datetime('now')),

-- Japan (example - add actual country ID when available)
('lang-1301', 'Japanese', 'ja', '日本語', NULL, 1, datetime('now'), datetime('now')),

-- South Korea (example - add actual country ID when available)
('lang-1401', 'Korean', 'ko', '한국어', NULL, 1, datetime('now'), datetime('now')),

-- Arabic-speaking countries (example)
('lang-1501', 'Arabic', 'ar', 'العربية', NULL, 1, datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'LANGUAGE entity metadata and sample data installed' AS status;
SELECT COUNT(*) AS language_count FROM language;
SELECT 'NOTE: Complete language data should be populated based on country requirements' AS note;
