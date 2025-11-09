-- =====================================================================
-- POPULAR_ORGANIZATION_POSITION Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1p1o1s1-i111-4t11-i111-o111n111p111', 'POPULAR_ORGANIZATION_POSITION', 'Popular Organization Position',
        'Positions combining department, team, and designation', 'POPULAR_ORG_STRUCTURE', 'popular_organization_position', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1p1o1s1-0001-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1p1o1s1-0002-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Record creation timestamp', 2),
('p1p1o1s1-0003-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Record last update timestamp', 3),
('p1p1o1s1-0004-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('p1p1o1s1-0005-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Optimistic locking version', 5),
('p1p1o1s1-0006-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'User who created', 6),
('p1p1o1s1-0007-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'User who updated', 7),
('p1p1o1s1-0008-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'department_id', 'Department ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_DEPARTMENTS', 8),
('p1p1o1s1-0009-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'team_id', 'Team ID', 'text', 0, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (optional)', 9),
('p1p1o1s1-0010-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'designation_id', 'Designation ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_DESIGNATION', 10),
('p1p1o1s1-0011-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Position code', 11),
('p1p1o1s1-0012-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Position description', 12),
('p1p1o1s1-0013-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'responsibilities', 'Responsibilities', 'text', 0, 0, 0, 0, NULL, 'Key responsibilities', 13),
('p1p1o1s1-0014-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'experience_years', 'Experience Years', 'integer', 0, 0, 0, 0, NULL, 'Required years of experience', 14),
('p1p1o1s1-0015-0000-0000-000000000001', 'p1p1o1s1-i111-4t11-i111-o111n111p111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 15);
