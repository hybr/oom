-- =====================================================================
-- Entity: TIMEZONE
-- Domain: GEOGRAPHY
-- Description: Time zones of countries and cities
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60', 'TIMEZONE', 'Time Zone', 'Time zones of countries', 'GEOGRAPHY', 'timezone', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('d8h4e5f6-4444-4c6d-6666-334455667788','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','name','Time Zone Name','text',1,1,1,'IANA tz name (e.g., Asia/Kolkata)',1, datetime('now'), datetime('now')),
('d8h4e5f6-4444-4c6d-6666-334455667789','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','utc_offset','UTC Offset','text',1,0,0,'Offset like +05:30',2, datetime('now'), datetime('now')),
('d8h4e5f6-4444-4c6d-6666-334455667790','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','dst','Daylight Saving','boolean',0,0,0,'1 if DST used',3, datetime('now'), datetime('now')),
('d8h4e5f6-4444-4c6d-6666-334455667791','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','country_id','Country','text',0,0,0,'FK to Country',4, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- TIMEZONE belongs to COUNTRY (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0007','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','OneToMany','timezones','country_id','A country can have multiple timezones', datetime('now'));

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-tz-create','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','create','Create Timezone','Create timezone record','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-tz-read','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','read','Read Timezone','Read timezone','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-tz-search','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','search','Search Timezones','Search timezones','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-tz-convert','d7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60','convert_utc_to_local','Convert UTC to Local Time','Convert datetime to local tz','[{"name":"datetime_utc","type":"text"},{"name":"timezone_id","type":"text"}]','text',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-tz-create','func-tz-create','sql','sp_create_timezone',1, datetime('now')),
('h-tz-read','func-tz-read','sql','sp_read_timezone',1, datetime('now')),
('h-tz-search','func-tz-search','sql','sp_search_timezone',1, datetime('now')),
('h-tz-convert','func-tz-convert','script','scripts/convert_utc_to_local.py',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for TIMEZONE

-- =========================================
-- 7. Entity Data
-- =========================================
-- NOTE: Timezone data based on IANA Time Zone Database.
-- DST (Daylight Saving Time) observance varies by region.

INSERT OR IGNORE INTO timezone (id, name, utc_offset, dst, country_id, created_at, updated_at)
VALUES
-- Asia
('tz-001', 'Asia/Kolkata', '+05:30', 0, 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', datetime('now'), datetime('now')),
('tz-002', 'Asia/Shanghai', '+08:00', 0, 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', datetime('now'), datetime('now')),
('tz-003', 'Asia/Tokyo', '+09:00', 0, NULL, datetime('now'), datetime('now')),
('tz-004', 'Asia/Seoul', '+09:00', 0, NULL, datetime('now'), datetime('now')),
('tz-005', 'Asia/Hong_Kong', '+08:00', 0, NULL, datetime('now'), datetime('now')),
('tz-006', 'Asia/Singapore', '+08:00', 0, NULL, datetime('now'), datetime('now')),
('tz-007', 'Asia/Dubai', '+04:00', 0, NULL, datetime('now'), datetime('now')),
('tz-008', 'Asia/Bangkok', '+07:00', 0, NULL, datetime('now'), datetime('now')),
('tz-009', 'Asia/Jakarta', '+07:00', 0, NULL, datetime('now'), datetime('now')),
('tz-010', 'Asia/Manila', '+08:00', 0, NULL, datetime('now'), datetime('now')),
('tz-011', 'Asia/Karachi', '+05:00', 0, NULL, datetime('now'), datetime('now')),
('tz-012', 'Asia/Dhaka', '+06:00', 0, NULL, datetime('now'), datetime('now')),
('tz-013', 'Asia/Colombo', '+05:30', 0, NULL, datetime('now'), datetime('now')),
('tz-014', 'Asia/Kathmandu', '+05:45', 0, NULL, datetime('now'), datetime('now')),
('tz-015', 'Asia/Tehran', '+03:30', 1, NULL, datetime('now'), datetime('now')),
('tz-016', 'Asia/Jerusalem', '+02:00', 1, NULL, datetime('now'), datetime('now')),
('tz-017', 'Asia/Riyadh', '+03:00', 0, NULL, datetime('now'), datetime('now')),
('tz-018', 'Asia/Taipei', '+08:00', 0, NULL, datetime('now'), datetime('now')),

-- Americas
('tz-101', 'America/New_York', '-05:00', 1, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-102', 'America/Chicago', '-06:00', 1, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-103', 'America/Denver', '-07:00', 1, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-104', 'America/Los_Angeles', '-08:00', 1, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-105', 'America/Phoenix', '-07:00', 0, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-106', 'America/Anchorage', '-09:00', 1, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-107', 'America/Toronto', '-05:00', 1, 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', datetime('now'), datetime('now')),
('tz-108', 'America/Vancouver', '-08:00', 1, 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', datetime('now'), datetime('now')),
('tz-109', 'America/Mexico_City', '-06:00', 0, 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', datetime('now'), datetime('now')),
('tz-110', 'America/Sao_Paulo', '-03:00', 1, 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-111', 'America/Buenos_Aires', '-03:00', 0, NULL, datetime('now'), datetime('now')),
('tz-112', 'America/Santiago', '-04:00', 1, NULL, datetime('now'), datetime('now')),
('tz-113', 'America/Bogota', '-05:00', 0, NULL, datetime('now'), datetime('now')),
('tz-114', 'America/Lima', '-05:00', 0, NULL, datetime('now'), datetime('now')),
('tz-115', 'America/Caracas', '-04:00', 0, NULL, datetime('now'), datetime('now')),

-- Europe
('tz-201', 'Europe/London', '+00:00', 1, 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', datetime('now'), datetime('now')),
('tz-202', 'Europe/Paris', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-203', 'Europe/Berlin', '+01:00', 1, 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', datetime('now'), datetime('now')),
('tz-204', 'Europe/Rome', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-205', 'Europe/Madrid', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-206', 'Europe/Amsterdam', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-207', 'Europe/Brussels', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-208', 'Europe/Vienna', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-209', 'Europe/Zurich', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-210', 'Europe/Stockholm', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-211', 'Europe/Oslo', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-212', 'Europe/Copenhagen', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-213', 'Europe/Helsinki', '+02:00', 1, NULL, datetime('now'), datetime('now')),
('tz-214', 'Europe/Warsaw', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-215', 'Europe/Prague', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-216', 'Europe/Budapest', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-217', 'Europe/Athens', '+02:00', 1, NULL, datetime('now'), datetime('now')),
('tz-218', 'Europe/Istanbul', '+03:00', 0, NULL, datetime('now'), datetime('now')),
('tz-219', 'Europe/Moscow', '+03:00', 0, NULL, datetime('now'), datetime('now')),
('tz-220', 'Europe/Lisbon', '+00:00', 1, NULL, datetime('now'), datetime('now')),
('tz-221', 'Europe/Dublin', '+00:00', 1, NULL, datetime('now'), datetime('now')),

-- Africa
('tz-301', 'Africa/Cairo', '+02:00', 0, NULL, datetime('now'), datetime('now')),
('tz-302', 'Africa/Johannesburg', '+02:00', 0, NULL, datetime('now'), datetime('now')),
('tz-303', 'Africa/Lagos', '+01:00', 0, NULL, datetime('now'), datetime('now')),
('tz-304', 'Africa/Nairobi', '+03:00', 0, NULL, datetime('now'), datetime('now')),
('tz-305', 'Africa/Casablanca', '+01:00', 1, NULL, datetime('now'), datetime('now')),
('tz-306', 'Africa/Algiers', '+01:00', 0, NULL, datetime('now'), datetime('now')),
('tz-307', 'Africa/Tunis', '+01:00', 0, NULL, datetime('now'), datetime('now')),

-- Australia & Pacific
('tz-401', 'Australia/Sydney', '+10:00', 1, 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-402', 'Australia/Melbourne', '+10:00', 1, 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-403', 'Australia/Brisbane', '+10:00', 0, 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-404', 'Australia/Perth', '+08:00', 0, 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-405', 'Australia/Adelaide', '+09:30', 1, 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),
('tz-406', 'Pacific/Auckland', '+12:00', 1, NULL, datetime('now'), datetime('now')),
('tz-407', 'Pacific/Fiji', '+12:00', 1, NULL, datetime('now'), datetime('now')),
('tz-408', 'Pacific/Honolulu', '-10:00', 0, 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', datetime('now'), datetime('now')),

-- UTC and Special
('tz-500', 'UTC', '+00:00', 0, NULL, datetime('now'), datetime('now')),
('tz-501', 'GMT', '+00:00', 0, NULL, datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'TIMEZONE entity metadata and sample data installed' AS status;
SELECT COUNT(*) AS timezone_count FROM timezone;
SELECT 'NOTE: Timezone data based on IANA Time Zone Database' AS note;
