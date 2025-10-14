-- =========================================================
-- DOMAIN: ORGANIZATION - POSITIONS AND REQUIREMENTS
-- =========================================================

-- =========================================================
-- POPULAR_ORGANIZATION_DESIGNATION
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'POPULAR_ORGANIZATION_DESIGNATION', 'Popular Organization Designation',
 'Common job designation titles (e.g., Manager, Senior Engineer, VP)', 'ORGANIZATION', 'popular_organization_designation');

-- POPULAR_ORGANIZATION_DESIGNATION attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('a1a2a3a4-b5b6-47c8-9d0e-1f2a3b4c5d6e', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'code', 'Designation Code', 'text', 1, 0, 'Unique designation code', 1),
('b2b3b4b5-c6c7-48d9-0e1f-2a3b4c5d6e7f', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'name', 'Designation Name', 'text', 1, 1, 'Full name of the designation', 2),
('c3c4c5c6-d7d8-49e0-1f2a-3b4c5d6e7f8a', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'level', 'Seniority Level', 'text', 0, 0, 'Entry/Junior/Mid/Senior/Lead/Principal/Executive', 3),
('d4d5d6d7-e8e9-40f1-2a3b-4c5d6e7f8a9b', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'description', 'Description', 'text', 0, 0, 'Description of the designation', 4);

-- =========================================================
-- POPULAR_ORGANIZATION_POSITION
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'POPULAR_ORGANIZATION_POSITION', 'Popular Organization Position',
 'Specific positions within organizational structure with department, team, and designation details', 'ORGANIZATION', 'popular_organization_position');

-- POPULAR_ORGANIZATION_POSITION attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('e5e6e7e8-f9f0-41a2-3b4c-5d6e7f8a9b0c', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'position_name', 'Position Name', 'text', 1, 1, 'Specific name of the position', 1),
('f6f7f8f9-a0a1-42b3-4c5d-6e7f8a9b0c1d', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'department_id', 'Department', 'uuid', 1, 0, 'Reference to POPULAR_ORGANIZATION_DEPARTMENTS', 2),
('a7a8a9a0-b1b2-43c4-5d6e-7f8a9b0c1d2e', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'team_id', 'Team', 'uuid', 0, 0, 'Reference to POPULAR_ORGANIZATION_DEPARTMENT_TEAMS', 3),
('b8b9b0b1-c2c3-44d5-6e7f-8a9b0c1d2e3f', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'designation_id', 'Designation', 'uuid', 1, 0, 'Reference to POPULAR_ORGANIZATION_DESIGNATION', 4),
('c9c0c1c2-d3d4-45e6-7f8a-9b0c1d2e3f4a', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'code', 'Position Code', 'text', 1, 0, 'Unique position code', 5),
('d0d1d2d3-e4e5-46f7-8a9b-0c1d2e3f4a5b', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'description', 'Description', 'text', 0, 0, 'Detailed description of the position', 6),
('e1e2e3e4-f5f6-47a8-9b0c-1d2e3f4a5b6c', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'responsibilities', 'Key Responsibilities', 'text', 0, 0, 'Main responsibilities of the position', 7);

-- =========================================================
-- POPULAR_ORGANIZATION_EDUCATION
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'POPULAR_ORGANIZATION_EDUCATION', 'Popular Organization Education Requirements',
 'Educational requirements for specific positions', 'ORGANIZATION', 'popular_organization_education');

-- POPULAR_ORGANIZATION_EDUCATION attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('f2f3f4f5-a6a7-49b8-1c2d-3e4f5a6b7c8d', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'position_id', 'Position', 'uuid', 1, 0, 'Reference to POPULAR_ORGANIZATION_POSITION', 1),
('a3a4a5a6-b7b8-40c9-2d3e-4f5a6b7c8d9e', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'minimum_education_level', 'Minimum Education Level', 'uuid', 1, 0, 'Reference to ENUM_EDUCATION_LEVELS', 2),
('b4b5b6b7-c8c9-41d0-3e4f-5a6b7c8d9e0f', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'subject_id', 'Preferred Subject', 'uuid', 0, 0, 'Reference to POPULAR_EDUCATION_SUBJECT', 3),
('c5c6c7c8-d9d0-42e1-4f5a-6b7c8d9e0f1a', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'is_mandatory', 'Is Mandatory', 'boolean', 1, 0, 'Whether this education requirement is mandatory', 4),
('d6d7d8d9-e0e1-43f2-5a6b-7c8d9e0f1a2b', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'preferred', 'Preferred', 'boolean', 0, 0, 'Whether this is preferred but not required', 5),
('e7e8e9e0-f1f2-44a3-6b7c-8d9e0f1a2b3c', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'notes', 'Notes', 'text', 0, 0, 'Additional notes about education requirements', 6);

