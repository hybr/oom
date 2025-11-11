-- =====================================================================
-- MEDIA_FILE_ACCESS_LOG Entity Metadata - File access audit trail
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('m1a1c1l1-g111-4a11-u111-d111i111t111', 'MEDIA_FILE_ACCESS_LOG', 'Media File Access Log',
        'Immutable audit trail for file access tracking and security', 'MEDIA_FILE', 'media_file_access_log', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('m1a1c1l1-0001-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('m1a1c1l1-0002-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'media_file_id', 'Media File ID', 'text', 1, 0, 0, 0, NULL, 'FK to MEDIA_FILE', 2),
('m1a1c1l1-0003-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'accessed_by_id', 'Accessed By ID', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (null for anonymous)', 3),
('m1a1c1l1-0004-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'accessed_at', 'Accessed At', 'datetime', 1, 0, 0, 0, 'datetime("now")', 'Access timestamp', 4),
('m1a1c1l1-0005-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'access_type', 'Access Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Type of access', 5),
('m1a1c1l1-0006-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'ip_address', 'IP Address', 'text', 0, 0, 0, 0, NULL, 'Client IP address', 6),
('m1a1c1l1-0007-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'user_agent', 'User Agent', 'text', 0, 0, 0, 0, NULL, 'Client user agent', 7),
('m1a1c1l1-0008-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'success', 'Success', 'boolean', 1, 0, 0, 0, '1', 'Access succeeded or failed', 8);

UPDATE entity_attribute SET enum_values = '["VIEW","DOWNLOAD","DELETE"]' WHERE id = 'm1a1c1l1-0005-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('m1a1c1l1-r001-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'MANY_TO_ONE', 'Access Log to Media File', 'media_file_id', 'File that was accessed'),
('m1a1c1l1-r002-0000-0000-000000000001', 'm1a1c1l1-g111-4a11-u111-d111i111t111', (SELECT id FROM entity_definition WHERE code = 'PERSON'), 'MANY_TO_ONE', 'Access Log to Person', 'accessed_by_id', 'Person who accessed file');
