-- =====================================================================
-- INTERVIEW_STAGE Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('i1s1t1a1-g111-4e11-s111-t111a111g111', 'INTERVIEW_STAGE', 'Interview Stage',
        'Predefined interview stages (HR Screen, Technical Round, etc.)', 'HIRING_VACANCY', 'interview_stage', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('i1s1t1a1-0001-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('i1s1t1a1-0002-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('i1s1t1a1-0003-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('i1s1t1a1-0004-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('i1s1t1a1-0005-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('i1s1t1a1-0006-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('i1s1t1a1-0007-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('i1s1t1a1-0008-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Stage code', 8),
('i1s1t1a1-0009-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'name', 'Name', 'text', 1, 0, 0, 1, NULL, 'Stage name', 9),
('i1s1t1a1-0010-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'sequence_number', 'Sequence Number', 'integer', 1, 0, 0, 0, NULL, 'Stage order', 10),
('i1s1t1a1-0011-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Stage description', 11),
('i1s1t1a1-0012-0000-0000-000000000001', 'i1s1t1a1-g111-4e11-s111-t111a111g111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 12);