-- =========================================================
-- POPULAR_ORGANIZATION_SKILL
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'POPULAR_ORGANIZATION_SKILL', 'Popular Organization Skill Requirements',
 'Skill requirements for specific positions', 'ORGANIZATION', 'popular_organization_skill');

-- POPULAR_ORGANIZATION_SKILL attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('f8f9f0f1-a2a3-40b4-2d3e-4f5a6b7c8d9e', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'position_id', 'Position', 'uuid', 1, 0, 'Reference to POPULAR_ORGANIZATION_POSITION', 1),
('a9a0a1a2-b3b4-41c5-3e4f-5a6b7c8d9e0f', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'skill_id', 'Skill', 'uuid', 1, 0, 'Reference to POPULAR_SKILL', 2),
('b0b1b2b3-c4c5-42d6-4f5a-6b7c8d9e0f1a', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'level', 'Required Level', 'text', 0, 0, 'Beginner/Intermediate/Advanced/Expert', 3),
('c1c2c3c4-d5d6-43e7-5a6b-7c8d9e0f1a2b', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'is_mandatory', 'Is Mandatory', 'boolean', 1, 0, 'Whether this skill is mandatory', 4),
('d2d3d4d5-e6e7-44f8-6b7c-8d9e0f1a2b3c', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'years_experience', 'Years of Experience', 'integer', 0, 0, 'Minimum years of experience required', 5),
('e3e4e5e6-f7f8-45a9-7c8d-9e0f1a2b3c4d', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'notes', 'Notes', 'text', 0, 0, 'Additional notes about skill requirements', 6);

-- =========================================================
-- RELATIONSHIPS
-- =========================================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field)
VALUES
-- POPULAR_ORGANIZATION_POSITION to POPULAR_ORGANIZATION_DEPARTMENTS
('f6f7f8f9-a0a1-42b3-4c5d-6e7f8a9b0c1d-rel', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'ManyToOne', 'department', 'department_id'),

-- POPULAR_ORGANIZATION_POSITION to POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
('a7a8a9a0-b1b2-43c4-5d6e-7f8a9b0c1d2e-rel', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'ManyToOne', 'team', 'team_id'),

-- POPULAR_ORGANIZATION_POSITION to POPULAR_ORGANIZATION_DESIGNATION
('b8b9b0b1-c2c3-44d5-6e7f-8a9b0c1d2e3f-rel', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'ManyToOne', 'designation', 'designation_id'),

-- POPULAR_ORGANIZATION_EDUCATION to POPULAR_ORGANIZATION_POSITION
('f2f3f4f5-a6a7-49b8-1c2d-3e4f5a6b7c8d-rel', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'ManyToOne', 'position', 'position_id'),

-- POPULAR_ORGANIZATION_EDUCATION to ENUM_EDUCATION_LEVELS
('a3a4a5a6-b7b8-40c9-2d3e-4f5a6b7c8d9e-rel', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'ManyToOne', 'minimum_education_level', 'minimum_education_level'),

-- POPULAR_ORGANIZATION_EDUCATION to POPULAR_EDUCATION_SUBJECT
('b4b5b6b7-c8c9-41d0-3e4f-5a6b7c8d9e0f-rel', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'e3d54a5a-292e-4b6f-9610-8fb85e43b442', 'ManyToOne', 'subject', 'subject_id'),

-- POPULAR_ORGANIZATION_SKILL to POPULAR_ORGANIZATION_POSITION
('f8f9f0f1-a2a3-40b4-2d3e-4f5a6b7c8d9e-rel', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'ManyToOne', 'position', 'position_id'),

-- POPULAR_ORGANIZATION_SKILL to POPULAR_SKILL
('a9a0a1a2-b3b4-41c5-3e4f-5a6b7c8d9e0f-rel', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'ManyToOne', 'skill', 'skill_id'),

-- OneToMany reverse relationships
('f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b-education', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'OneToMany', 'education_requirements', 'position_id'),

('f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b-skills', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'OneToMany', 'skill_requirements', 'position_id'),

('c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e-positions', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'OneToMany', 'positions', 'department_id'),

