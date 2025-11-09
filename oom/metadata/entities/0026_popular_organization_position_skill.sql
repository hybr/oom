-- =====================================================================
-- POPULAR_ORGANIZATION_POSITION_SKILL Entity Metadata
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('p1p1s1k1-l111-4111-s111-k111i111l111', 'POPULAR_ORGANIZATION_POSITION_SKILL', 'Popular Organization Position Skill',
        'Skills required for organizational positions with proficiency levels', 'POPULAR_ORG_STRUCTURE', 'popular_organization_position_skill', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1p1s1k1-0001-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1p1s1k1-0002-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Record creation timestamp', 2),
('p1p1s1k1-0003-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Record last update timestamp', 3),
('p1p1s1k1-0004-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete timestamp', 4),
('p1p1s1k1-0005-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Optimistic locking version', 5),
('p1p1s1k1-0006-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'User who created', 6),
('p1p1s1k1-0007-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'User who updated', 7),
('p1p1s1k1-0008-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'position_id', 'Position ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_ORGANIZATION_POSITION', 8),
('p1p1s1k1-0009-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'skill_id', 'Skill ID', 'text', 1, 0, 0, 0, NULL, 'FK to POPULAR_SKILL', 9),
('p1p1s1k1-0010-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'proficiency_level', 'Proficiency Level', 'text', 0, 0, 0, 0, NULL, 'FK to ENUM_SKILL_LEVEL', 10),
('p1p1s1k1-0011-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'is_required', 'Is Required', 'boolean', 1, 0, 0, 0, '1', 'Whether skill is required', 11),
('p1p1s1k1-0012-0000-0000-000000000001', 'p1p1s1k1-l111-4111-s111-k111i111l111', 'priority', 'Priority', 'integer', 0, 0, 0, 0, NULL, 'Priority (1=Critical, 2=Important, 3=Nice-to-have)', 12);
