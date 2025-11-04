-- =====================================================================
-- Full SQLite script: Geography metadata (entity definitions, attributes,
-- relationships, functions, handlers, validation rules)
-- Generated: 2025-10-10 (Asia/Kolkata)
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- DDL (recreated for convenience)
-- =========================================
CREATE TABLE IF NOT EXISTS entity_definition (
    id TEXT PRIMARY KEY,
    code TEXT UNIQUE NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    domain TEXT,
    table_name TEXT,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS entity_attribute (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,
    code TEXT NOT NULL,
    name TEXT NOT NULL,
    data_type TEXT NOT NULL,
    is_required INTEGER DEFAULT 0,
    is_unique INTEGER DEFAULT 0,
    is_system INTEGER DEFAULT 0,
    is_label INTEGER DEFAULT 0,
    default_value TEXT,
    min_value TEXT,
    max_value TEXT,
    enum_values TEXT,
    validation_regex TEXT,
    description TEXT,
    display_order INTEGER,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    UNIQUE(entity_id, code),
    FOREIGN KEY(entity_id) REFERENCES entity_definition(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS entity_function (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,
    function_code TEXT NOT NULL,
    function_name TEXT,
    function_description TEXT,
    parameters TEXT,
    return_type TEXT,
    is_system INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    UNIQUE(entity_id, function_code),
    FOREIGN KEY(entity_id) REFERENCES entity_definition(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS entity_function_handler (
    id TEXT PRIMARY KEY,
    function_id TEXT NOT NULL,
    handler_type TEXT,
    handler_reference TEXT,
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY(function_id) REFERENCES entity_function(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS entity_relationship (
    id TEXT PRIMARY KEY,
    from_entity_id TEXT NOT NULL,
    to_entity_id TEXT NOT NULL,
    relation_type TEXT,
    relation_name TEXT,
    fk_field TEXT,
    description TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY(from_entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY(to_entity_id) REFERENCES entity_definition(id)
);

CREATE TABLE IF NOT EXISTS entity_validation_rule (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,
    attribute_id TEXT NOT NULL,
    rule_name TEXT,
    rule_expression TEXT,
    error_message TEXT,
    severity TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    FOREIGN KEY(entity_id) REFERENCES entity_definition(id),
    FOREIGN KEY(attribute_id) REFERENCES entity_attribute(id)
);

-- =========================================
-- 1. entity_definition inserts (entities)
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name) VALUES
('8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523', 'CONTINENT', 'Continent', 'Continents of the world', 'GEOGRAPHY', 'continent'),
('2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'COUNTRY', 'Country', 'Countries within continents', 'GEOGRAPHY', 'country'),
('9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c', 'STATE', 'State / Province', 'States or provinces within a country', 'GEOGRAPHY', 'state'),
('b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c', 'DISTRICT', 'District / County', 'Districts or counties within a state', 'GEOGRAPHY', 'district'),
('aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad', 'CITY', 'City / Town', 'Cities or towns within a district', 'GEOGRAPHY', 'city'),
('f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e', 'POSTAL_ADDRESS', 'Postal Address', 'Street-level postal addresses', 'GEOGRAPHY', 'postal_address'),
('0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f', 'LANGUAGE', 'Language', 'Languages spoken in countries', 'GEOGRAPHY', 'language'),
('5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a', 'CURRENCY', 'Currency', 'Currencies used in countries', 'GEOGRAPHY', 'currency'),
('d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60', 'TIMEZONE', 'Time Zone', 'Time zones of countries', 'GEOGRAPHY', 'timezone');

-- =========================================
-- 2. entity_attribute inserts (comprehensive)
-- Note: display_order is approximate; adjust as needed.
-- =========================================

-- CONTINENT attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b111','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','name','Continent Name','text',1,1,1,'Human-readable continent name',1),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b112','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','code','Continent Code','text',1,1,1,'Short code for continent (e.g., AS, EU)',2),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b113','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','area_sq_km','Area (sq km)','number',0,0,0,'Geographic area',3),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b114','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','population','Population','number',0,0,0,'Total population estimate',4),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b115','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','gdp_usd','GDP (USD)','number',0,0,0,'Aggregate GDP estimate',5),
('c1a6f3d9-88fb-4f8d-9e7b-11c2a3e5b116','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','description','Description','text',0,0,0,'Free-text description',6);

-- COUNTRY attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a001','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','name','Country Name','text',1,1,1,'Human-readable country name',1),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a002','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','code','Country Code','text',1,1,1,'Short code (e.g., IND)',2),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a003','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','continent_id','Continent','text',1,0,0,'FK to Continent',3),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a004','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','iso_alpha2','ISO Alpha-2 Code','text',1,1,0,'ISO 3166-1 alpha-2',4),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a005','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','iso_alpha3','ISO Alpha-3 Code','text',1,1,0,'ISO 3166-1 alpha-3',5),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a006','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','iso_numeric','ISO Numeric Code','number',0,0,0,'ISO numeric code',6),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a007','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','population','Population','number',0,0,0,'Population estimate',7),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a008','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',8),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a009','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','official_languages','official_languages','text',0,0,0,'Comma-separated official languages',9),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a010','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','currency_id','Currency','text',0,0,0,'FK to Currency',10),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a011','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','timezone_id','Time Zone','text',0,0,0,'FK to Timezone',11),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a012','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','gdp_usd','GDP (USD)','number',0,0,0,'GDP in USD',12),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a013','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','flag_url','Flag Image URL','text',0,0,0,'URL to flag image',13),
('d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a014','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','description','Description','text',0,0,0,'Free-text',14);

