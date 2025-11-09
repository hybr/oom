-- =====================================================================
-- ENUM_FILE_CATEGORY Entity Metadata - File category enumeration
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('e1f1c1t1-g111-4o11-r111-y111c111a111', 'ENUM_FILE_CATEGORY', 'File Category',
        'File type classification for processing and validation rules', 'MEDIA_FILE', 'enum_file_category', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1f1c1t1-0001-0000-0000-000000000001', 'e1f1c1t1-g111-4o11-r111-y111c111a111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1f1c1t1-0002-0000-0000-000000000001', 'e1f1c1t1-g111-4o11-r111-y111c111a111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Category code (e.g., IMAGE, DOCUMENT, VIDEO)', 2),
('e1f1c1t1-0003-0000-0000-000000000001', 'e1f1c1t1-g111-4o11-r111-y111c111a111', 'name', 'Name', 'text', 1, 0, 0, 0, NULL, 'Display name', 3),
('e1f1c1t1-0004-0000-0000-000000000001', 'e1f1c1t1-g111-4o11-r111-y111c111a111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Category description and use cases', 4);
