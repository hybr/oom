-- =====================================================================
-- TRAINING_COMPLETION Entity - Training completion records and certificates
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('t1r1c1o1-m1p1-4l1t-n111-111111111111', 'TRAINING_COMPLETION', 'Training Completion', 'Training completion records with certificates and feedback', 'HR_TRAINING_DEVELOPMENT', 'training_completion', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('t1r1c1o1-0001-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('t1r1c1o1-0002-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('t1r1c1o1-0003-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('t1r1c1o1-0004-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('t1r1c1o1-0005-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('t1r1c1o1-0006-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('t1r1c1o1-0007-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('t1r1c1o1-0008-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'training_request_id', 'Training Request ID', 'text', 1, 0, 0, 0, NULL, 'FK to TRAINING_REQUEST', 8),
('t1r1c1o1-0009-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 9),
('t1r1c1o1-0010-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'completion_date', 'Completion Date', 'date', 1, 0, 0, 0, NULL, 'Training completion date', 10),
('t1r1c1o1-0011-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'certificate_url', 'Certificate URL', 'text', 0, 0, 0, 0, NULL, 'Uploaded certificate', 11),
('t1r1c1o1-0012-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'certificate_number', 'Certificate Number', 'text', 0, 0, 0, 0, NULL, 'Certificate ID/number', 12),
('t1r1c1o1-0013-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'score', 'Score', 'number', 0, 0, 0, 0, NULL, 'Score/grade obtained', 13),
('t1r1c1o1-0014-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'feedback', 'Feedback', 'text', 0, 0, 0, 0, NULL, 'Training feedback', 14),
('t1r1c1o1-0015-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'rating', 'Rating', 'integer', 0, 0, 0, 0, NULL, 'Training quality rating (1-5)', 15),
('t1r1c1o1-0016-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'validity_start_date', 'Validity Start Date', 'date', 0, 0, 0, 0, NULL, 'Certificate validity start', 16),
('t1r1c1o1-0017-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'validity_end_date', 'Validity End Date', 'date', 0, 0, 0, 0, NULL, 'Certificate expiry date', 17),
('t1r1c1o1-0018-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'is_verified', 'Is Verified', 'boolean', 1, 0, 0, 0, '0', 'Certificate verified by HR', 18),
('t1r1c1o1-0019-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 19);

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('t1r1c1o1-rel1-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 't1r1a1i1-n1r1-4q1s-t111-111111111111', 'many-to-one', 'completion_to_request', 'training_request_id', 'Training completion for request'),
('t1r1c1o1-rel2-0000-0000-000000000001', 't1r1c1o1-m1p1-4l1t-n111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'completion_to_employee', 'employee_id', 'Employee training completion');
