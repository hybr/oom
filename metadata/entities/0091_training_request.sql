-- =====================================================================
-- TRAINING_REQUEST Entity - Training enrollment requests
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('t1r1a1i1-n1r1-4q1s-t111-111111111111', 'TRAINING_REQUEST', 'Training Request', 'Employee training requests and approvals', 'HR_TRAINING_DEVELOPMENT', 'training_request', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('t1r1a1i1-0001-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('t1r1a1i1-0002-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('t1r1a1i1-0003-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('t1r1a1i1-0004-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('t1r1a1i1-0005-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('t1r1a1i1-0006-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('t1r1a1i1-0007-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('t1r1a1i1-0008-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 8),
('t1r1a1i1-0009-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'approved_by', 'Approved By', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (approver)', 9),
('t1r1a1i1-0010-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'request_code', 'Request Code', 'text', 1, 1, 0, 1, NULL, 'Unique request code', 10),
('t1r1a1i1-0011-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'training_name', 'Training Name', 'text', 1, 0, 0, 1, NULL, 'Name of training/course', 11),
('t1r1a1i1-0012-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'training_type', 'Training Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Type of training', 12),
('t1r1a1i1-0013-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'provider', 'Provider', 'text', 0, 0, 0, 0, NULL, 'Training provider/vendor', 13),
('t1r1a1i1-0014-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'cost', 'Cost', 'number', 1, 0, 0, 0, NULL, 'Training cost', 14),
('t1r1a1i1-0015-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'currency', 'Currency', 'text', 1, 0, 0, 0, 'USD', 'Currency code', 15),
('t1r1a1i1-0016-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'start_date', 'Start Date', 'date', 1, 0, 0, 0, NULL, 'Training start date', 16),
('t1r1a1i1-0017-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'end_date', 'End Date', 'date', 1, 0, 0, 0, NULL, 'Training end date', 17),
('t1r1a1i1-0018-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'justification', 'Justification', 'text', 1, 0, 0, 0, NULL, 'Business justification', 18),
('t1r1a1i1-0019-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'expected_benefits', 'Expected Benefits', 'text', 0, 0, 0, 0, NULL, 'Expected outcomes', 19),
('t1r1a1i1-0020-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'SUBMITTED', 'Request status', 20),
('t1r1a1i1-0021-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'requires_bond', 'Requires Bond', 'boolean', 1, 0, 0, 0, '0', 'Whether training bond required', 21),
('t1r1a1i1-0022-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'bond_duration_months', 'Bond Duration (Months)', 'integer', 0, 0, 0, 0, NULL, 'Service commitment duration', 22),
('t1r1a1i1-0023-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 23);

UPDATE entity_attribute SET enum_values = '["INTERNAL_TRAINING","EXTERNAL_TRAINING","CERTIFICATION","WORKSHOP","CONFERENCE","ONLINE_COURSE"]'
WHERE id = 't1r1a1i1-0012-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["SUBMITTED","APPROVED","REJECTED","COMPLETED","CANCELLED"]'
WHERE id = 't1r1a1i1-0020-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('t1r1a1i1-rel1-0000-0000-000000000001', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'training_request_to_employee', 'employee_id', 'Training requested by employee');
