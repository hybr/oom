-- =====================================================================
-- GRIEVANCE_CASE Entity - Employee grievance complaints
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('g1r1i1e1-v1c1-4a1s-e111-111111111111', 'GRIEVANCE_CASE', 'Grievance Case', 'Employee grievance complaints and resolutions', 'HR_EMPLOYEE_RELATIONS', 'grievance_case', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('g1r1i1e1-0001-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('g1r1i1e1-0002-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('g1r1i1e1-0003-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('g1r1i1e1-0004-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('g1r1i1e1-0005-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('g1r1i1e1-0006-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('g1r1i1e1-0007-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('g1r1i1e1-0008-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'complainant_id', 'Complainant ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (employee filing grievance)', 8),
('g1r1i1e1-0009-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'assigned_to', 'Assigned To', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (investigator)', 9),
('g1r1i1e1-0010-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'grievance_code', 'Grievance Code', 'text', 1, 1, 0, 1, NULL, 'Unique grievance code', 10),
('g1r1i1e1-0011-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'category', 'Category', 'enum_strings', 1, 0, 0, 0, NULL, 'Grievance category', 11),
('g1r1i1e1-0012-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'description', 'Description', 'text', 1, 0, 0, 0, NULL, 'Detailed description', 12),
('g1r1i1e1-0013-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'severity', 'Severity', 'enum_strings', 1, 0, 0, 0, 'MEDIUM', 'Severity level', 13),
('g1r1i1e1-0014-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'evidence_urls', 'Evidence URLs', 'json', 0, 0, 0, 0, NULL, 'Supporting evidence documents', 14),
('g1r1i1e1-0015-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'is_anonymous', 'Is Anonymous', 'boolean', 1, 0, 0, 0, '0', 'Anonymous complaint', 15),
('g1r1i1e1-0016-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'SUBMITTED', 'Case status', 16),
('g1r1i1e1-0017-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'resolution', 'Resolution', 'text', 0, 0, 0, 0, NULL, 'Resolution details', 17),
('g1r1i1e1-0018-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'resolution_date', 'Resolution Date', 'datetime', 0, 0, 0, 0, NULL, 'Date resolved', 18),
('g1r1i1e1-0019-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'closed_date', 'Closed Date', 'datetime', 0, 0, 0, 0, NULL, 'Date case closed', 19),
('g1r1i1e1-0020-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 20);

UPDATE entity_attribute SET enum_values = '["WORKPLACE_HARASSMENT","DISCRIMINATION","UNFAIR_TREATMENT","POLICY_VIOLATION","SALARY_ISSUE","WORK_ENVIRONMENT","MANAGER_CONFLICT","OTHER"]'
WHERE id = 'g1r1i1e1-0011-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["LOW","MEDIUM","HIGH","CRITICAL"]'
WHERE id = 'g1r1i1e1-0013-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["SUBMITTED","UNDER_INVESTIGATION","SUBSTANTIATED","UNSUBSTANTIATED","RESOLVED","CLOSED"]'
WHERE id = 'g1r1i1e1-0016-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('g1r1i1e1-rel1-0000-0000-000000000001', 'g1r1i1e1-v1c1-4a1s-e111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'grievance_to_complainant', 'complainant_id', 'Employee complainant');
