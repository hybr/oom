-- =========================================================
-- DATA POPULATION: POPULAR_ORGANIZATION_DESIGNATION
-- =========================================================

-- Entry Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4001-8b0c-1d2e3f4a5b6c', 'INTERN', 'Intern', 'Entry', 'Temporary position for students or recent graduates gaining work experience', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4002-8b0c-1d2e3f4a5b6c', 'TRAINEE', 'Trainee', 'Entry', 'Entry-level position with structured training program', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4003-8b0c-1d2e3f4a5b6c', 'JR_ASSOC', 'Junior Associate', 'Entry', 'Entry-level associate position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4004-8b0c-1d2e3f4a5b6c', 'ASSOC', 'Associate', 'Entry', 'Entry-level professional position', datetime('now'), datetime('now'));

-- Junior Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4010-8b0c-1d2e3f4a5b6c', 'JR_ENG', 'Junior Engineer', 'Junior', 'Junior-level engineering position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4011-8b0c-1d2e3f4a5b6c', 'JR_DEV', 'Junior Developer', 'Junior', 'Junior-level software development position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4012-8b0c-1d2e3f4a5b6c', 'JR_ANALYST', 'Junior Analyst', 'Junior', 'Junior-level analyst position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4013-8b0c-1d2e3f4a5b6c', 'JR_DESIGNER', 'Junior Designer', 'Junior', 'Junior-level design position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4014-8b0c-1d2e3f4a5b6c', 'JR_CONSULTANT', 'Junior Consultant', 'Junior', 'Junior-level consulting position', datetime('now'), datetime('now'));

-- Mid Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4020-8b0c-1d2e3f4a5b6c', 'ENG', 'Engineer', 'Mid', 'Mid-level engineering position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4021-8b0c-1d2e3f4a5b6c', 'DEV', 'Developer', 'Mid', 'Mid-level software development position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4022-8b0c-1d2e3f4a5b6c', 'ANALYST', 'Analyst', 'Mid', 'Mid-level analyst position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4023-8b0c-1d2e3f4a5b6c', 'DESIGNER', 'Designer', 'Mid', 'Mid-level design position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4024-8b0c-1d2e3f4a5b6c', 'CONSULTANT', 'Consultant', 'Mid', 'Mid-level consulting position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4025-8b0c-1d2e3f4a5b6c', 'SPECIALIST', 'Specialist', 'Mid', 'Subject matter specialist position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4026-8b0c-1d2e3f4a5b6c', 'COORDINATOR', 'Coordinator', 'Mid', 'Project or team coordination position', datetime('now'), datetime('now'));

-- Senior Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4030-8b0c-1d2e3f4a5b6c', 'SR_ENG', 'Senior Engineer', 'Senior', 'Senior-level engineering position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4031-8b0c-1d2e3f4a5b6c', 'SR_DEV', 'Senior Developer', 'Senior', 'Senior-level software development position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4032-8b0c-1d2e3f4a5b6c', 'SR_ANALYST', 'Senior Analyst', 'Senior', 'Senior-level analyst position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4033-8b0c-1d2e3f4a5b6c', 'SR_DESIGNER', 'Senior Designer', 'Senior', 'Senior-level design position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4034-8b0c-1d2e3f4a5b6c', 'SR_CONSULTANT', 'Senior Consultant', 'Senior', 'Senior-level consulting position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4035-8b0c-1d2e3f4a5b6c', 'SR_SPECIALIST', 'Senior Specialist', 'Senior', 'Senior subject matter specialist', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4036-8b0c-1d2e3f4a5b6c', 'MANAGER', 'Manager', 'Senior', 'Team or functional area manager', datetime('now'), datetime('now'));

-- Lead Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4040-8b0c-1d2e3f4a5b6c', 'LEAD_ENG', 'Lead Engineer', 'Lead', 'Technical lead for engineering team', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4041-8b0c-1d2e3f4a5b6c', 'LEAD_DEV', 'Lead Developer', 'Lead', 'Technical lead for development team', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4042-8b0c-1d2e3f4a5b6c', 'LEAD_ANALYST', 'Lead Analyst', 'Lead', 'Lead analyst position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4043-8b0c-1d2e3f4a5b6c', 'TEAM_LEAD', 'Team Lead', 'Lead', 'General team leadership position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4044-8b0c-1d2e3f4a5b6c', 'SR_MANAGER', 'Senior Manager', 'Lead', 'Senior management position overseeing multiple teams', datetime('now'), datetime('now'));

