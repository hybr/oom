-- =====================================================================
-- LEAVE_BALANCE Entity Metadata
-- Employee leave entitlements and balance tracking
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'l1e1a1v1-b1a1-4l1n-c111-111111111111',
    'LEAVE_BALANCE',
    'Leave Balance',
    'Employee leave entitlements and balance tracking by year and leave type',
    'HR_EMPLOYEE_MANAGEMENT',
    'leave_balance',
    1
);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('l1e1a1v1-b001-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('l1e1a1v1-b002-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('l1e1a1v1-b003-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('l1e1a1v1-b004-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('l1e1a1v1-b005-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('l1e1a1v1-b006-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('l1e1a1v1-b007-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('l1e1a1v1-b008-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 8),
('l1e1a1v1-b009-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'leave_type', 'Leave Type', 'enum_strings', 1, 0, 0, 1, NULL, 'Type of leave', 9),
('l1e1a1v1-b010-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'year', 'Year', 'integer', 1, 0, 0, 0, NULL, 'Calendar year', 10),
('l1e1a1v1-b011-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'total_entitled', 'Total Entitled', 'number', 1, 0, 0, 0, NULL, 'Total entitled leave days', 11),
('l1e1a1v1-b012-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'used', 'Used', 'number', 1, 0, 0, 0, '0', 'Leave days used', 12),
('l1e1a1v1-b013-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'balance', 'Balance', 'number', 1, 0, 0, 0, NULL, 'Remaining leave balance', 13),
('l1e1a1v1-b014-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'carried_forward', 'Carried Forward', 'number', 1, 0, 0, 0, '0', 'Leave carried from previous year', 14),
('l1e1a1v1-b015-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'encashed', 'Encashed', 'number', 1, 0, 0, 0, '0', 'Leave days encashed', 15),
('l1e1a1v1-b016-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 16);

UPDATE entity_attribute SET enum_values = '["CASUAL_LEAVE","SICK_LEAVE","EARNED_LEAVE","MATERNITY_LEAVE","PATERNITY_LEAVE","COMP_OFF"]'
WHERE id = 'l1e1a1v1-b009-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('l1e1a1v1-b-rel1-0000-0000-000000000001', 'l1e1a1v1-b1a1-4l1n-c111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'leave_balance_to_employee', 'employee_id', 'Employee leave balance');
