-- =====================================================================
-- Entity: POSTAL_ADDRESS
-- Domain: GEOGRAPHY
-- Description: Street-level postal addresses for persons and organizations
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e', 'POSTAL_ADDRESS', 'Postal Address', 'Street-level postal addresses', 'GEOGRAPHY', 'postal_address', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, enum_values, validation_regex, description, display_order, created_at, updated_at) VALUES
('a5e1b2c3-1111-4c6d-9999-001122334455','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','first_street','First Street','text',1,0,0,NULL,NULL,'Primary street address',1, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334456','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','second_street','Second Street','text',0,0,0,NULL,NULL,'Optional secondary street',2, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334457','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','area','Area / Locality','text',1,0,0,NULL,NULL,'Neighborhood / locality',3, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334458','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','landmark','Landmark','text',0,0,0,NULL,NULL,'Nearby landmark',4, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334459','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','postal_code','Postal Code / PIN','text',1,0,0,NULL,'^[A-Za-z0-9\\- ]{3,20}$','Post code or PIN',5, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334468','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','city_id','City','text',1,0,0,NULL,NULL,'FK to City',6, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334460','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','latitude','Latitude','text',0,0,0,NULL,NULL,'Decimal degrees',7, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334461','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','longitude','Longitude','text',0,0,0,NULL,NULL,'Decimal degrees',8, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334462','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','address_type','Address Type','text',1,0,0,'["Home","Office","Warehouse","Other"]',NULL,'Type of address',9, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334463','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','is_primary','Is Primary','boolean',1,0,0,'0','0','Primary address flag',10, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334464','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','contact_person','Contact Person','text',0,0,0,NULL,NULL,'Contact for deliveries',11, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334465','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','contact_phone','Contact Phone','text',0,0,0,NULL,'^[A-Za-z0-9\\s\\-]{7,20}$','Phone number',12, datetime('now'), datetime('now')),
('a5e1b2c3-1111-4c6d-9999-001122334466','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','delivery_instructions','Delivery Instructions','text',0,0,0,NULL,NULL,'Extra delivery notes',13, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- POSTAL_ADDRESS belongs to CITY (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0004','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','OneToMany','postal_addresses','city_id','A city has multiple postal addresses', datetime('now'));

-- Note: POSTAL_ADDRESS also links to PERSON and ORGANIZATION entities (defined in their respective domains)

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-pa-create','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','create','Create Postal Address','Create postal address','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-pa-read','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','read','Read Postal Address','Read address by id','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-pa-update','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','update','Update Postal Address','Update address','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-pa-delete','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','delete','Delete Postal Address','Delete address','[{"name":"id","type":"text"}]','void',0,1, datetime('now'), datetime('now')),
('func-pa-search','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','search','Search Postal Addresses','Search addresses','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-pa-export','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','export','Export Addresses','Export addresses list','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-pa-validate-postal','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','validate_postal_code','Validate Postal Code','Validate format & existence','[{"name":"postal_code","type":"text"},{"name":"country_id","type":"text"}]','boolean',1,1, datetime('now'), datetime('now')),
('func-pa-by-area','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','get_addresses_by_area','Get Addresses by Area','List addresses in area','[{"name":"area","type":"text"}]','json',1,1, datetime('now'), datetime('now')),
('func-pa-primary','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','get_primary_address','Get Primary Address','Primary address for city','[{"name":"city_id","type":"text"}]','json',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-pa-create','func-pa-create','sql','sp_create_postal_address',1, datetime('now')),
('h-pa-read','func-pa-read','sql','sp_read_postal_address',1, datetime('now')),
('h-pa-update','func-pa-update','sql','sp_update_postal_address',1, datetime('now')),
('h-pa-delete','func-pa-delete','sql','sp_delete_postal_address',1, datetime('now')),
('h-pa-search','func-pa-search','sql','sp_search_postal_address',1, datetime('now')),
('h-pa-export','func-pa-export','script','scripts/export_addresses.py',1, datetime('now')),
('h-pa-validate-postal','func-pa-validate-postal','api','https://api.postalvalidation.example.com/validate',1, datetime('now')),
('h-pa-by-area','func-pa-by-area','sql','sp_get_addresses_by_area',1, datetime('now')),
('h-pa-primary','func-pa-primary','sql','sp_get_primary_address',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- Postal code format validation
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity, created_at) VALUES
('vr-postal-format-1','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','a5e1b2c3-1111-4c6d-9999-001122334459','postal_format','regex_match(value, "^[A-Za-z0-9\\- ]{3,20}$")','Invalid postal code format','error', datetime('now'));

-- Primary address uniqueness (conceptual rule)
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity, created_at) VALUES
('vr-address-primary-unique','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','a5e1b2c3-1111-4c6d-9999-001122334463','primary_address_unique','count_where(entity="POSTAL_ADDRESS", city_id = context.city_id AND is_primary=1) <= 1','Only one primary address allowed per city','error', datetime('now'));

-- =========================================
-- 7. Entity Data
-- =========================================
-- NOTE: POSTAL_ADDRESS data is user-generated and not pre-seeded.
-- This entity will be populated when persons and organizations are created.
-- No sample data is included in this file.

-- Sample structure for reference:
-- INSERT OR IGNORE INTO postal_address (id, first_street, second_street, area, landmark, postal_code, city_id,
--   latitude, longitude, address_type, is_primary, contact_person, contact_phone, delivery_instructions,
--   created_at, updated_at)
-- VALUES
-- ('pa-sample-001', '123 Main Street', 'Apt 4B', 'Malviya Nagar', 'Near Metro Station', '302017',
--   'c00000000-0028-4000-0000-000280000000', '26.8523', '75.8123', 'Home', 1, 'John Doe', '+91-9876543210',
--   'Ring bell twice', datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'POSTAL_ADDRESS entity metadata installed' AS status;
SELECT 'NOTE: POSTAL_ADDRESS data is user-generated and not pre-seeded' AS note;