-- Principal Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4050-8b0c-1d2e3f4a5b6c', 'PRINCIPAL_ENG', 'Principal Engineer', 'Principal', 'Principal-level engineering position with broad technical influence', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4051-8b0c-1d2e3f4a5b6c', 'PRINCIPAL_ARCH', 'Principal Architect', 'Principal', 'Principal-level architecture position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4052-8b0c-1d2e3f4a5b6c', 'PRINCIPAL_CONSULTANT', 'Principal Consultant', 'Principal', 'Principal-level consulting position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4053-8b0c-1d2e3f4a5b6c', 'DIRECTOR', 'Director', 'Principal', 'Director-level management position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4054-8b0c-1d2e3f4a5b6c', 'SR_DIRECTOR', 'Senior Director', 'Principal', 'Senior director-level management position', datetime('now'), datetime('now'));

-- Executive Level Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4060-8b0c-1d2e3f4a5b6c', 'VP', 'Vice President', 'Executive', 'Vice president executive position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4061-8b0c-1d2e3f4a5b6c', 'SVP', 'Senior Vice President', 'Executive', 'Senior vice president executive position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4062-8b0c-1d2e3f4a5b6c', 'CTO', 'Chief Technology Officer', 'Executive', 'Chief technology officer - executive leadership', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4063-8b0c-1d2e3f4a5b6c', 'CIO', 'Chief Information Officer', 'Executive', 'Chief information officer - executive leadership', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4064-8b0c-1d2e3f4a5b6c', 'CFO', 'Chief Financial Officer', 'Executive', 'Chief financial officer - executive leadership', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4065-8b0c-1d2e3f4a5b6c', 'COO', 'Chief Operating Officer', 'Executive', 'Chief operating officer - executive leadership', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4066-8b0c-1d2e3f4a5b6c', 'CHRO', 'Chief Human Resources Officer', 'Executive', 'Chief human resources officer - executive leadership', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4067-8b0c-1d2e3f4a5b6c', 'CMO', 'Chief Marketing Officer', 'Executive', 'Chief marketing officer - executive leadership', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4068-8b0c-1d2e3f4a5b6c', 'CEO', 'Chief Executive Officer', 'Executive', 'Chief executive officer - top executive position', datetime('now'), datetime('now'));

-- Specialized Designations
INSERT OR IGNORE INTO popular_organization_designation (id, code, name, level, description, created_at, updated_at)
VALUES
('d1a2b3c4-e5f6-4070-8b0c-1d2e3f4a5b6c', 'ARCHITECT', 'Architect', 'Senior', 'Architecture specialist position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4071-8b0c-1d2e3f4a5b6c', 'SR_ARCHITECT', 'Senior Architect', 'Lead', 'Senior architecture specialist', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4072-8b0c-1d2e3f4a5b6c', 'SCRUM_MASTER', 'Scrum Master', 'Mid', 'Agile team facilitator', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4073-8b0c-1d2e3f4a5b6c', 'PRODUCT_OWNER', 'Product Owner', 'Senior', 'Product backlog and stakeholder management', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4074-8b0c-1d2e3f4a5b6c', 'PRODUCT_MANAGER', 'Product Manager', 'Senior', 'Product strategy and management', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4075-8b0c-1d2e3f4a5b6c', 'SR_PRODUCT_MANAGER', 'Senior Product Manager', 'Lead', 'Senior product management position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4076-8b0c-1d2e3f4a5b6c', 'PROJ_MANAGER', 'Project Manager', 'Senior', 'Project management and delivery', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4077-8b0c-1d2e3f4a5b6c', 'SR_PROJ_MANAGER', 'Senior Project Manager', 'Lead', 'Senior project management position', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-4078-8b0c-1d2e3f4a5b6c', 'PROGRAM_MANAGER', 'Program Manager', 'Lead', 'Multi-project program management', datetime('now'), datetime('now'));
