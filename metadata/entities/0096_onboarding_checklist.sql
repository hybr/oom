-- =====================================================================
-- ONBOARDING_CHECKLIST Entity - New hire onboarding task tracking
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('o1n1b1o1-a1r1-4d1n-g111-111111111111', 'ONBOARDING_CHECKLIST', 'Onboarding Checklist', 'New hire onboarding checklist and task tracking', 'HR_EMPLOYEE_LIFECYCLE', 'onboarding_checklist', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('o1n1b1o1-0001-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('o1n1b1o1-0002-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('o1n1b1o1-0003-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('o1n1b1o1-0004-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('o1n1b1o1-0005-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('o1n1b1o1-0006-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('o1n1b1o1-0007-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('o1n1b1o1-0008-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (new hire)', 8),
('o1n1b1o1-0009-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'employment_contract_id', 'Employment Contract ID', 'text', 1, 0, 0, 0, NULL, 'FK to EMPLOYMENT_CONTRACT', 9),
('o1n1b1o1-0010-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'buddy_id', 'Buddy ID', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (onboarding buddy)', 10),
('o1n1b1o1-0011-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'checklist_code', 'Checklist Code', 'text', 1, 1, 0, 1, NULL, 'Unique checklist code', 11),
('o1n1b1o1-0012-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'joining_date', 'Joining Date', 'date', 1, 0, 0, 0, NULL, 'Employee joining date', 12),
('o1n1b1o1-0013-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'onboarding_phase', 'Onboarding Phase', 'enum_strings', 1, 0, 0, 0, 'PRE_JOINING', 'Current phase', 13),
('o1n1b1o1-0014-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'checklist_items', 'Checklist Items', 'json', 1, 0, 0, 0, '[]', 'Array of checklist tasks', 14),
('o1n1b1o1-0015-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'completion_percentage', 'Completion Percentage', 'number', 1, 0, 0, 0, '0', 'Overall completion %', 15),
('o1n1b1o1-0016-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'orientation_date', 'Orientation Date', 'date', 0, 0, 0, 0, NULL, 'HR orientation date', 16),
('o1n1b1o1-0017-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'welcome_kit_issued', 'Welcome Kit Issued', 'boolean', 1, 0, 0, 0, '0', 'Welcome kit provided', 17),
('o1n1b1o1-0018-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'id_card_issued', 'ID Card Issued', 'boolean', 1, 0, 0, 0, '0', 'Employee ID card issued', 18),
('o1n1b1o1-0019-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'system_access_granted', 'System Access Granted', 'boolean', 1, 0, 0, 0, '0', 'IT systems access setup', 19),
('o1n1b1o1-0020-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'workspace_assigned', 'Workspace Assigned', 'boolean', 1, 0, 0, 0, '0', 'Desk/workspace assigned', 20),
('o1n1b1o1-0021-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'probation_end_date', 'Probation End Date', 'date', 0, 0, 0, 0, NULL, 'Expected probation end', 21),
('o1n1b1o1-0022-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'NOT_STARTED', 'Onboarding status', 22),
('o1n1b1o1-0023-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'completed_date', 'Completed Date', 'datetime', 0, 0, 0, 0, NULL, 'Onboarding completion date', 23),
('o1n1b1o1-0024-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, 'Additional notes', 24),
('o1n1b1o1-0025-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 25);

UPDATE entity_attribute SET enum_values = '["PRE_JOINING","DAY_ONE","FIRST_WEEK","FIRST_MONTH","PROBATION_PERIOD"]'
WHERE id = 'o1n1b1o1-0013-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["NOT_STARTED","IN_PROGRESS","COMPLETED","ON_HOLD"]'
WHERE id = 'o1n1b1o1-0022-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('o1n1b1o1-rel1-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'onboarding_to_employee', 'employee_id', 'Employee being onboarded'),
('o1n1b1o1-rel2-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'e1m1p1c1-o1n1-4t1r-c111-111111111111', 'many-to-one', 'onboarding_to_contract', 'employment_contract_id', 'Related employment contract'),
('o1n1b1o1-rel3-0000-0000-000000000001', 'o1n1b1o1-a1r1-4d1n-g111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'onboarding_to_buddy', 'buddy_id', 'Assigned onboarding buddy');
