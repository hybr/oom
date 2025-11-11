-- =====================================================================
-- VACANCY_APPLICATION Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('v1a1p1p1-l111-4111-a111-p111p111l111', 'VACANCY_APPLICATION', 'Vacancy Application',
        'Applications submitted by candidates for job vacancies', 'HIRING_VACANCY', 'vacancy_application', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('v1a1p1p1-0001-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('v1a1p1p1-0002-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('v1a1p1p1-0003-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('v1a1p1p1-0004-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('v1a1p1p1-0005-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('v1a1p1p1-0006-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('v1a1p1p1-0007-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('v1a1p1p1-0008-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'vacancy_id', 'Vacancy ID', 'text', 1, 0, 0, 0, NULL, 'FK to ORGANIZATION_VACANCY', 8),
('v1a1p1p1-0009-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'applicant_id', 'Applicant ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 9),
('v1a1p1p1-0010-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'application_code', 'Application Code', 'text', 1, 1, 0, 1, NULL, 'Unique code', 10),
('v1a1p1p1-0011-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'application_date', 'Application Date', 'datetime', 1, 0, 0, 0, 'datetime("now")', 'Date applied', 11),
('v1a1p1p1-0012-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'cover_letter', 'Cover Letter', 'text', 0, 0, 0, 0, NULL, 'Cover letter', 12),
('v1a1p1p1-0013-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'expected_salary', 'Expected Salary', 'number', 0, 0, 0, 0, NULL, 'Expected salary', 13),
('v1a1p1p1-0014-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'notice_period_days', 'Notice Period Days', 'integer', 0, 0, 0, 0, NULL, 'Notice period', 14),
('v1a1p1p1-0015-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'SUBMITTED', 'Application status', 15),
('v1a1p1p1-0016-0000-0000-000000000001', 'v1a1p1p1-l111-4111-a111-p111p111l111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, 'Internal notes', 16);

UPDATE entity_attribute SET enum_values = '["SUBMITTED","UNDER_REVIEW","SHORTLISTED","REJECTED","INTERVIEW_SCHEDULED","OFFER_MADE","ACCEPTED","WITHDRAWN"]' WHERE id = 'v1a1p1p1-0015-0000-0000-000000000001';
