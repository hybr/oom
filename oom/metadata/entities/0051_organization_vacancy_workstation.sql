-- =====================================================================
-- ORGANIZATION_VACANCY_WORKSTATION Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('o1v1w1s1-t111-4111-w111-s111t111n111', 'ORGANIZATION_VACANCY_WORKSTATION', 'Organization Vacancy Workstation',
        'Junction table linking vacancies to workstations', 'HIRING_VACANCY', 'organization_vacancy_workstation', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('o1v1w1s1-0001-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('o1v1w1s1-0002-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('o1v1w1s1-0003-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('o1v1w1s1-0004-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('o1v1w1s1-0005-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('o1v1w1s1-0006-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('o1v1w1s1-0007-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('o1v1w1s1-0008-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'vacancy_id', 'Vacancy ID', 'text', 1, 0, 0, 0, NULL, 'FK to ORGANIZATION_VACANCY', 8),
('o1v1w1s1-0009-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'workstation_id', 'Workstation ID', 'text', 1, 0, 0, 0, NULL, 'FK to WORKSTATION', 9),
('o1v1w1s1-0010-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'assigned_to_applicant_id', 'Assigned Applicant ID', 'text', 0, 0, 0, 0, NULL, 'FK to PERSON (selected candidate)', 10),
('o1v1w1s1-0011-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'assigned_date', 'Assigned Date', 'datetime', 0, 0, 0, 0, NULL, 'Assignment date', 11),
('o1v1w1s1-0012-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'is_occupied', 'Is Occupied', 'boolean', 1, 0, 0, 0, '0', 'Occupied status', 12),
('o1v1w1s1-0013-0000-0000-000000000001', 'o1v1w1s1-t111-4111-w111-s111t111n111', 'notes', 'Notes', 'text', 0, 0, 0, 0, NULL, 'Assignment notes', 13);