-- STATE attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0011','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','name','State / Province Name','text',1,0,1,'State or province name',1),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0012','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','code','State Code','text',0,0,1,'Optional state code',2),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0013','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','country_id','Country','text',1,0,0,'FK to Country',3),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0014','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','population','Population','number',0,0,0,'Population estimate',4),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0015','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',5),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0016','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','gdp_usd','GDP (USD)','number',0,0,0,'GDP in USD',6),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0017','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','capital','Capital City','text',0,0,0,'Name of state capital',7),
('e3c8a8b9-7b3a-4d1b-9382-0b4a3d5a0018','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','description','Description','text',0,0,0,'Free-text',8);

-- DISTRICT attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e6f','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','name','District / County Name','text',1,0,1,'Name of district or county',1),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e70','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','code','District Code','text',0,0,1,'Optional district code',2),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e71','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','state_id','State / Province','text',1,0,0,'FK to State',3),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e72','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','population','Population','number',0,0,0,'Population estimate',4),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e73','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',5),
('g5a9c7b1-3b4a-4e2b-8c91-1a2b3c4d5e74','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','description','Description','text',0,0,0,'Free-text',6);

-- CITY attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0011','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','name','City / Town Name','text',1,0,1,'Name of city or town',1),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0012','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','code','City Code','text',0,0,0,'Optional city code',2),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0021','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','district_id','District / County','text',1,0,0,'FK to District',3),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0013','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','state_id','State / Province','text',1,0,0,'FK to State',4),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0014','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','country_id','Country','text',1,0,0,'FK to Country',5),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0015','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','population','Population','number',0,0,0,'Population',6),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0016','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','area_sq_km','Area (sq km)','number',0,0,0,'Area in sq km',7),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0017','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','latitude','Latitude','text',0,0,0,'Decimal degrees',8),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0018','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','longitude','Longitude','text',0,0,0,'Decimal degrees',9),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0019','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','timezone_id','Time Zone','text',0,0,0,'FK to Timezone',10),
('f4d9b7a2-2a31-4d6f-8b2d-0c3a1b2d0020','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','description','Description','text',0,0,0,'Free-text',11);

