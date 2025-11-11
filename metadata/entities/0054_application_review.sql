-- =====================================================================
-- APPLICATION_REVIEW Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('a1r1e1v1-i111-4e11-w111-r111e111v111', 'APPLICATION_REVIEW', 'Application Review',
        'Reviews and evaluations of job applications', 'HIRING_VACANCY', 'application_review', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('a1r1e1v1-0001-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('a1r1e1v1-0002-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('a1r1e1v1-0003-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('a1r1e1v1-0004-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('a1r1e1v1-0005-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('a1r1e1v1-0006-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('a1r1e1v1-0007-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('a1r1e1v1-0008-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'application_id', 'Application ID', 'text', 1, 0, 0, 0, NULL, 'FK to VACANCY_APPLICATION', 8),
('a1r1e1v1-0009-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'reviewer_id', 'Reviewer ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 9),
('a1r1e1v1-0010-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'review_date', 'Review Date', 'datetime', 1, 0, 0, 0, 'datetime("now")', 'Review date', 10),
('a1r1e1v1-0011-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'rating', 'Rating', 'integer', 0, 0, 0, 0, NULL, 'Rating (1-5)', 11),
('a1r1e1v1-0012-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'comments', 'Comments', 'text', 0, 0, 0, 0, NULL, 'Review comments', 12),
('a1r1e1v1-0013-0000-0000-000000000001', 'a1r1e1v1-i111-4e11-w111-r111e111v111', 'recommendation', 'Recommendation', 'enum_strings', 1, 0, 0, 0, NULL, 'Recommendation', 13);

UPDATE entity_attribute SET enum_values = '["SHORTLIST","REJECT","INTERVIEW","HOLD"]' WHERE id = 'a1r1e1v1-0013-0000-0000-000000000001';
