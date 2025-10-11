-- =========================================================
-- DOMAIN: EDUCATION & SKILL
-- =========================================================

-- ENUM TABLE (for EDUCATION_LEVELS)
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'ENUM_EDUCATION_LEVELS', 'Education Levels Enumeration',
 'List of possible education levels for Person Education', 'EDUCATION', 'enum_education_levels');

-- ENUM_EDUCATION_LEVELS attributes
INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description)
VALUES
('7a4e2f86-cd9a-4b56-9d5f-1b67ea2f1181', 'b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'code', 'Code', 'text', 1, 0, 'Enum code'),
('c3f72b2d-35b2-46c2-9ac3-3d6c58fd43a5', 'b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'name', 'Name', 'text', 1, 1, 'Human-readable label');

-- =========================================================
-- POPULAR_EDUCATION_SUBJECT
-- =========================================================
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('e3d54a5a-292e-4b6f-9610-8fb85e43b442', 'POPULAR_EDUCATION_SUBJECT', 'Popular Education Subject',
 'Common subjects like Mathematics, Physics, etc.', 'EDUCATION', 'popular_education_subject');

INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description)
VALUES
('9c4b0b52-b6bb-4ff5-b9c2-c2db40f1d46b', 'e3d54a5a-292e-4b6f-9610-8fb85e43b442', 'name', 'Subject Name', 'text', 1, 1, 'Name of the education subject');

-- =========================================================
-- POPULAR_SKILL
-- =========================================================
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'POPULAR_SKILL', 'Popular Skill',
 'Common technical or soft skills like Python, Communication, etc.', 'SKILL', 'popular_skill');

INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description)
VALUES
('68acdd13-1845-40c4-9e4d-232208aef4c4', 'f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'name', 'Skill Name', 'text', 1, 1, 'Name of the skill');

-- =========================================================
-- PERSON_EDUCATION
-- =========================================================
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'PERSON_EDUCATION', 'Person Education',
 'Details of educational qualifications of a person', 'EDUCATION', 'person_education');

INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, description)
VALUES
('c8fbb7ea-7157-4986-bffb-d51b3d5185d8', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'person_id', 'Person', 'uuid', 1, 'Reference to PERSON'),
('1f7e5a6e-298c-4e4e-9f3f-8ffbc0f2308c', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'institution', 'Institution', 'text', 1, 'Name of the educational institution'),
('cf24488f-9a89-4d1f-9a36-78b27a342f31', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'start_date', 'Start Date', 'date', 0, 'Start date of course'),
('a22d3d66-bac2-4f02-b9fd-38c2b56ddf1f', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'complete_date', 'Completion Date', 'date', 0, 'Completion date'),
('530db6b9-27a2-489a-a9c2-58ad9f8ed3cb', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'education_level', 'Education Level', 'text', 1, 'Enum reference to EDUCATION_LEVELS');

-- =========================================================
-- PERSON_EDUCATION_SUBJECT
-- =========================================================
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('ba480b24-d2a0-42b1-95f3-59f303b775b2', 'PERSON_EDUCATION_SUBJECT', 'Person Education Subject',
 'Subjects studied in a particular education qualification', 'EDUCATION', 'person_education_subject');

INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, description)
VALUES
('8e4c59d0-4b6b-4d4b-9c0b-7b7e0a822a87', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'person_education_id', 'Person Education', 'uuid', 1, 'Reference to PERSON_EDUCATION'),
('3a5bff77-d4a8-4933-8828-95ff7e38f847', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'subject_id', 'Subject', 'uuid', 1, 'Reference to POPULAR_EDUCATION_SUBJECT'),
('ce86c991-93db-4418-977d-b3e2a7c9b5e1', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'marks_type', 'Marks Type', 'text', 0, 'Percentage, Grade, CGPA etc.'),
('d85b6a66-12d3-4766-96c0-2a911a9e7c03', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'marks', 'Marks', 'number', 0, 'Marks or grade achieved');

-- =========================================================
-- PERSON_SKILL
-- =========================================================
INSERT INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'PERSON_SKILL', 'Person Skill',
 'Skills attained by a person through education or training', 'SKILL', 'person_skill');

INSERT INTO entity_attribute (id, entity_id, code, name, data_type, is_required, description)
VALUES
('14ae6464-51ac-4a54-8894-71db8b3baf63', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'person_id', 'Person', 'uuid', 1, 'Reference to PERSON'),
('f84f88d3-2997-4f04-a8c7-3e9cfbfa33e2', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'subject_id', 'Skill', 'uuid', 1, 'Reference to POPULAR_SKILL'),
('6031ab7d-8ce3-4728-8c0b-3b2dc7618a2b', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'institution', 'Institution', 'text', 0, 'Training or certifying institution'),
('e48ac2a5-1b4c-45c4-b6a8-42e083478d6a', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'level', 'Skill Level', 'text', 0, 'Beginner / Intermediate / Expert'),
('c861e67e-c3de-49a3-8de3-6407b33c8f4c', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'start_date', 'Start Date', 'date', 0, 'When skill training started'),
('17a83c06-2e7b-4402-a9a5-786cba5b6d44', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'complete_date', 'Completion Date', 'date', 0, 'When skill training ended'),
('17b8b6f7-b94b-4f25-9bbf-10ed9ac57a52', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'marks_type', 'Marks Type', 'text', 0, 'Percentage / Grade'),
('7b7d9513-6c73-47b7-b36a-f45ef6a6888b', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'marks', 'Marks', 'number', 0, 'Score achieved');

-- =========================================================
-- RELATIONSHIPS
-- =========================================================
INSERT INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field)
VALUES
('bca27db9-46d1-4b4c-bb9c-38b6b9bde891', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'OneToMany', 'education_subjects', 'person_education_id'),
('bfa63ec2-89a1-4809-a00e-0c6a13b9839b', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'ManyToOne', 'skill', 'subject_id');

-- =========================================================
-- FUNCTIONS (CRUD + BUSINESS LOGIC)
-- =========================================================
INSERT INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type)
VALUES
('bf23620e-c5ac-4b12-a9f8-0282abebd421', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'calculate_duration', 'Calculate Study Duration',
 'Calculate study duration from start to completion date', '[{"name":"person_education_id","type":"uuid"}]', 'number'),
('3a286b43-227d-4e0a-9c1c-d0bb7cb0a52b', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'recommend_skill_upgrade', 'Recommend Skill Upgrade',
 'Suggest next level course or certification based on skill level', '[{"name":"person_id","type":"uuid"}]', 'json');

-- =========================================================
-- FUNCTION HANDLERS
-- =========================================================
INSERT INTO entity_function_handler (id, function_id, handler_type, handler_reference)
VALUES
('e8a203e3-51b1-4234-b8b8-fb4d1a6df92b', 'bf23620e-c5ac-4b12-a9f8-0282abebd421', 'sql', 'SELECT julianday(complete_date) - julianday(start_date) FROM person_education WHERE id = :person_education_id'),
('ab03c4c3-b482-4907-9ad3-ff4a71c788c9', '3a286b43-227d-4e0a-9c1c-d0bb7cb0a52b', 'api', 'https://api.skillsuggestor.ai/recommend');

-- =========================================================
-- VALIDATION RULES
-- =========================================================
INSERT INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('5d64a2de-93b4-4f51-b91f-8c84bcd52365', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'cf24488f-9a89-4d1f-9a36-78b27a342f31', 'date_check', 'complete_date >= start_date', 'Completion date cannot be before start date', 'error');


