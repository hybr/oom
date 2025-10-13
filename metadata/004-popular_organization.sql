-- =========================================================
-- DOMAIN: ORGANIZATION - POPULAR ENTITIES
-- =========================================================

-- =========================================================
-- POPULAR_INDUSTRY_CATEGORY (Hierarchical)
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'POPULAR_INDUSTRY_CATEGORY', 'Popular Industry Category',
 'Hierarchical classification of industries (e.g., Technology > Software > SaaS)', 'ORGANIZATION', 'popular_industry_category');

-- POPULAR_INDUSTRY_CATEGORY attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'code', 'Category Code', 'text', 1, 0, 'Unique category code', 1),
('c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'name', 'Category Name', 'text', 1, 1, 'Display name of the industry category', 2),
('d4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'parent_category_id', 'Parent Category', 'uuid', 0, 0, 'Parent category for hierarchical structure', 3),
('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'level', 'Hierarchy Level', 'integer', 0, 0, 'Level in hierarchy (1=top level)', 4),
('f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'description', 'Description', 'text', 0, 0, 'Detailed description of the category', 5);

-- =========================================================
-- POPULAR_ORGANIZATION_LEGAL_TYPES
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'POPULAR_ORGANIZATION_LEGAL_TYPES', 'Popular Organization Legal Types',
 'Legal entity types based on country (e.g., LLC, Private Limited, GmbH)', 'ORGANIZATION', 'popular_organization_legal_type');

-- POPULAR_ORGANIZATION_LEGAL_TYPES attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'code', 'Type Code', 'text', 1, 0, 'Abbreviated legal type code (e.g., LLC, Ltd)', 1),
('b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'name', 'Type Name', 'text', 1, 1, 'Full name of the legal type', 2),
('c9d0e1f2-a3b4-45c6-7d8e-9f0a1b2c3d4e', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'country_id', 'Country', 'uuid', 1, 0, 'Country where this legal type is applicable', 3),
('d0e1f2a3-b4c5-46d7-8e9f-0a1b2c3d4e5f', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'description', 'Description', 'text', 0, 0, 'Description of the legal entity type', 4),
('e1f2a3b4-c5d6-47e8-9f0a-1b2c3d4e5f6a', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'requires_minimum_capital', 'Requires Minimum Capital', 'boolean', 0, 0, 'Whether minimum capital is required', 5),
('f2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'minimum_capital_amount', 'Minimum Capital Amount', 'number', 0, 0, 'Minimum required capital if applicable', 6);

-- =========================================================
-- POPULAR_ORGANIZATION_DEPARTMENTS
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'POPULAR_ORGANIZATION_DEPARTMENTS', 'Popular Organization Departments',
 'Common organizational departments (e.g., HR, IT, Finance)', 'ORGANIZATION', 'popular_organization_department');

-- POPULAR_ORGANIZATION_DEPARTMENTS attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'code', 'Department Code', 'text', 1, 0, 'Unique department code (e.g., HR, IT)', 1),
('b4c5d6e7-f8a9-40b1-2c3d-4e5f6a7b8c9d', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'name', 'Department Name', 'text', 1, 1, 'Full name of the department', 2),
('c5d6e7f8-a9b0-41c2-3d4e-5f6a7b8c9d0e', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'description', 'Description', 'text', 0, 0, 'Description of department functions', 3),
('d6e7f8a9-b0c1-42d3-4e5f-6a7b8c9d0e1f', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'parent_department_id', 'Parent Department', 'uuid', 0, 0, 'Parent department if hierarchical', 4),
('e7f8a9b0-c1d2-43e4-5f6a-7b8c9d0e1f2a', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'typical_responsibilities', 'Typical Responsibilities', 'text', 0, 0, 'Common responsibilities of this department', 5);

-- =========================================================
-- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
-- =========================================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES
('d4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'POPULAR_ORGANIZATION_DEPARTMENT_TEAMS', 'Popular Organization Department Teams',
 'Common teams within departments (e.g., Recruitment under HR, DevOps under IT)', 'ORGANIZATION', 'popular_organization_department_team');

-- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('f8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'code', 'Team Code', 'text', 1, 0, 'Unique team code', 1),
('a9b0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'name', 'Team Name', 'text', 1, 1, 'Full name of the team', 2),
('b0c1d2e3-f4a5-46b7-8c9d-0e1f2a3b4c5d', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'department_id', 'Department', 'uuid', 1, 0, 'Parent department this team belongs to', 3),
('c1d2e3f4-a5b6-47c8-9d0e-1f2a3b4c5d6e', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'description', 'Description', 'text', 0, 0, 'Description of team functions', 4),
('d2e3f4a5-b6c7-48d9-0e1f-2a3b4c5d6e7f', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'typical_roles', 'Typical Roles', 'text', 0, 0, 'Common roles within this team', 5);

-- =========================================================
-- RELATIONSHIPS
-- =========================================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field)
VALUES
-- POPULAR_INDUSTRY_CATEGORY self-referencing (hierarchical)
('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a-rel', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'ManyToOne', 'parent_category', 'parent_category_id'),

-- POPULAR_ORGANIZATION_LEGAL_TYPES to COUNTRY
('c9d0e1f2-a3b4-45c6-7d8e-9f0a1b2c3d4e-rel', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', '2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'ManyToOne', 'country', 'country_id'),

-- POPULAR_ORGANIZATION_DEPARTMENTS self-referencing (hierarchical)
('d6e7f8a9-b0c1-42d3-4e5f-6a7b8c9d0e1f-rel', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'ManyToOne', 'parent_department', 'parent_department_id'),

-- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS to POPULAR_ORGANIZATION_DEPARTMENTS
('b0c1d2e3-f4a5-46b7-8c9d-0e1f2a3b4c5d-rel', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'ManyToOne', 'department', 'department_id'),

-- OneToMany reverse relationships
('a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c-children', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'OneToMany', 'subcategories', 'parent_category_id'),
('c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e-children', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'OneToMany', 'subdepartments', 'parent_department_id'),
('c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e-teams', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'OneToMany', 'teams', 'department_id');

-- =========================================================
-- FUNCTIONS (CRUD + BUSINESS LOGIC)
-- =========================================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type)
VALUES
('func-001-industry-category', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'get_full_path', 'Get Full Category Path',
 'Get full hierarchical path of category (e.g., Technology > Software > SaaS)', '[{"name":"category_id","type":"uuid"}]', 'text'),
('func-002-industry-category', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'get_root_categories', 'Get Root Categories',
 'Get all top-level categories (level 1)', '[]', 'json'),
('func-003-legal-types', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'get_by_country', 'Get Legal Types by Country',
 'Get all legal types available in a specific country', '[{"name":"country_id","type":"uuid"}]', 'json'),
('func-004-departments', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'get_org_structure', 'Get Organization Structure',
 'Get hierarchical structure of all departments', '[]', 'json');

-- =========================================================
-- FUNCTION HANDLERS
-- =========================================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference)
VALUES
('handler-001-industry', 'func-001-industry-category', 'sql', 'WITH RECURSIVE path_cte AS (SELECT id, name, parent_category_id, name as path FROM popular_industry_category WHERE id = :category_id UNION ALL SELECT p.id, p.name, p.parent_category_id, p.name || '' > '' || c.path FROM popular_industry_category p INNER JOIN path_cte c ON c.parent_category_id = p.id) SELECT path FROM path_cte WHERE parent_category_id IS NULL'),
('handler-002-industry', 'func-002-industry-category', 'sql', 'SELECT * FROM popular_industry_category WHERE parent_category_id IS NULL OR level = 1 ORDER BY name'),
('handler-003-legal', 'func-003-legal-types', 'sql', 'SELECT * FROM popular_organization_legal_type WHERE country_id = :country_id ORDER BY name'),
('handler-004-dept', 'func-004-departments', 'sql', 'WITH RECURSIVE dept_tree AS (SELECT id, code, name, parent_department_id, 0 as level FROM popular_organization_department WHERE parent_department_id IS NULL UNION ALL SELECT d.id, d.code, d.name, d.parent_department_id, dt.level + 1 FROM popular_organization_department d INNER JOIN dept_tree dt ON d.parent_department_id = dt.id) SELECT * FROM dept_tree ORDER BY level, name');

-- =========================================================
-- VALIDATION RULES
-- =========================================================
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('val-001-industry', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'unique_code', 'is_unique(code)', 'Category code must be unique', 'error'),
('val-002-legal', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'unique_code_per_country', 'is_unique(code, country_id)', 'Legal type code must be unique per country', 'error'),
('val-003-dept', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'unique_code', 'is_unique(code)', 'Department code must be unique', 'error'),
('val-004-team', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'f8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'unique_code', 'is_unique(code)', 'Team code must be unique', 'error');


