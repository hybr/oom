-- =====================================================================
-- ENUM_MEDIA_CONTEXT Entity Metadata - Media context enumeration
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('e1m1c1x1-t111-4e11-x111-t111c111o111', 'ENUM_MEDIA_CONTEXT', 'Media Context',
        'Semantic meaning of file for entity fields (e.g., LOGO, RESUME, PROFILE_PICTURE)', 'MEDIA_FILE', 'enum_media_context', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1m1c1x1-0001-0000-0000-000000000001', 'e1m1c1x1-t111-4e11-x111-t111c111o111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1m1c1x1-0002-0000-0000-000000000001', 'e1m1c1x1-t111-4e11-x111-t111c111o111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Context code (e.g., LOGO, RESUME, PROFILE_PICTURE)', 2),
('e1m1c1x1-0003-0000-0000-000000000001', 'e1m1c1x1-t111-4e11-x111-t111c111o111', 'name', 'Name', 'text', 1, 0, 0, 0, NULL, 'Display name', 3),
('e1m1c1x1-0004-0000-0000-000000000001', 'e1m1c1x1-t111-4e11-x111-t111c111o111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Context description and applicable entity types', 4);
