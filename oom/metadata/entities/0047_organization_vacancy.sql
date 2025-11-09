-- =====================================================================
-- ORGANIZATION_VACANCY Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'ORGANIZATION_VACANCY', 'Organization Vacancy',
        'Job vacancies or open positions within an organization', 'HIRING_VACANCY', 'organization_vacancy', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('o1v1a1c1-0001-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('o1v1a1c1-0002-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('o1v1a1c1-0003-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('o1v1a1c1-0004-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('o1v1a1c1-0005-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Optimistic locking version', 5),
('o1v1a1c1-0006-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('o1v1a1c1-0007-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Last updater', 7),
('o1v1a1c1-0008-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, 'FK to ORGANIZATION', 8),
('o1v1a1c1-0009-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'popular_position_id', 'Position ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION', 9),
('o1v1a1c1-0010-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'vacancy_code', 'Vacancy Code', 'text', 1, 1, 0, 1, NULL, 'Unique vacancy code', 10),
('o1v1a1c1-0011-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'title', 'Title', 'text', 1, 0, 0, 1, NULL, 'Job title', 11),
('o1v1a1c1-0012-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'description', 'Description', 'text', 1, 0, 0, 0, NULL, 'Job description', 12),
('o1v1a1c1-0013-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'responsibilities', 'Responsibilities', 'text', 0, 0, 0, 0, NULL, 'Key responsibilities', 13),
('o1v1a1c1-0014-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'requirements', 'Requirements', 'text', 0, 0, 0, 0, NULL, 'Job requirements', 14),
('o1v1a1c1-0015-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'preferred_qualifications', 'Preferred Qualifications', 'text', 0, 0, 0, 0, NULL, 'Preferred qualifications', 15),
('o1v1a1c1-0016-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'employment_type', 'Employment Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Employment type', 16),
('o1v1a1c1-0017-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'experience_required', 'Experience Required', 'integer', 0, 0, 0, 0, NULL, 'Years of experience', 17),
('o1v1a1c1-0018-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'salary_min', 'Minimum Salary', 'number', 0, 0, 0, 0, NULL, 'Minimum salary', 18),
('o1v1a1c1-0019-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'salary_max', 'Maximum Salary', 'number', 0, 0, 0, 0, NULL, 'Maximum salary', 19),
('o1v1a1c1-0020-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'salary_currency', 'Salary Currency', 'text', 0, 0, 0, 0, NULL, 'Currency code', 20),
('o1v1a1c1-0021-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'number_of_openings', 'Number of Openings', 'integer', 1, 0, 0, 0, '1', 'Total positions', 21),
('o1v1a1c1-0022-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'filled_positions', 'Filled Positions', 'integer', 1, 0, 0, 0, '0', 'Positions filled', 22),
('o1v1a1c1-0023-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'application_deadline', 'Application Deadline', 'date', 0, 0, 0, 0, NULL, 'Last date to apply', 23),
('o1v1a1c1-0024-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'start_date', 'Start Date', 'date', 0, 0, 0, 0, NULL, 'Expected start date', 24),
('o1v1a1c1-0025-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'work_location_type', 'Work Location Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Work location type', 25),
('o1v1a1c1-0026-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'is_remote_allowed', 'Is Remote Allowed', 'boolean', 1, 0, 0, 0, '0', 'Remote work allowed', 26),
('o1v1a1c1-0027-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'DRAFT', 'Vacancy status', 27),
('o1v1a1c1-0028-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'published_date', 'Published Date', 'datetime', 0, 0, 0, 0, NULL, 'Publication date', 28),
('o1v1a1c1-0029-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'closed_date', 'Closed Date', 'datetime', 0, 0, 0, 0, NULL, 'Closing date', 29),
('o1v1a1c1-0030-0000-0000-000000000001', 'o1v1a1c1-a1n1-4c11-y111-v111a111c111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 30);

UPDATE entity_attribute SET enum_values = '["FULL_TIME","PART_TIME","CONTRACT","INTERN","TEMPORARY"]' WHERE id = 'o1v1a1c1-0016-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["ON_SITE","REMOTE","HYBRID"]' WHERE id = 'o1v1a1c1-0025-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["DRAFT","PUBLISHED","CLOSED","CANCELLED","ON_HOLD"]' WHERE id = 'o1v1a1c1-0027-0000-0000-000000000001';
