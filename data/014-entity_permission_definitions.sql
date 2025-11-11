-- ============================================
-- DATA SEED: Entity Permission Definitions
-- Permission types and entity permission definitions for all entities
-- ============================================

-- First, insert permission types
INSERT OR IGNORE INTO enum_entity_permission_type (id, code, name, created_at, updated_at)
VALUES
('a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'REQUEST', 'Request Permission', datetime('now'), datetime('now')),
('b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'FEASIBLE_ANALYST', 'Feasibility Analysis Permission', datetime('now'), datetime('now')),
('c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'APPROVER', 'Approval Permission', datetime('now'), datetime('now')),
('d4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'DESIGNER', 'Design Permission', datetime('now'), datetime('now')),
('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'DEVELOPER', 'Development Permission', datetime('now'), datetime('now')),
('f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'TESTER', 'Testing Permission', datetime('now'), datetime('now')),
('a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'IMPLEMENTOR', 'Implementation Permission', datetime('now'), datetime('now')),
('b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'SUPPORTER', 'Support Permission', datetime('now'), datetime('now'));

-- Entity Permission Definitions for all entities
-- Note: Using first 10 positions from popular_organization_position table

-- CONTINENT entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('11111111-1111-1111-1111-111111111111', '8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('11111111-1111-1111-1111-111111111112', '8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('11111111-1111-1111-1111-111111111113', '8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('11111111-1111-1111-1111-111111111114', '8bfa24c6-9a3e-4f56-b2d7-cc93baf6e523', 'b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- COUNTRY entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('22222222-2222-2222-2222-222222222221', '2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('22222222-2222-2222-2222-222222222222', '2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('22222222-2222-2222-2222-222222222223', '2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('22222222-2222-2222-2222-222222222224', '2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('22222222-2222-2222-2222-222222222225', '2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0', 'b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- STATE entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('33333333-3333-3333-3333-333333333331', '9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('33333333-3333-3333-3333-333333333332', '9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('33333333-3333-3333-3333-333333333333', '9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('33333333-3333-3333-3333-333333333334', '9b5f0b8a-3a93-40e8-9c58-4f9b2b1d6a1c', 'b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- CITY entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('44444444-4444-4444-4444-444444444441', 'aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('44444444-4444-4444-4444-444444444442', 'aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('44444444-4444-4444-4444-444444444443', 'aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('44444444-4444-4444-4444-444444444444', 'aa6d1f77-2c4d-4ef3-8b63-22e2e9b2f4ad', 'b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POSTAL_ADDRESS entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('55555555-5555-5555-5555-555555555551', 'f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('55555555-5555-5555-5555-555555555552', 'f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('55555555-5555-5555-5555-555555555553', 'f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('55555555-5555-5555-5555-555555555554', 'f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e', 'b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- LANGUAGE entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('66666666-6666-6666-6666-666666666661', '0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('66666666-6666-6666-6666-666666666662', '0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('66666666-6666-6666-6666-666666666663', '0d2a8f01-5c8a-49d9-8b2e-6c5c1a2d2e3f', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- CURRENCY entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('77777777-7777-7777-7777-777777777771', '5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('77777777-7777-7777-7777-777777777772', '5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('77777777-7777-7777-7777-777777777773', '5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- TIMEZONE entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('88888888-8888-8888-8888-888888888881', 'd7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('88888888-8888-8888-8888-888888888882', 'd7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('88888888-8888-8888-8888-888888888883', 'd7a6b3c9-1f41-4a6a-bc3e-5a2d4c8b9f60', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- PERSON entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('99999999-9999-9999-9999-999999999991', '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('99999999-9999-9999-9999-999999999992', '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('99999999-9999-9999-9999-999999999993', '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('99999999-9999-9999-9999-999999999994', '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('99999999-9999-9999-9999-999999999995', '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3', 'b8c9d0e1-f2a3-44b5-6c7d-8e9f0a1b2c3d', 'p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- PERSON_CREDENTIAL entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', '7c1a8923-4585-4f73-9f64-fdfaa13b3b24', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaab1', '7c1a8923-4585-4f73-9f64-fdfaa13b3b24', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaab2', '7c1a8923-4585-4f73-9f64-fdfaa13b3b24', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- PERSON_EDUCATION entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbc1', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbc2', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- PERSON_EDUCATION_SUBJECT entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('cccccccc-cccc-cccc-cccc-cccccccccccc', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('cccccccc-cccc-cccc-cccc-ccccccccccd1', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('cccccccc-cccc-cccc-cccc-ccccccccccd2', 'ba480b24-d2a0-42b1-95f3-59f303b775b2', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- PERSON_SKILL entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('dddddddd-dddd-dddd-dddd-dddddddddddd', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('dddddddd-dddd-dddd-dddd-ddddddddde1', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('dddddddd-dddd-dddd-dddd-ddddddddde2', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_EDUCATION_SUBJECT entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee', 'e3d54a5a-292e-4b6f-9610-8fb85e43b442', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('eeeeeeee-eeee-eeee-eeee-eeeeeeeeeef1', 'e3d54a5a-292e-4b6f-9610-8fb85e43b442', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('eeeeeeee-eeee-eeee-eeee-eeeeeeeeeef2', 'e3d54a5a-292e-4b6f-9610-8fb85e43b442', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_INDUSTRY_CATEGORY entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('ffffffff-ffff-ffff-ffff-ffffffffffff', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('ffffffff-ffff-ffff-ffff-fffffffffff1', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('ffffffff-ffff-ffff-ffff-fffffffffff2', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_DEPARTMENTS entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('10101010-1010-1010-1010-101010101010', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('10101010-1010-1010-1010-101010101011', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('10101010-1010-1010-1010-101010101012', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('20202020-2020-2020-2020-202020202020', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('20202020-2020-2020-2020-202020202021', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('20202020-2020-2020-2020-202020202022', 'd4e5f6a7-b8c9-40d1-2e3f-4a5b6c7d8e9f', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_DESIGNATION entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('30303030-3030-3030-3030-303030303030', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('30303030-3030-3030-3030-303030303031', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('30303030-3030-3030-3030-303030303032', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_EDUCATION entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('40404040-4040-4040-4040-404040404040', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('40404040-4040-4040-4040-404040404041', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('40404040-4040-4040-4040-404040404042', 'a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_LEGAL_TYPES entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('50505050-5050-5050-5050-505050505050', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('50505050-5050-5050-5050-505050505051', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('50505050-5050-5050-5050-505050505052', 'b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_POSITION entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('60606060-6060-6060-6060-606060606060', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('60606060-6060-6060-6060-606060606061', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('60606060-6060-6060-6060-606060606062', 'f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_ORGANIZATION_SKILL entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('70707070-7070-7070-7070-707070707070', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('70707070-7070-7070-7070-707070707071', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('70707070-7070-7070-7070-707070707072', 'b3c4d5e6-f7a8-49b0-1c2d-3e4f5a6b7c8d', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- POPULAR_SKILL entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('80808080-8080-8080-8080-808080808080', 'f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('80808080-8080-8080-8080-808080808081', 'f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('80808080-8080-8080-8080-808080808082', 'f216b77b-7f54-4e7a-902f-7a6a0bceab7a', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- ENUM_EDUCATION_LEVELS entity permissions
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('90909090-9090-9090-9090-909090909090', 'b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('90909090-9090-9090-9090-909090909091', 'b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('90909090-9090-9090-9090-909090909092', 'b9f5e4a2-2a87-47d7-a7a0-81f7d3fbb5a2', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- ENUM_ENTITY_PERMISSION_TYPE entity permissions (meta-permissions for the permission system itself)
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('abababab-abab-abab-abab-abababababab', 'a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('abababab-abab-abab-abab-abababababa1', 'a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('abababab-abab-abab-abab-abababababa2', 'a9c2d4e6-f7b8-49a0-1c2d-3e4f5a6b7c8d', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));

-- ENTITY_PERMISSION_DEFINITION entity permissions (meta-permissions for the permission definition system itself)
INSERT OR IGNORE INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed, created_at, updated_at)
VALUES
('cdcdcdcd-cdcd-cdcd-cdcd-cdcdcdcdcdcd', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('cdcdcdcd-cdcd-cdcd-cdcd-cdcdcdcdcdc1', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now')),
('cdcdcdcd-cdcd-cdcd-cdcd-cdcdcdcdcdc2', 'b7c8d9e0-f1a2-43b3-5c4d-6e7f8a9b0c1d', 'e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 1, datetime('now'), datetime('now'));