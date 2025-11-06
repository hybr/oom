-- ============================================
-- DATA SEED: Popular Organization Skill Requirements
-- Skill requirements for various positions
-- ============================================

-- Backend Developer Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Junior Backend Developer
('s1a2b3c4-e5f6-7001-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'Python fundamentals', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7002-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 'a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Beginner', 1, 0, 'JavaScript basics', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7003-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 'e1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'SQL fundamentals', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7004-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 'l1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'Version control with Git', datetime('now'), datetime('now')),

-- Backend Developer (Mid)
('s1a2b3c4-e5f6-7010-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Strong Python with 2+ years', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7011-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 'c6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Intermediate', 0, 2, 'Node.js experience preferred', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7012-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 'e1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Database design and optimization', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7013-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 'g4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Intermediate', 1, 1, 'Docker containerization', datetime('now'), datetime('now')),

-- Senior Backend Developer
('s1a2b3c4-e5f6-7020-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Advanced', 1, 5, 'Expert Python with 5+ years', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7021-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 'e1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Advanced', 1, 5, 'Advanced database design', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7022-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 'g5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Intermediate', 1, 2, 'Container orchestration', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7023-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 'g1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 0, 2, 'Cloud platform AWS', datetime('now'), datetime('now'));

-- Frontend Developer Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Junior Frontend Developer
('s1a2b3c4-e5f6-7030-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 'c1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'HTML5 fundamentals', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7031-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 'c2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Beginner', 1, 0, 'CSS3 and responsive design', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7032-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 'a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Beginner', 1, 0, 'JavaScript fundamentals', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7033-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 'c3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Beginner', 1, 0, 'React basics', datetime('now'), datetime('now')),

-- Frontend Developer (Mid)
('s1a2b3c4-e5f6-7040-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 'a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Intermediate', 1, 2, 'Strong JavaScript', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7041-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 'c3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Intermediate', 1, 2, 'React with hooks', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7042-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 'a4b5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Intermediate', 1, 1, 'TypeScript for type safety', datetime('now'), datetime('now')),

-- Senior Frontend Developer
('s1a2b3c4-e5f6-7050-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5006-9b0c-1d2e3f4a5b6c', 'a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Advanced', 1, 5, 'Expert JavaScript ES6+', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7051-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5006-9b0c-1d2e3f4a5b6c', 'c3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Advanced', 1, 4, 'Advanced React patterns', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7052-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5006-9b0c-1d2e3f4a5b6c', 'a4b5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Advanced', 1, 3, 'Expert TypeScript', datetime('now'), datetime('now'));

-- DevOps Engineer Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Junior DevOps
('s1a2b3c4-e5f6-7080-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5010-9b0c-1d2e3f4a5b6c', 'l1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'Version control basics', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7081-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5010-9b0c-1d2e3f4a5b6c', 'g4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Beginner', 1, 0, 'Docker basics', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7082-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5010-9b0c-1d2e3f4a5b6c', 'g6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Beginner', 1, 0, 'CI/CD concepts', datetime('now'), datetime('now')),

-- DevOps Engineer (Mid)
('s1a2b3c4-e5f6-7090-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'g4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Intermediate', 1, 2, 'Docker and containerization', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7091-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'g5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Intermediate', 1, 1, 'Kubernetes orchestration', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7092-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'g0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Intermediate', 1, 1, 'Terraform IaC', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7093-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'g7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Intermediate', 1, 1, 'Jenkins CI/CD', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7094-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'g1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'AWS cloud platform', datetime('now'), datetime('now'));

-- Data Analyst Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Junior Data Analyst
('s1a2b3c4-e5f6-7120-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5020-9b0c-1d2e3f4a5b6c', 'e1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'SQL fundamentals', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7121-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5020-9b0c-1d2e3f4a5b6c', 's5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Beginner', 1, 0, 'Excel for analysis', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7122-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5020-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'Python for data', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7123-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5020-9b0c-1d2e3f4a5b6c', 'j1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 0, 'Basic statistics', datetime('now'), datetime('now')),

-- Data Analyst (Mid)
('s1a2b3c4-e5f6-7130-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5021-9b0c-1d2e3f4a5b6c', 'e1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Advanced SQL', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7131-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5021-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Python for analysis', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7132-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5021-9b0c-1d2e3f4a5b6c', 'j5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Intermediate', 1, 1, 'Tableau visualization', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7133-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5021-9b0c-1d2e3f4a5b6c', 'j1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Statistical analysis', datetime('now'), datetime('now'));

-- QA Engineer Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- QA Engineer (Mid)
('s1a2b3c4-e5f6-7180-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5031-9b0c-1d2e3f4a5b6c', 'o6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Intermediate', 1, 2, 'Test automation', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7181-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5031-9b0c-1d2e3f4a5b6c', 'o7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Intermediate', 1, 1, 'Selenium WebDriver', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7182-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5031-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Beginner', 1, 1, 'Python for automation', datetime('now'), datetime('now'));

-- Management Positions Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Engineering Manager
('s1a2b3c4-e5f6-7300-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5050-9b0c-1d2e3f4a5b6c', 'm2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Advanced', 1, 3, 'Team leadership', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7301-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5050-9b0c-1d2e3f4a5b6c', 'm7a8b9c0-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Advanced', 1, 3, 'Project management', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7302-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5050-9b0c-1d2e3f4a5b6c', 'm8a9b0c1-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Advanced', 1, 3, 'Agile methodologies', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7303-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5050-9b0c-1d2e3f4a5b6c', 'm1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Advanced', 1, 3, 'Communication skills', datetime('now'), datetime('now')),

-- Product Manager
('s1a2b3c4-e5f6-7320-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5052-9b0c-1d2e3f4a5b6c', 'q2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Intermediate', 1, 3, 'Product management', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7321-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5052-9b0c-1d2e3f4a5b6c', 'm8a9b0c1-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Intermediate', 1, 2, 'Agile product development', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7322-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5052-9b0c-1d2e3f4a5b6c', 'm1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Stakeholder communication', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7323-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5052-9b0c-1d2e3f4a5b6c', 'p7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Intermediate', 1, 2, 'User research', datetime('now'), datetime('now'));

-- HR Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Recruiter
('s1a2b3c4-e5f6-7400-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5103-9b0c-1d2e3f4a5b6c', 'y1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Recruitment skills', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7401-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5103-9b0c-1d2e3f4a5b6c', 'y3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Intermediate', 1, 2, 'Interviewing skills', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7402-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5103-9b0c-1d2e3f4a5b6c', 'm1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Communication', datetime('now'), datetime('now'));

-- Finance Skills
INSERT OR IGNORE INTO popular_organization_skill (id, position_id, skill_id, level, is_mandatory, years_experience, notes, created_at, updated_at)
VALUES
-- Financial Analyst
('s1a2b3c4-e5f6-7500-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5201-9b0c-1d2e3f4a5b6c', 'r1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Intermediate', 1, 2, 'Financial analysis', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7501-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5201-9b0c-1d2e3f4a5b6c', 'r5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Intermediate', 1, 2, 'Budgeting', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7502-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5201-9b0c-1d2e3f4a5b6c', 's5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Advanced', 1, 3, 'Advanced Excel', datetime('now'), datetime('now')),

-- Accountant
('s1a2b3c4-e5f6-7510-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5203-9b0c-1d2e3f4a5b6c', 'r2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Intermediate', 1, 2, 'Accounting principles', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7511-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5203-9b0c-1d2e3f4a5b6c', 'r3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Intermediate', 1, 2, 'Bookkeeping', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-7512-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5203-9b0c-1d2e3f4a5b6c', 'r4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Intermediate', 1, 2, 'Financial reporting', datetime('now'), datetime('now'));