('d4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f-positions', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'OneToMany', 'positions', 'team_id'),

('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a-positions', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'OneToMany', 'positions', 'designation_id');

-- =========================================================
-- FUNCTIONS (CRUD + BUSINESS LOGIC)
-- =========================================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type)
VALUES
('func-001-position', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'get_full_position_details', 'Get Full Position Details',
 'Get complete position information including department, team, designation, education and skill requirements', '[{"name":"position_id","type":"uuid"}]', 'json'),

('func-002-position', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'get_positions_by_department', 'Get Positions by Department',
 'Get all positions within a specific department', '[{"name":"department_id","type":"uuid"}]', 'json'),

('func-003-position', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'match_candidate_to_position', 'Match Candidate to Position',
 'Check if a candidate meets the education and skill requirements for a position', '[{"name":"position_id","type":"uuid"},{"name":"person_id","type":"uuid"}]', 'json'),

('func-004-designation', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'get_career_path', 'Get Career Path',
 'Get typical career progression from a given designation', '[{"name":"designation_id","type":"uuid"}]', 'json'),

('func-005-org-edu', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'get_education_requirements', 'Get Education Requirements for Position',
 'Get all education requirements for a specific position', '[{"name":"position_id","type":"uuid"}]', 'json'),

('func-006-org-skill', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'get_skill_requirements', 'Get Skill Requirements for Position',
 'Get all skill requirements for a specific position', '[{"name":"position_id","type":"uuid"}]', 'json');

-- =========================================================
-- FUNCTION HANDLERS
-- =========================================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference)
VALUES
('handler-001-position', 'func-001-position', 'sql',
 'SELECT p.*, d.name as department_name, t.name as team_name, des.name as designation_name FROM popular_organization_position p LEFT JOIN popular_organization_department d ON p.department_id = d.id LEFT JOIN popular_organization_department_team t ON p.team_id = t.id LEFT JOIN popular_organization_designation des ON p.designation_id = des.id WHERE p.id = :position_id'),

('handler-002-position', 'func-002-position', 'sql',
 'SELECT * FROM popular_organization_position WHERE department_id = :department_id ORDER BY position_name'),

('handler-003-position', 'func-003-position', 'api',
 'https://api.hrmatching.ai/match'),

('handler-004-designation', 'func-004-designation', 'sql',
 'SELECT * FROM popular_organization_designation WHERE level > (SELECT level FROM popular_organization_designation WHERE id = :designation_id) ORDER BY level'),

('handler-005-org-edu', 'func-005-org-edu', 'sql',
 'SELECT oe.*, el.name as education_level_name, es.name as subject_name FROM popular_organization_education oe LEFT JOIN enum_education_level el ON oe.minimum_education_level = el.id LEFT JOIN popular_education_subject es ON oe.subject_id = es.id WHERE oe.position_id = :position_id ORDER BY oe.is_mandatory DESC, oe.preferred DESC'),

('handler-006-org-skill', 'func-006-org-skill', 'sql',
 'SELECT os.*, s.name as skill_name FROM popular_organization_skill os LEFT JOIN popular_skill s ON os.skill_id = s.id WHERE os.position_id = :position_id ORDER BY os.is_mandatory DESC, os.years_experience DESC');

-- =========================================================
-- VALIDATION RULES
-- =========================================================
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('val-001-designation', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'a1a2a3a4-b5b6-47c8-9d0e-1f2a3b4c5d6e', 'unique_code', 'is_unique(code)', 'Designation code must be unique', 'error'),

('val-002-position', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'c9c0c1c2-d3d4-45e6-7f8a-9b0c1d2e3f4a', 'unique_code', 'is_unique(code)', 'Position code must be unique', 'error'),

('val-003-position', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'a7a8a9a0-b1b2-43c4-5d6e-7f8a9b0c1d2e', 'team_belongs_to_dept', 'team.department_id == department_id', 'Team must belong to the selected department', 'error'),

('val-004-org-skill', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'd2d3d4d5-e6e7-44f8-6b7c-8d9e0f1a2b3c', 'valid_experience', 'years_experience >= 0 AND years_experience <= 50', 'Years of experience must be between 0 and 50', 'error'),

('val-005-org-edu', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'c5c6c7c8-d9d0-42e1-4f5a-6b7c8d9e0f1a', 'mandatory_or_preferred', 'is_mandatory OR preferred', 'Education requirement must be either mandatory or preferred', 'warning');

