-- =====================================================================
-- EXIT_CLEARANCE Entity - Employee exit/offboarding clearance tracking
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('e1x1i1t1-c1l1-4r1n-c111-111111111111', 'EXIT_CLEARANCE', 'Exit Clearance', 'Employee exit clearance and no-dues certificate tracking', 'HR_EMPLOYEE_LIFECYCLE', 'exit_clearance', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1x1i1t1-0001-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1x1i1t1-0002-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('e1x1i1t1-0003-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('e1x1i1t1-0004-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('e1x1i1t1-0005-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('e1x1i1t1-0006-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('e1x1i1t1-0007-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('e1x1i1t1-0008-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (exiting employee)', 8),
('e1x1i1t1-0009-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'employment_contract_id', 'Employment Contract ID', 'text', 1, 0, 0, 0, NULL, 'FK to EMPLOYMENT_CONTRACT', 9),
('e1x1i1t1-0010-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'clearance_code', 'Clearance Code', 'text', 1, 1, 0, 1, NULL, 'Unique clearance code', 10),
('e1x1i1t1-0011-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'resignation_date', 'Resignation Date', 'date', 0, 0, 0, 0, NULL, 'Date of resignation submission', 11),
('e1x1i1t1-0012-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'last_working_day', 'Last Working Day', 'date', 1, 0, 0, 0, NULL, 'Final date of employment', 12),
('e1x1i1t1-0013-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'exit_reason', 'Exit Reason', 'enum_strings', 1, 0, 0, 0, NULL, 'Reason for leaving', 13),
('e1x1i1t1-0014-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'exit_type', 'Exit Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Type of exit', 14),
('e1x1i1t1-0015-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'clearance_checklist', 'Clearance Checklist', 'json', 1, 0, 0, 0, '[]', 'Department-wise clearance tasks', 15),
('e1x1i1t1-0016-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'hr_clearance', 'HR Clearance', 'boolean', 1, 0, 0, 0, '0', 'HR department clearance', 16),
('e1x1i1t1-0017-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'it_clearance', 'IT Clearance', 'boolean', 1, 0, 0, 0, '0', 'IT department clearance', 17),
('e1x1i1t1-0018-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'finance_clearance', 'Finance Clearance', 'boolean', 1, 0, 0, 0, '0', 'Finance department clearance', 18),
('e1x1i1t1-0019-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'admin_clearance', 'Admin Clearance', 'boolean', 1, 0, 0, 0, '0', 'Admin department clearance', 19),
('e1x1i1t1-0020-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'manager_clearance', 'Manager Clearance', 'boolean', 1, 0, 0, 0, '0', 'Reporting manager clearance', 20),
('e1x1i1t1-0021-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'assets_returned', 'Assets Returned', 'boolean', 1, 0, 0, 0, '0', 'All company assets returned', 21),
('e1x1i1t1-0022-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'asset_list', 'Asset List', 'json', 0, 0, 0, 0, NULL, 'List of returned assets', 22),
('e1x1i1t1-0023-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'knowledge_transfer_complete', 'Knowledge Transfer Complete', 'boolean', 1, 0, 0, 0, '0', 'Handover completed', 23),
('e1x1i1t1-0024-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'handover_notes', 'Handover Notes', 'text', 0, 0, 0, 0, NULL, 'Knowledge transfer details', 24),
('e1x1i1t1-0025-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'exit_interview_done', 'Exit Interview Done', 'boolean', 1, 0, 0, 0, '0', 'Exit interview completed', 25),
('e1x1i1t1-0026-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'exit_interview_notes', 'Exit Interview Notes', 'text', 0, 0, 0, 0, NULL, 'Exit interview feedback', 26),
('e1x1i1t1-0027-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'final_settlement_amount', 'Final Settlement Amount', 'number', 0, 0, 0, 0, NULL, 'Full and final settlement', 27),
('e1x1i1t1-0028-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'settlement_date', 'Settlement Date', 'date', 0, 0, 0, 0, NULL, 'Payment settlement date', 28),
('e1x1i1t1-0029-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'no_dues_certificate_issued', 'No Dues Certificate Issued', 'boolean', 1, 0, 0, 0, '0', 'NDC issued', 29),
('e1x1i1t1-0030-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'no_dues_certificate_url', 'No Dues Certificate URL', 'text', 0, 0, 0, 0, NULL, 'NDC document link', 30),
('e1x1i1t1-0031-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'experience_certificate_issued', 'Experience Certificate Issued', 'boolean', 1, 0, 0, 0, '0', 'Experience letter issued', 31),
('e1x1i1t1-0032-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'experience_certificate_url', 'Experience Certificate URL', 'text', 0, 0, 0, 0, NULL, 'Experience certificate link', 32),
('e1x1i1t1-0033-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'eligible_for_rehire', 'Eligible for Rehire', 'boolean', 1, 0, 0, 0, '1', 'Rehire eligibility', 33),
('e1x1i1t1-0034-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'INITIATED', 'Clearance status', 34),
('e1x1i1t1-0035-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'completed_date', 'Completed Date', 'datetime', 0, 0, 0, 0, NULL, 'Clearance completion date', 35),
('e1x1i1t1-0036-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, 'Additional notes', 36),
('e1x1i1t1-0037-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 37);

UPDATE entity_attribute SET enum_values = '["BETTER_OPPORTUNITY","HIGHER_EDUCATION","RELOCATION","PERSONAL_REASONS","HEALTH_REASONS","RETIREMENT","CAREER_CHANGE","WORK_LIFE_BALANCE","COMPENSATION","OTHER"]'
WHERE id = 'e1x1i1t1-0013-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["RESIGNATION","TERMINATION","RETIREMENT","END_OF_CONTRACT","MUTUAL_SEPARATION","ABSCONDING"]'
WHERE id = 'e1x1i1t1-0014-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["INITIATED","IN_PROGRESS","PENDING_CLEARANCES","COMPLETED","ON_HOLD"]'
WHERE id = 'e1x1i1t1-0034-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('e1x1i1t1-rel1-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'exit_to_employee', 'employee_id', 'Employee exiting'),
('e1x1i1t1-rel2-0000-0000-000000000001', 'e1x1i1t1-c1l1-4r1n-c111-111111111111', 'e1m1p1c1-o1n1-4t1r-c111-111111111111', 'many-to-one', 'exit_to_contract', 'employment_contract_id', 'Related employment contract');