-- POSTAL_ADDRESS attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, enum_values, validation_regex, description, display_order) VALUES
('a5e1b2c3-1111-4c6d-9999-001122334455','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','first_street','First Street','text',1,0,0,NULL,NULL,'Primary street address',1),
('a5e1b2c3-1111-4c6d-9999-001122334456','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','second_street','Second Street','text',0,0,0,NULL,NULL,'Optional secondary street',2),
('a5e1b2c3-1111-4c6d-9999-001122334457','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','area','Area / Locality','text',1,0,0,NULL,NULL,'Neighborhood / locality',3),
('a5e1b2c3-1111-4c6d-9999-001122334458','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','landmark','Landmark','text',0,0,0,NULL,NULL,'Nearby landmark',4),
('a5e1b2c3-1111-4c6d-9999-001122334459','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','postal_code','Postal Code / PIN','text',1,0,0,NULL,'^[A-Za-z0-9\\- ]{3,20}$','Post code or PIN',5),
('a5e1b2c3-1111-4c6d-9999-001122334468','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','city_id','City','text',1,0,0,NULL,NULL,'FK to City',6),
('a5e1b2c3-1111-4c6d-9999-001122334460','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','latitude','Latitude','text',0,0,0,NULL,NULL,'Decimal degrees',7),
('a5e1b2c3-1111-4c6d-9999-001122334461','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','longitude','Longitude','text',0,0,0,NULL,NULL,'Decimal degrees',8),
('a5e1b2c3-1111-4c6d-9999-001122334462','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','address_type','Address Type','text',1,0,0,'["Home","Office","Warehouse","Other"]',NULL,'Type of address',9),
('a5e1b2c3-1111-4c6d-9999-001122334463','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','is_primary','Is Primary','boolean',1,0,0,'0','0','Primary address flag',10),
('a5e1b2c3-1111-4c6d-9999-001122334464','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','contact_person','Contact Person','text',0,0,0,NULL,NULL,'Contact for deliveries',11),
('a5e1b2c3-1111-4c6d-9999-001122334465','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','contact_phone','Contact Phone','text',0,0,0,NULL,'^[A-Za-z0-9\\s\\-]{7,20}$','Phone number',12),
('a5e1b2c3-1111-4c6d-9999-001122334466','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','delivery_instructions','Delivery Instructions','text',0,0,0,NULL,NULL,'Extra delivery notes',13);

-- LANGUAGE attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('b6f2c3d4-2222-4a6b-8888-112233445566','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','name','Language Name','text',1,0,1,'English name of the language',1),
('b6f2c3d4-2222-4a6b-8888-112233445567','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','code','Language Code','text',0,0,0,'ISO language code (e.g., en)',2),
('b6f2c3d4-2222-4a6b-8888-112233445568','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','native_name','Native Name','text',0,0,0,'Native script name',3),
('b6f2c3d4-2222-4a6b-8888-112233445569','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','country_id','Country','text',0,0,0,'FK to Country',4),
('b6f2c3d4-2222-4a6b-8888-112233445570','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','is_official','Is Official','boolean',0,0,0,'1 if official language',5);

-- CURRENCY attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('c7g3d4e5-3333-4b6c-7777-223344556677','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','code','Currency Code','text',1,1,1,'ISO4217 code (e.g., INR)',1),
('c7g3d4e5-3333-4b6c-7777-223344556678','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','name','Currency Name','text',1,0,0,'Currency full name',2),
('c7g3d4e5-3333-4b6c-7777-223344556679','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','symbol','Currency Symbol','text',0,0,0,'Symbol like ₹, $',3),
('c7g3d4e5-3333-4b6c-7777-223344556680','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','country_id','Country','text',0,0,0,'FK to Country',4),
('c7g3d4e5-3333-4b6c-7777-223344556681','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','exchange_rate_usd','Exchange Rate to USD','number',0,0,0,'Reference exchange rate',5);

