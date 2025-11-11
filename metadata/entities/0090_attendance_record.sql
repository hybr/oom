-- =====================================================================
-- ATTENDANCE_RECORD Entity Metadata - Daily attendance tracking
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('a1t1t1n1-r1e1-4c1r-d111-111111111111', 'ATTENDANCE_RECORD', 'Attendance Record', 'Daily employee attendance with punch in/out times', 'HR_EMPLOYEE_MANAGEMENT', 'attendance_record', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('a1t1t1n1-0001-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('a1t1t1n1-0002-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('a1t1t1n1-0003-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('a1t1t1n1-0004-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('a1t1t1n1-0005-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('a1t1t1n1-0006-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('a1t1t1n1-0007-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('a1t1t1n1-0008-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 8),
('a1t1t1n1-0009-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'date', 'Date', 'date', 1, 0, 0, 1, NULL, 'Attendance date', 9),
('a1t1t1n1-0010-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'punch_in', 'Punch In', 'time', 0, 0, 0, 0, NULL, 'Check-in time', 10),
('a1t1t1n1-0011-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'punch_out', 'Punch Out', 'time', 0, 0, 0, 0, NULL, 'Check-out time', 11),
('a1t1t1n1-0012-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'total_hours', 'Total Hours', 'number', 0, 0, 0, 0, NULL, 'Total hours worked', 12),
('a1t1t1n1-0013-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'PRESENT', 'Attendance status', 13),
('a1t1t1n1-0014-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'is_regularized', 'Is Regularized', 'boolean', 1, 0, 0, 0, '0', 'Whether attendance was regularized', 14),
('a1t1t1n1-0015-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'regularization_reason', 'Regularization Reason', 'text', 0, 0, 0, 0, NULL, 'Reason for regularization', 15),
('a1t1t1n1-0016-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'regularized_by', 'Regularized By', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (approver)', 16),
('a1t1t1n1-0017-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 17);

UPDATE entity_attribute SET enum_values = '["PRESENT","ABSENT","HALF_DAY","ON_LEAVE","WORK_FROM_HOME","HOLIDAY","WEEKEND"]'
WHERE id = 'a1t1t1n1-0013-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('a1t1t1n1-rel1-0000-0000-000000000001', 'a1t1t1n1-r1e1-4c1r-d111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'attendance_to_employee', 'employee_id', 'Employee attendance');
