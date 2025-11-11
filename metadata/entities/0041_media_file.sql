-- =====================================================================
-- MEDIA_FILE Entity Metadata - Centralized file management with versioning
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('m1e1d1a1-f111-4i11-l111-e111m111g111', 'MEDIA_FILE', 'Media File',
        'Centralized file management with versioning and access control', 'MEDIA_FILE', 'media_file', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('m1e1d1a1-0001-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('m1e1d1a1-0002-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('m1e1d1a1-0003-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('m1e1d1a1-0004-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('m1e1d1a1-0005-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('m1e1d1a1-0006-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('m1e1d1a1-0007-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('m1e1d1a1-0008-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'file_name', 'File Name', 'text', 1, 0, 0, 1, NULL, 'Stored filename (e.g., abc123.jpg)', 8),
('m1e1d1a1-0009-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'original_file_name', 'Original File Name', 'text', 1, 0, 0, 0, NULL, 'User original filename', 9),
('m1e1d1a1-0010-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'file_path', 'File Path', 'text', 1, 0, 0, 0, NULL, 'Path in storage (e.g., uploads/2024/01/)', 10),
('m1e1d1a1-0011-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'file_url', 'File URL', 'text', 1, 0, 0, 0, NULL, 'Full accessible URL', 11),
('m1e1d1a1-0012-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'storage_provider', 'Storage Provider', 'text', 1, 0, 0, 0, NULL, 'FK to ENUM_STORAGE_PROVIDER', 12),
('m1e1d1a1-0013-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'storage_key', 'Storage Key', 'text', 1, 0, 0, 0, NULL, 'Provider-specific identifier', 13),
('m1e1d1a1-0014-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'mime_type', 'MIME Type', 'text', 1, 0, 0, 0, NULL, 'File MIME type (e.g., image/jpeg)', 14),
('m1e1d1a1-0015-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'file_size_bytes', 'File Size (Bytes)', 'integer', 1, 0, 0, 0, NULL, 'File size in bytes', 15),
('m1e1d1a1-0016-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'file_category', 'File Category', 'text', 1, 0, 0, 0, NULL, 'FK to ENUM_FILE_CATEGORY', 16),
('m1e1d1a1-0017-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'entity_type', 'Entity Type', 'text', 1, 0, 0, 0, NULL, 'Polymorphic: ORGANIZATION, PERSON, etc.', 17),
('m1e1d1a1-0018-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'entity_id', 'Entity ID', 'text', 1, 0, 0, 0, NULL, 'Polymorphic FK to entity_type', 18),
('m1e1d1a1-0019-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'field_context', 'Field Context', 'text', 1, 0, 0, 0, NULL, 'FK to ENUM_MEDIA_CONTEXT', 19),
('m1e1d1a1-0020-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'uploaded_by_id', 'Uploaded By ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (uploader)', 20),
('m1e1d1a1-0021-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'uploaded_at', 'Uploaded At', 'datetime', 1, 0, 0, 0, 'datetime("now")', 'Upload timestamp', 21),
('m1e1d1a1-0022-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'is_public', 'Is Public', 'boolean', 1, 0, 0, 0, '0', 'Public URL vs auth required', 22),
('m1e1d1a1-0023-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'version_number', 'Version Number', 'integer', 1, 0, 0, 0, '1', 'File version (starts at 1)', 23),
('m1e1d1a1-0024-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'replaces_file_id', 'Replaces File ID', 'text', 0, 0, 0, 0, NULL, 'FK to MEDIA_FILE (previous version)', 24),
('m1e1d1a1-0025-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'metadata', 'Metadata', 'json', 0, 0, 0, 0, NULL, 'Width, height, duration, pages, etc.', 25),
('m1e1d1a1-0026-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Current version flag', 26),
('m1e1d1a1-0027-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'deleted_by', 'Deleted By', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (who deleted)', 27);

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('m1e1d1a1-r001-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', (SELECT id FROM entity_definition WHERE code = 'PERSON'), 'MANY_TO_ONE', 'Media File to Uploader', 'uploaded_by_id', 'Person who uploaded file'),
('m1e1d1a1-r002-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', (SELECT id FROM entity_definition WHERE code = 'PERSON'), 'MANY_TO_ONE', 'Media File to Deleter', 'deleted_by', 'Person who deleted file'),
('m1e1d1a1-r003-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'MANY_TO_ONE', 'Media File Version Chain', 'replaces_file_id', 'Previous file version'),
('m1e1d1a1-r004-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'e1s1t1g1-p111-4r11-o111-v111i111d111', 'MANY_TO_ONE', 'Media File to Storage Provider', 'storage_provider', 'Storage backend'),
('m1e1d1a1-r005-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'e1f1c1t1-g111-4o11-r111-y111c111a111', 'MANY_TO_ONE', 'Media File to File Category', 'file_category', 'File type classification'),
('m1e1d1a1-r006-0000-0000-000000000001', 'm1e1d1a1-f111-4i11-l111-e111m111g111', 'e1m1c1x1-t111-4e11-x111-t111c111o111', 'MANY_TO_ONE', 'Media File to Media Context', 'field_context', 'Semantic meaning of file');