-- TIMEZONE attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order) VALUES
('d8h4e5f6-4444-4c6d-6666-334455667788','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','name','Time Zone Name','text',1,1,1,'IANA tz name (e.g., Asia/Kolkata)',1),
('d8h4e5f6-4444-4c6d-6666-334455667789','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','utc_offset','UTC Offset','text',1,0,0,'Offset like +05:30',2),
('d8h4e5f6-4444-4c6d-6666-334455667790','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','dst','Daylight Saving','boolean',0,0,0,'1 if DST used',3),
('d8h4e5f6-4444-4c6d-6666-334455667791','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','country_id','Country','text',0,0,0,'FK to Country',4);

-- =========================================
-- 3. entity_relationship inserts (hierarchy)
-- =========================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('rel-0001','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','OneToMany','countries','continent_id','A continent has multiple countries'),
('rel-0002','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','OneToMany','states','country_id','A country has multiple states/provinces'),
('rel-0003','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','OneToMany','districts','state_id','A state has multiple districts'),
('rel-0009','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','OneToMany','cities','district_id','A district has multiple cities'),
('rel-0004','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','OneToMany','postal_addresses','city_id','A city has multiple postal addresses'),
('rel-0005','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','OneToMany','languages','country_id','A country can have multiple languages'),
('rel-0006','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','OneToMany','currencies','country_id','A country can have multiple currencies'),
('rel-0007','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','OneToMany','timezones','country_id','A country can have multiple timezones');

-- =========================================
-- 4. entity_function inserts
--    CRUD + search + import/export + business logic
-- =========================================

