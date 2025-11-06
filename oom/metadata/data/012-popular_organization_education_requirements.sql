-- ============================================
-- DATA SEED: Popular Organization Education Requirements
-- Education requirements for various positions
-- ============================================

-- Junior Level Positions
INSERT OR IGNORE INTO popular_organization_education (id, position_id, minimum_education_level, subject_id, is_mandatory, preferred, notes, created_at, updated_at)
VALUES
('e1a2b3c4-e5f6-6001-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5001-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree in Computer Science or related field required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6002-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5004-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Web Development', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6003-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5010-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a6b7c8d9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 1, 0, 'Bachelor in Computer Science or Information Technology', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6004-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5020-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a8b9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 1, 0, 'Bachelor in Statistics or Mathematics required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6005-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5030-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree in Computer Science required', datetime('now'), datetime('now'));

-- Mid Level Positions
INSERT OR IGNORE INTO popular_organization_education (id, position_id, minimum_education_level, subject_id, is_mandatory, preferred, notes, created_at, updated_at)
VALUES
('e1a2b3c4-e5f6-6010-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5002-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree in Computer Science required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6011-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5005-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6012-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5007-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Software Engineering required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6013-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5011-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree in CS IT or Engineering required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6014-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5021-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a8b9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 1, 0, 'Bachelor in Statistics Mathematics or Economics', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6015-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5023-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Engineering required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6016-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5025-9b0c-1d2e3f4a5b6c', 'e7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'a9b0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 1, 0, 'Master or PhD in Data Science Statistics or Computer Science required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6017-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5031-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6018-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5040-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Cybersecurity required', datetime('now'), datetime('now'));

-- Senior Level Positions
INSERT OR IGNORE INTO popular_organization_education (id, position_id, minimum_education_level, subject_id, is_mandatory, preferred, notes, created_at, updated_at)
VALUES
('e1a2b3c4-e5f6-6020-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5003-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor required Master preferred', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6021-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5006-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6022-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5008-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor or Master degree required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6023-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5012-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree in CS IT or Engineering', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6024-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5022-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a8b9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 1, 0, 'Bachelor required Master preferred', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6025-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5024-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Engineering', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6026-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5032-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6027-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5041-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6028-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5013-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Engineering required', datetime('now'), datetime('now'));

-- Management Positions
INSERT OR IGNORE INTO popular_organization_education (id, position_id, minimum_education_level, subject_id, is_mandatory, preferred, notes, created_at, updated_at)
VALUES
('e1a2b3c4-e5f6-6030-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5050-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or Engineering required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6031-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5051-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor degree required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6032-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5052-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 1, 0, 'Bachelor in Computer Science Business or related field', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6033-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5054-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 1, 0, 'Bachelor in Computer Science or related technical field', datetime('now'), datetime('now'));

-- HR Positions
INSERT OR IGNORE INTO popular_organization_education (id, position_id, minimum_education_level, subject_id, is_mandatory, preferred, notes, created_at, updated_at)
VALUES
('e1a2b3c4-e5f6-6040-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5100-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 1, 0, 'Bachelor in Human Resources or Business Administration', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6041-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5101-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 1, 0, 'Bachelor in Human Resources or Business required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6042-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5102-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 1, 0, 'Bachelor required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6043-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5103-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 1, 0, 'Bachelor in HR Business or Psychology', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6044-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5104-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 1, 0, 'Bachelor degree required', datetime('now'), datetime('now'));

-- Finance Positions
INSERT OR IGNORE INTO popular_organization_education (id, position_id, minimum_education_level, subject_id, is_mandatory, preferred, notes, created_at, updated_at)
VALUES
('e1a2b3c4-e5f6-6050-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5200-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 1, 0, 'Bachelor in Finance Accounting or Economics', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6051-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5201-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 1, 0, 'Bachelor in Finance or Accounting required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6052-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5202-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 1, 0, 'Bachelor required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6053-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5203-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1, 0, 'Bachelor in Accounting required', datetime('now'), datetime('now')),
('e1a2b3c4-e5f6-6054-9b0c-1d2e3f4a5b6c', 'p1a2b3c4-e5f6-5204-9b0c-1d2e3f4a5b6c', 'e6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'd3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 1, 0, 'Bachelor in Finance or Accounting required', datetime('now'), datetime('now'));
