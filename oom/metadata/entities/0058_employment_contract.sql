-- =====================================================================
-- EMPLOYMENT_CONTRACT Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('e1m1p1c1-o111-4n11-t111-r111a111c111', 'EMPLOYMENT_CONTRACT', 'Employment Contract',
        'Active employment contracts linking employees to organizations and positions', 'HIRING_VACANCY', 'employment_contract', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1m1p1c1-0001-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1m1p1c1-0002-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('e1m1p1c1-0003-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('e1m1p1c1-0004-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('e1m1p1c1-0005-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('e1m1p1c1-0006-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('e1m1p1c1-0007-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('e1m1p1c1-0008-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'organization_id', 'Organization ID', 'text', 1, 0, 0, 0, NULL, 'FK to ORGANIZATION', 8),
('e1m1p1c1-0009-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 9),
('e1m1p1c1-0010-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'position_id', 'Position ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION', 10),
('e1m1p1c1-0011-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'contract_code', 'Contract Code', 'text', 1, 1, 0, 1, NULL, 'Unique contract code', 11),
('e1m1p1c1-0012-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'start_date', 'Start Date', 'date', 1, 0, 0, 0, NULL, 'Employment start date', 12),
('e1m1p1c1-0013-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'end_date', 'End Date', 'date', 0, 0, 0, 0, NULL, 'Employment end date', 13),
('e1m1p1c1-0014-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'salary_amount', 'Salary Amount', 'number', 1, 0, 0, 0, NULL, 'Salary amount', 14),
('e1m1p1c1-0015-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'salary_currency', 'Salary Currency', 'text', 1, 0, 0, 0, NULL, 'Currency code', 15),
('e1m1p1c1-0016-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'contract_type', 'Contract Type', 'enum_strings', 1, 0, 0, 0, NULL, 'Contract type', 16),
('e1m1p1c1-0017-0000-0000-000000000001', 'e1m1p1c1-o111-4n11-t111-r111a111c111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'ACTIVE', 'Contract status', 17);

UPDATE entity_attribute SET enum_values = '["FULL_TIME","PART_TIME","CONTRACT","INTERN","TEMPORARY"]' WHERE id = 'e1m1p1c1-0016-0000-0000-000000000001';
UPDATE entity_attribute SET enum_values = '["ACTIVE","COMPLETED","TERMINATED","SUSPENDED"]' WHERE id = 'e1m1p1c1-0017-0000-0000-000000000001';
