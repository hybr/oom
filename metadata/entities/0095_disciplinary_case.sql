-- =====================================================================
-- DISCIPLINARY_CASE Entity - Employee disciplinary actions
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('d1i1s1c1-c1a1-4s1e-a111-111111111111', 'DISCIPLINARY_CASE', 'Disciplinary Case', 'Employee disciplinary actions and warnings', 'HR_EMPLOYEE_RELATIONS', 'disciplinary_case', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('d1i1s1c1-0001-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('d1i1s1c1-0002-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('d1i1s1c1-0003-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('d1i1s1c1-0004-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('d1i1s1c1-0005-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('d1i1s1c1-0006-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('d1i1s1c1-0007-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('d1i1s1c1-0008-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 8),
('d1i1s1c1-0009-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'case_code', 'Case Code', 'text', 1, 1, 0, 1, NULL, 'Unique case code', 9),
('d1i1s1c1-0010-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'violation_type', 'Violation Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Type of violation', 10),
('d1i1s1c1-0011-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'incident_date', 'Incident Date', 'date', 1, 0, 0, 0, NULL, 'Date of incident', 11),
('d1i1s1c1-0012-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'description', 'Description', 'text', 1, 0, 0, 0, NULL, 'Incident description', 12),
('d1i1s1c1-0013-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'evidence_urls', 'Evidence URLs', 'json', 0, 0, 0, 0, NULL, 'Supporting evidence', 13),
('d1i1s1c1-0014-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'action_taken', 'Action Taken', 'enum_strings', 0, 0, 0, 0, NULL, 'Disciplinary action', 14),
('d1i1s1c1-0015-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'warning_letter_url', 'Warning Letter URL', 'text', 0, 0, 0, 0, NULL, 'Warning letter document', 15),
('d1i1s1c1-0016-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'warning_expiry_date', 'Warning Expiry Date', 'date', 0, 0, 0, 0, NULL, 'When warning expires', 16),
('d1i1s1c1-0017-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'OPEN', 'Case status', 17),
('d1i1s1c1-0018-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 18);

UPDATE entity_attribute SET enum_values = '["ATTENDANCE_ISSUES","PERFORMANCE_ISSUES","BEHAVIORAL_ISSUES","POLICY_VIOLATION","CODE_OF_CONDUCT_BREACH","FRAUD_OR_THEFT","INSUBORDINATION","HARASSMENT","OTHER"]'
WHERE id = 'd1i1s1c1-0010-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["VERBAL_WARNING","WRITTEN_WARNING","FINAL_WARNING","SUSPENSION","DEMOTION","TERMINATION","NO_ACTION"]'
WHERE id = 'd1i1s1c1-0014-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["OPEN","UNDER_INVESTIGATION","RESOLVED","APPEALED","CLOSED"]'
WHERE id = 'd1i1s1c1-0017-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('d1i1s1c1-rel1-0000-0000-000000000001', 'd1i1s1c1-c1a1-4s1e-a111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'disciplinary_to_employee', 'employee_id', 'Employee under discipline');
