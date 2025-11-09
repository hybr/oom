-- =====================================================================
-- JOB_OFFER Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('j1o1b1o1-f111-4f11-e111-r111o111f111', 'JOB_OFFER', 'Job Offer',
        'Job offers extended to candidates', 'HIRING_VACANCY', 'job_offer', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('j1o1b1o1-0001-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('j1o1b1o1-0002-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('j1o1b1o1-0003-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('j1o1b1o1-0004-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('j1o1b1o1-0005-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('j1o1b1o1-0006-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('j1o1b1o1-0007-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('j1o1b1o1-0008-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'application_id', 'Application ID', 'text', 1, 0, 0, 0, NULL, 'FK to VACANCY_APPLICATION', 8),
('j1o1b1o1-0009-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'offer_code', 'Offer Code', 'text', 1, 1, 0, 1, NULL, 'Unique offer code', 9),
('j1o1b1o1-0010-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'offer_date', 'Offer Date', 'datetime', 1, 0, 0, 0, 'datetime("now")', 'Offer date', 10),
('j1o1b1o1-0011-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'salary_amount', 'Salary Amount', 'number', 1, 0, 0, 0, NULL, 'Offered salary', 11),
('j1o1b1o1-0012-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'salary_currency', 'Salary Currency', 'text', 1, 0, 0, 0, NULL, 'Currency code', 12),
('j1o1b1o1-0013-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'benefits', 'Benefits', 'text', 0, 0, 0, 0, NULL, 'Benefits package', 13),
('j1o1b1o1-0014-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'joining_date', 'Joining Date', 'date', 0, 0, 0, 0, NULL, 'Expected joining date', 14),
('j1o1b1o1-0015-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'expiry_date', 'Expiry Date', 'date', 0, 0, 0, 0, NULL, 'Offer expiry date', 15),
('j1o1b1o1-0016-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'terms_and_conditions', 'Terms and Conditions', 'text', 0, 0, 0, 0, NULL, 'Offer terms', 16),
('j1o1b1o1-0017-0000-0000-000000000001', 'j1o1b1o1-f111-4f11-e111-r111o111f111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'SENT', 'Offer status', 17);

UPDATE entity_attribute SET enum_values = '["SENT","ACCEPTED","REJECTED","WITHDRAWN","EXPIRED"]' WHERE id = 'j1o1b1o1-0017-0000-0000-000000000001';
