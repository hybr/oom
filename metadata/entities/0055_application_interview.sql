-- =====================================================================
-- APPLICATION_INTERVIEW Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('a1i1n1t1-e111-4r11-v111-i111e111w111', 'APPLICATION_INTERVIEW', 'Application Interview',
        'Interview sessions for job applications', 'HIRING_VACANCY', 'application_interview', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('a1i1n1t1-0001-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('a1i1n1t1-0002-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('a1i1n1t1-0003-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('a1i1n1t1-0004-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('a1i1n1t1-0005-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('a1i1n1t1-0006-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('a1i1n1t1-0007-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('a1i1n1t1-0008-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'application_id', 'Application ID', 'text', 1, 0, 0, 0, NULL, 'FK to VACANCY_APPLICATION', 8),
('a1i1n1t1-0009-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'interview_stage_id', 'Interview Stage ID', 'text', 1, 0, 0, 0, NULL, 'FK to INTERVIEW_STAGE', 9),
('a1i1n1t1-0010-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'scheduled_date', 'Scheduled Date', 'datetime', 1, 0, 0, 0, NULL, 'Interview date/time', 10),
('a1i1n1t1-0011-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'duration_minutes', 'Duration Minutes', 'integer', 0, 0, 0, 0, '60', 'Duration in minutes', 11),
('a1i1n1t1-0012-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'location', 'Location', 'text', 0, 0, 0, 0, NULL, 'Interview location', 12),
('a1i1n1t1-0013-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'interviewer_ids', 'Interviewer IDs', 'json', 0, 0, 0, 0, NULL, 'Array of PERSON IDs', 13),
('a1i1n1t1-0014-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'SCHEDULED', 'Interview status', 14),
('a1i1n1t1-0015-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'feedback', 'Feedback', 'text', 0, 0, 0, 0, NULL, 'Interview feedback', 15),
('a1i1n1t1-0016-0000-0000-000000000001', 'a1i1n1t1-e111-4r11-v111-i111e111w111', 'result', 'Result', 'enum_strings', 0, 0, 0, 0, NULL, 'Interview result', 16);

UPDATE entity_attribute SET enum_values = '["SCHEDULED","IN_PROGRESS","COMPLETED","CANCELLED","RESCHEDULED"]' WHERE id = 'a1i1n1t1-0014-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["PASSED","FAILED","ON_HOLD"]' WHERE id = 'a1i1n1t1-0016-0000-0000-000000000001';
