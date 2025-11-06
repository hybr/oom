-- ============================================
-- DATA SEED: Popular Organization Positions
-- Specific positions within organizational structure
-- ============================================

-- Software Development Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 'Junior Backend Developer', 'dept-132-backend', 'team-eng-003', 'd1a2b3c4-e5f6-4011-8b0c-1d2e3f4a5b6c', 'IT-DEV-JR-BE-001', 'Entry-level backend development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 'Backend Developer', 'dept-132-backend', 'team-eng-003', 'd1a2b3c4-e5f6-4021-8b0c-1d2e3f4a5b6c', 'IT-DEV-BE-001', 'Mid-level backend development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 'Senior Backend Developer', 'dept-132-backend', 'team-eng-003', 'd1a2b3c4-e5f6-4031-8b0c-1d2e3f4a5b6c', 'IT-DEV-SR-BE-001', 'Senior backend development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 'Junior Frontend Developer', 'dept-131-frontend', 'team-eng-001', 'd1a2b3c4-e5f6-4011-8b0c-1d2e3f4a5b6c', 'IT-DEV-JR-FE-001', 'Entry-level frontend development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 'Frontend Developer', 'dept-131-frontend', 'team-eng-001', 'd1a2b3c4-e5f6-4021-8b0c-1d2e3f4a5b6c', 'IT-DEV-FE-001', 'Mid-level frontend development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5006-9b0c-1d2e3f4a5b6c', 'Senior Frontend Developer', 'dept-131-frontend', 'team-eng-001', 'd1a2b3c4-e5f6-4031-8b0c-1d2e3f4a5b6c', 'IT-DEV-SR-FE-001', 'Senior frontend development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5007-9b0c-1d2e3f4a5b6c', 'Full Stack Developer', 'dept-009-eng', NULL, 'd1a2b3c4-e5f6-4021-8b0c-1d2e3f4a5b6c', 'IT-DEV-FS-001', 'Mid-level full stack development position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5008-9b0c-1d2e3f4a5b6c', 'Senior Full Stack Developer', 'dept-009-eng', NULL, 'd1a2b3c4-e5f6-4031-8b0c-1d2e3f4a5b6c', 'IT-DEV-SR-FS-001', 'Senior full stack development position', datetime('now'), datetime('now'));

-- DevOps / Infrastructure Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5010-9b0c-1d2e3f4a5b6c', 'Junior DevOps Engineer', 'dept-133-devops', 'team-eng-005', 'd1a2b3c4-e5f6-4010-8b0c-1d2e3f4a5b6c', 'IT-DEVOPS-JR-001', 'Entry-level DevOps position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'DevOps Engineer', 'dept-133-devops', 'team-eng-005', 'd1a2b3c4-e5f6-4020-8b0c-1d2e3f4a5b6c', 'IT-DEVOPS-001', 'Mid-level DevOps position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5012-9b0c-1d2e3f4a5b6c', 'Senior DevOps Engineer', 'dept-133-devops', 'team-eng-005', 'd1a2b3c4-e5f6-4030-8b0c-1d2e3f4a5b6c', 'IT-DEVOPS-SR-001', 'Senior DevOps position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5013-9b0c-1d2e3f4a5b6c', 'Cloud Architect', 'dept-121-infra', 'team-it-002', 'd1a2b3c4-e5f6-4070-8b0c-1d2e3f4a5b6c', 'IT-CLOUD-ARCH-001', 'Cloud architecture specialist', datetime('now'), datetime('now'));

-- Data / Analytics Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5020-9b0c-1d2e3f4a5b6c', 'Junior Data Analyst', 'dept-135-data', NULL, 'd1a2b3c4-e5f6-4012-8b0c-1d2e3f4a5b6c', 'IT-DATA-JR-ANALYST-001', 'Entry-level data analysis position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5021-9b0c-1d2e3f4a5b6c', 'Data Analyst', 'dept-135-data', NULL, 'd1a2b3c4-e5f6-4022-8b0c-1d2e3f4a5b6c', 'IT-DATA-ANALYST-001', 'Mid-level data analysis position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5022-9b0c-1d2e3f4a5b6c', 'Senior Data Analyst', 'dept-135-data', NULL, 'd1a2b3c4-e5f6-4032-8b0c-1d2e3f4a5b6c', 'IT-DATA-SR-ANALYST-001', 'Senior data analysis position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5023-9b0c-1d2e3f4a5b6c', 'Data Engineer', 'dept-135-data', 'team-eng-008', 'd1a2b3c4-e5f6-4020-8b0c-1d2e3f4a5b6c', 'IT-DATA-ENG-001', 'Mid-level data engineering position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5024-9b0c-1d2e3f4a5b6c', 'Senior Data Engineer', 'dept-135-data', 'team-eng-008', 'd1a2b3c4-e5f6-4030-8b0c-1d2e3f4a5b6c', 'IT-DATA-SR-ENG-001', 'Senior data engineering position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5025-9b0c-1d2e3f4a5b6c', 'Data Scientist', 'dept-135-data', NULL, 'd1a2b3c4-e5f6-4025-8b0c-1d2e3f4a5b6c', 'IT-DATA-SCIENTIST-001', 'Data science specialist position', datetime('now'), datetime('now'));