-- Helper: create lists of functions per entity
-- CONTINENT: CRUD + search/export + business logic
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-ct-create','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','create','Create Continent','Create a new continent','[{"name":"data","type":"json"}]','void',0),
('func-ct-read','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','read','Read Continent','Read continent by id','[{"name":"id","type":"text"}]','json',0),
('func-ct-update','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','update','Update Continent','Update continent by id','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0),
('func-ct-delete','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','delete','Delete Continent','Delete by id','[{"name":"id","type":"text"}]','void',0),
('func-ct-search','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','search','Search Continents','Search with filters','[{"name":"filters","type":"json"}]','json',0),
('func-ct-export','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','export','Export Continents','Export matched continents','[{"name":"filters","type":"json"}]','json',0),
('func-ct-validate-code','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','validate_continent_code','Validate Continent Code','Ensure code uniqueness/format','[{"name":"code","type":"text"}]','boolean',1),
('func-ct-summary','8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523','get_continent_summary','Continent Summary','Return aggregated counts and stats','[{"name":"id","type":"text"}]','json',1);

-- COUNTRY: CRUD + search/export + business logic (ISO validations, primary timezone, GDP summary, import)
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-cy-create','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','create','Create Country','Create country record','[{"name":"data","type":"json"}]','void',0),
('func-cy-read','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','read','Read Country','Read by id','[{"name":"id","type":"text"}]','json',0),
('func-cy-update','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','update','Update Country','Update country','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0),
('func-cy-delete','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','delete','Delete Country','Delete country','[{"name":"id","type":"text"}]','void',0),
('func-cy-search','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','search','Search Countries','Search countries','[{"name":"filters","type":"json"}]','json',0),
('func-cy-export','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','export','Export Countries','Export matched countries','[{"name":"filters","type":"json"}]','json',0),
('func-cy-import','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','import','Import Countries CSV/JSON','Bulk import countries','[{"name":"file","type":"text"}]','json',1),
('func-cy-validate-iso','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','validate_country_iso','Validate Country ISO','Validate ISO alpha2/alpha3','[{"name":"iso_alpha2","type":"text"},{"name":"iso_alpha3","type":"text"}]','boolean',1),
('func-cy-gdp-summary','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','get_country_gdp_summary','Country GDP Summary','Aggregate GDP from states','[{"name":"country_id","type":"text"}]','number',1),
('func-cy-primary-tz','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','get_primary_timezone','Get Primary Timezone','Return primary timezone for country','[{"name":"country_id","type":"text"}]','text',1);

-- STATE functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-st-create','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','create','Create State','Create a state record','[{"name":"data","type":"json"}]','void',0),
('func-st-read','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','read','Read State','Read state by id','[{"name":"id","type":"text"}]','json',0),
('func-st-update','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','update','Update State','Update state','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0),
('func-st-delete','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','delete','Delete State','Delete state','[{"name":"id","type":"text"}]','void',0),
('func-st-search','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','search','Search States','Search states','[{"name":"filters","type":"json"}]','json',0),
('func-st-validate-code','9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c','validate_state_code','Validate State Code','Ensure unique within country','[{"name":"state_code","type":"text"},{"name":"country_id","type":"text"}]','boolean',1);

-- DISTRICT functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-dt-create','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','create','Create District','Create a district record','[{"name":"data","type":"json"}]','void',0),
('func-dt-read','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','read','Read District','Read district by id','[{"name":"id","type":"text"}]','json',0),
('func-dt-update','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','update','Update District','Update district','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0),
('func-dt-delete','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','delete','Delete District','Delete district','[{"name":"id","type":"text"}]','void',0),
('func-dt-search','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','search','Search Districts','Search districts','[{"name":"filters","type":"json"}]','json',0),
('func-dt-validate-code','b5c3a8d9-4f7e-4a2b-9c31-7d8f1b3e5a9c','validate_district_code','Validate District Code','Ensure unique within state','[{"name":"district_code","type":"text"},{"name":"state_id","type":"text"}]','boolean',1);

-- CITY functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-ci-create','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','create','Create City','Create a city','[{"name":"data","type":"json"}]','void',0),
('func-ci-read','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','read','Read City','Read city','[{"name":"id","type":"text"}]','json',0),
('func-ci-update','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','update','Update City','Update city','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0),
('func-ci-delete','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','delete','Delete City','Delete city','[{"name":"id","type":"text"}]','void',0),
('func-ci-search','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','search','Search Cities','Search cities','[{"name":"filters","type":"json"}]','json',0),
('func-ci-get-coords','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','get_city_coordinates','Get City Coordinates','Return lat/lon for city','[{"name":"city_id","type":"text"}]','json',1),
('func-ci-by-pop','aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad','get_cities_by_population','Get Cities by Population','Top cities by population','[{"name":"state_id","type":"text"},{"name":"limit","type":"number"}]','json',1);

-- POSTAL_ADDRESS functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-pa-create','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','create','Create Postal Address','Create postal address','[{"name":"data","type":"json"}]','void',0),
('func-pa-read','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','read','Read Postal Address','Read address by id','[{"name":"id","type":"text"}]','json',0),
('func-pa-update','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','update','Update Postal Address','Update address','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',0),
('func-pa-delete','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','delete','Delete Postal Address','Delete address','[{"name":"id","type":"text"}]','void',0),
('func-pa-search','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','search','Search Postal Addresses','Search addresses','[{"name":"filters","type":"json"}]','json',0),
('func-pa-export','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','export','Export Addresses','Export addresses list','[{"name":"filters","type":"json"}]','json',0),
('func-pa-validate-postal','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','validate_postal_code','Validate Postal Code','Validate format & existence','[{"name":"postal_code","type":"text"},{"name":"country_id","type":"text"}]','boolean',1),
('func-pa-by-area','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','get_addresses_by_area','Get Addresses by Area','List addresses in area','[{"name":"area","type":"text"}]','json',1),
('func-pa-primary','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','get_primary_address','Get Primary Address','Primary address for city','[{"name":"city_id","type":"text"}]','json',1);

-- LANGUAGE functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-lang-create','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','create','Create Language','Create language record','[{"name":"data","type":"json"}]','void',0),
('func-lang-read','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','read','Read Language','Read language','[{"name":"id","type":"text"}]','json',0),
('func-lang-search','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','search','Search Languages','Search languages','[{"name":"filters","type":"json"}]','json',0),
('func-lang-official','0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f','get_official_languages','Get Official Languages','Official languages for a country','[{"name":"country_id","type":"text"}]','json',1);

-- CURRENCY functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-cur-create','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','create','Create Currency','Create new currency','[{"name":"data","type":"json"}]','void',0),
('func-cur-read','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','read','Read Currency','Read currency','[{"name":"id","type":"text"}]','json',0),
('func-cur-search','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','search','Search Currencies','Search currencies','[{"name":"filters","type":"json"}]','json',0),
('func-cur-convert','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','get_currency_conversion','Currency Conversion','Convert amount between currencies','[{"name":"amount","type":"number"},{"name":"from_currency","type":"text"},{"name":"to_currency","type":"text"}]','number',1);

-- TIMEZONE functions
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system) VALUES
('func-tz-create','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','create','Create Timezone','Create timezone record','[{"name":"data","type":"json"}]','void',0),
('func-tz-read','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','read','Read Timezone','Read timezone','[{"name":"id","type":"text"}]','json',0),
('func-tz-search','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','search','Search Timezones','Search timezones','[{"name":"filters","type":"json"}]','json',0),
('func-tz-convert','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','convert_utc_to_local','Convert UTC to Local Time','Convert datetime to local tz','[{"name":"datetime_utc","type":"text"},{"name":"timezone_id","type":"text"}]','text',1);

-- =========================================
-- 5. entity_function_handler inserts (mixed sql/api/script)
--    Use SQL handlers for core CRUD/search, APIs for external lookups,
--    scripts for import/complex processing.
-- =========================================

-- CONTINENT handlers (mostly SQL)
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-ct-create','func-ct-create','sql','sp_create_continent'),
('h-ct-read','func-ct-read','sql','sp_read_continent'),
('h-ct-update','func-ct-update','sql','sp_update_continent'),
('h-ct-delete','func-ct-delete','sql','sp_delete_continent'),
('h-ct-search','func-ct-search','sql','sp_search_continent'),
('h-ct-export','func-ct-export','script','scripts/export_continents.py'),
('h-ct-validate-code','func-ct-validate-code','sql','sp_validate_continent_code'),
('h-ct-summary','func-ct-summary','sql','sp_get_continent_summary');

-- COUNTRY handlers (mix)
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-cy-create','func-cy-create','sql','sp_create_country'),
('h-cy-read','func-cy-read','sql','sp_read_country'),
('h-cy-update','func-cy-update','sql','sp_update_country'),
('h-cy-delete','func-cy-delete','sql','sp_delete_country'),
('h-cy-search','func-cy-search','sql','sp_search_country'),
('h-cy-export','func-cy-export','script','scripts/export_countries.py'),
('h-cy-import','func-cy-import','script','scripts/import_countries.py'),
('h-cy-validate-iso','func-cy-validate-iso','api','https://api.example.com/validate_iso'),
('h-cy-gdp-summary','func-cy-gdp-summary','sql','sp_get_country_gdp_summary'),
('h-cy-primary-tz','func-cy-primary-tz','sql','sp_get_primary_timezone');

-- STATE handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-st-create','func-st-create','sql','sp_create_state'),
('h-st-read','func-st-read','sql','sp_read_state'),
('h-st-update','func-st-update','sql','sp_update_state'),
('h-st-delete','func-st-delete','sql','sp_delete_state'),
('h-st-search','func-st-search','sql','sp_search_state'),
('h-st-validate-code','func-st-validate-code','sql','sp_validate_state_code');

-- DISTRICT handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-dt-create','func-dt-create','sql','sp_create_district'),
('h-dt-read','func-dt-read','sql','sp_read_district'),
('h-dt-update','func-dt-update','sql','sp_update_district'),
('h-dt-delete','func-dt-delete','sql','sp_delete_district'),
('h-dt-search','func-dt-search','sql','sp_search_district'),
('h-dt-validate-code','func-dt-validate-code','sql','sp_validate_district_code');

-- CITY handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-ci-create','func-ci-create','sql','sp_create_city'),
('h-ci-read','func-ci-read','sql','sp_read_city'),
('h-ci-update','func-ci-update','sql','sp_update_city'),
('h-ci-delete','func-ci-delete','sql','sp_delete_city'),
('h-ci-search','func-ci-search','sql','sp_search_city'),
('h-ci-get-coords','func-ci-get-coords','api','https://api.geo.example.com/city_coords'),
('h-ci-by-pop','func-ci-by-pop','sql','sp_get_cities_by_population');

-- POSTAL_ADDRESS handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-pa-create','func-pa-create','sql','sp_create_postal_address'),
('h-pa-read','func-pa-read','sql','sp_read_postal_address'),
('h-pa-update','func-pa-update','sql','sp_update_postal_address'),
('h-pa-delete','func-pa-delete','sql','sp_delete_postal_address'),
('h-pa-search','func-pa-search','sql','sp_search_postal_address'),
('h-pa-export','func-pa-export','script','scripts/export_addresses.py'),
('h-pa-validate-postal','func-pa-validate-postal','api','https://api.postalvalidation.example.com/validate'),
('h-pa-by-area','func-pa-by-area','sql','sp_get_addresses_by_area'),
('h-pa-primary','func-pa-primary','sql','sp_get_primary_address');

-- LANGUAGE handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-lang-create','func-lang-create','sql','sp_create_language'),
('h-lang-read','func-lang-read','sql','sp_read_language'),
('h-lang-search','func-lang-search','sql','sp_search_language'),
('h-lang-official','func-lang-official','sql','sp_get_official_languages');

-- CURRENCY handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-cur-create','func-cur-create','sql','sp_create_currency'),
('h-cur-read','func-cur-read','sql','sp_read_currency'),
('h-cur-search','func-cur-search','sql','sp_search_currency'),
('h-cur-convert','func-cur-convert','api','https://api.exchangerate.example.com/convert');

-- TIMEZONE handlers
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference) VALUES
('h-tz-create','func-tz-create','sql','sp_create_timezone'),
('h-tz-read','func-tz-read','sql','sp_read_timezone'),
('h-tz-search','func-tz-search','sql','sp_search_timezone'),
('h-tz-convert','func-tz-convert','script','scripts/convert_utc_to_local.py');

-- =========================================
-- 6. entity_validation_rule inserts (examples)
-- =========================================

-- Country ISO codes must be two/three letters
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity) VALUES
('vr-country-iso-1','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a004','iso_alpha2_format','length(value) = 2','ISO alpha-2 must be 2 characters','error'),
('vr-country-iso-2','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d2b7f4a0-21d9-4e8b-aa6f-2b30d4f1a005','iso_alpha3_format','length(value) = 3','ISO alpha-3 must be 3 characters','error');

-- Postal code: must match pattern
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity) VALUES
('vr-postal-format-1','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','a5e1b2c3-1111-4c6d-9999-001122334459','postal_format','regex_match(value, "^[A-Za-z0-9\\- ]{3,20}$")','Invalid postal code format','error');

-- Address primary uniqueness within city (conceptual rule — expression is example)
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity) VALUES
('vr-address-primary-unique','f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e','a5e1b2c3-1111-4c6d-9999-001122334463','primary_address_unique','count_where(entity="POSTAL_ADDRESS", city_id = context.city_id AND is_primary=1) <= 1','Only one primary address allowed per city','error');

-- =========================================
-- End of script
-- =========================================

-- Helpful: SELECT counts to verify inserts
SELECT 'entity_definition' AS table_name, COUNT(*) AS cnt FROM entity_definition;
SELECT 'entity_attribute' AS table_name, COUNT(*) AS cnt FROM entity_attribute;
SELECT 'entity_relationship' AS table_name, COUNT(*) AS cnt FROM entity_relationship;
SELECT 'entity_function' AS table_name, COUNT(*) AS cnt FROM entity_function;
SELECT 'entity_function_handler' AS table_name, COUNT(*) AS cnt FROM entity_function_handler;
SELECT 'entity_validation_rule' AS table_name, COUNT(*) AS cnt FROM entity_validation_rule;
