-- =========================================================
-- ENUM_SKILL_LEVEL Data
-- =========================================================
-- Proficiency levels for skills: Beginner, Novice, Intermediate, Advanced, Expert

INSERT OR IGNORE INTO enum_skill_level (id, code, name, created_at, updated_at)
VALUES
('sl-00000001-0000-4000-0000-000000000001', 'BEGINNER', 'Beginner', datetime('now'), datetime('now')),
('sl-00000002-0000-4000-0000-000000000002', 'NOVICE', 'Novice', datetime('now'), datetime('now')),
('sl-00000003-0000-4000-0000-000000000003', 'INTERMEDIATE', 'Intermediate', datetime('now'), datetime('now')),
('sl-00000004-0000-4000-0000-000000000004', 'ADVANCED', 'Advanced', datetime('now'), datetime('now')),
('sl-00000005-0000-4000-0000-000000000005', 'EXPERT', 'Expert', datetime('now'), datetime('now'));