-- QA / Testing Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5030-9b0c-1d2e3f4a5b6c', 'Junior QA Engineer', 'dept-014-quality', 'team-qa-001', 'd1a2b3c4-e5f6-4010-8b0c-1d2e3f4a5b6c', 'IT-QA-JR-001', 'Entry-level QA position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5031-9b0c-1d2e3f4a5b6c', 'QA Engineer', 'dept-014-quality', 'team-qa-002', 'd1a2b3c4-e5f6-4020-8b0c-1d2e3f4a5b6c', 'IT-QA-001', 'Mid-level QA position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5032-9b0c-1d2e3f4a5b6c', 'Senior QA Engineer', 'dept-014-quality', 'team-qa-002', 'd1a2b3c4-e5f6-4030-8b0c-1d2e3f4a5b6c', 'IT-QA-SR-001', 'Senior QA position', datetime('now'), datetime('now'));

-- Security Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5040-9b0c-1d2e3f4a5b6c', 'Security Engineer', 'dept-122-security', 'team-it-003', 'd1a2b3c4-e5f6-4020-8b0c-1d2e3f4a5b6c', 'IT-SEC-001', 'Mid-level security position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5041-9b0c-1d2e3f4a5b6c', 'Senior Security Engineer', 'dept-122-security', 'team-it-003', 'd1a2b3c4-e5f6-4030-8b0c-1d2e3f4a5b6c', 'IT-SEC-SR-001', 'Senior security position', datetime('now'), datetime('now'));

-- IT Management Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5050-9b0c-1d2e3f4a5b6c', 'Engineering Manager', 'dept-009-eng', NULL, 'd1a2b3c4-e5f6-4036-8b0c-1d2e3f4a5b6c', 'IT-MGR-ENG-001', 'Engineering team management position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5051-9b0c-1d2e3f4a5b6c', 'Technical Lead', 'dept-009-eng', NULL, 'd1a2b3c4-e5f6-4041-8b0c-1d2e3f4a5b6c', 'IT-LEAD-TECH-001', 'Technical leadership position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5052-9b0c-1d2e3f4a5b6c', 'Product Manager', 'dept-008-product', 'team-prod-001', 'd1a2b3c4-e5f6-4074-8b0c-1d2e3f4a5b6c', 'IT-PM-001', 'Product management position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5053-9b0c-1d2e3f4a5b6c', 'Senior Product Manager', 'dept-008-product', 'team-prod-001', 'd1a2b3c4-e5f6-4075-8b0c-1d2e3f4a5b6c', 'IT-PM-SR-001', 'Senior product management position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5054-9b0c-1d2e3f4a5b6c', 'IT Director', 'dept-004-it', NULL, 'd1a2b3c4-e5f6-4053-8b0c-1d2e3f4a5b6c', 'IT-DIR-001', 'IT department director position', datetime('now'), datetime('now'));

-- HR Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5100-9b0c-1d2e3f4a5b6c', 'HR Coordinator', 'dept-104-hr-ops', NULL, 'd1a2b3c4-e5f6-4026-8b0c-1d2e3f4a5b6c', 'HR-COORD-001', 'HR coordination position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5101-9b0c-1d2e3f4a5b6c', 'HR Specialist', 'dept-104-hr-ops', NULL, 'd1a2b3c4-e5f6-4025-8b0c-1d2e3f4a5b6c', 'HR-SPEC-001', 'HR specialist position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5102-9b0c-1d2e3f4a5b6c', 'Senior HR Manager', 'dept-002-hr', NULL, 'd1a2b3c4-e5f6-4044-8b0c-1d2e3f4a5b6c', 'HR-MGR-SR-001', 'Senior HR management position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5103-9b0c-1d2e3f4a5b6c', 'Recruiter', 'dept-101-recruit', 'team-hr-001', 'd1a2b3c4-e5f6-4025-8b0c-1d2e3f4a5b6c', 'HR-REC-001', 'Recruitment specialist position', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5104-9b0c-1d2e3f4a5b6c', 'Senior Recruiter', 'dept-101-recruit', 'team-hr-001', 'd1a2b3c4-e5f6-4035-8b0c-1d2e3f4a5b6c', 'HR-REC-SR-001', 'Senior recruitment position', datetime('now'), datetime('now'));

-- Finance Positions
INSERT OR IGNORE INTO popular_organization_position (id, position_name, department_id, team_id, designation_id, code, description, created_at, updated_at)
VALUES
('p1a2b3c4-e5f6-5200-9b0c-1d2e3f4a5b6c', 'Junior Financial Analyst', 'dept-112-fp-a', 'team-fin-004', 'd1a2b3c4-e5f6-4012-8b0c-1d2e3f4a5b6c', 'FIN-ANALYST-JR-001', 'Entry-level financial analyst', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5201-9b0c-1d2e3f4a5b6c', 'Financial Analyst', 'dept-112-fp-a', 'team-fin-004', 'd1a2b3c4-e5f6-4022-8b0c-1d2e3f4a5b6c', 'FIN-ANALYST-001', 'Mid-level financial analyst', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5202-9b0c-1d2e3f4a5b6c', 'Senior Financial Analyst', 'dept-112-fp-a', 'team-fin-005', 'd1a2b3c4-e5f6-4032-8b0c-1d2e3f4a5b6c', 'FIN-ANALYST-SR-001', 'Senior financial analyst', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5203-9b0c-1d2e3f4a5b6c', 'Accountant', 'dept-111-accounting', 'team-fin-003', 'd1a2b3c4-e5f6-4025-8b0c-1d2e3f4a5b6c', 'FIN-ACCT-001', 'Accounting specialist', datetime('now'), datetime('now')),
('p1a2b3c4-e5f6-5204-9b0c-1d2e3f4a5b6c', 'Finance Manager', 'dept-003-finance', NULL, 'd1a2b3c4-e5f6-4036-8b0c-1d2e3f4a5b6c', 'FIN-MGR-001', 'Finance management position', datetime('now'), datetime('now'));
