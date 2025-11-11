-- =====================================================================
-- ENUM_STORAGE_PROVIDER Entity Metadata - Storage provider enumeration
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('e1s1t1g1-p111-4r11-o111-v111i111d111', 'ENUM_STORAGE_PROVIDER', 'Storage Provider',
        'File storage provider enumeration for multi-backend support', 'MEDIA_FILE', 'enum_storage_provider', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1s1t1g1-0001-0000-0000-000000000001', 'e1s1t1g1-p111-4r11-o111-v111i111d111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1s1t1g1-0002-0000-0000-000000000001', 'e1s1t1g1-p111-4r11-o111-v111i111d111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Provider code (e.g., LOCAL, S3, AZURE)', 2),
('e1s1t1g1-0003-0000-0000-000000000001', 'e1s1t1g1-p111-4r11-o111-v111i111d111', 'name', 'Name', 'text', 1, 0, 0, 0, NULL, 'Display name', 3),
('e1s1t1g1-0004-0000-0000-000000000001', 'e1s1t1g1-p111-4r11-o111-v111i111d111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Provider description', 4);
