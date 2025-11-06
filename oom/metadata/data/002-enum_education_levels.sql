-- ============================================
-- DATA SEED: Education Levels
-- Enumeration of education levels
-- ============================================

INSERT OR IGNORE INTO enum_education_level (id, code, name, created_at, updated_at)
VALUES
('e1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'PRIMARY', 'Primary School', datetime('now'), datetime('now')),
('e2a3b4c5-d6e7-4f8a-9b0c-1d2e3f4a5b6c', 'SECONDARY', 'Secondary School', datetime('now'), datetime('now')),
('e3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'HIGH_SCHOOL', 'High School', datetime('now'), datetime('now')),
('e4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'DIPLOMA', 'Diploma', datetime('now'), datetime('now')),
('e5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'ASSOCIATE', 'Associate Degree', datetime('now'), datetime('now')),
('e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'BACHELOR', 'Bachelor Degree', datetime('now'), datetime('now')),
('e7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'MASTER', 'Master Degree', datetime('now'), datetime('now')),
('e8a9b0c1-d2e3-4f4a-5b6c-7d8e9f0a1b2c', 'DOCTORATE', 'Doctorate/PhD', datetime('now'), datetime('now')),
('e9a0b1c2-d3e4-4f5a-6b7c-8d9e0f1a2b3c', 'POST_DOCTORATE', 'Post-Doctorate', datetime('now'), datetime('now'));
