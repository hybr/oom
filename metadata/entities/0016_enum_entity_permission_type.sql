-- =====================================================================
-- ENUM_ENTITY_PERMISSION_TYPE Entity Metadata - Permission type enumeration
-- Generated: 2025-11-08
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES ('e1p1t1y1-p111-4e11-r111-m111t111y111', 'ENUM_ENTITY_PERMISSION_TYPE', 'Entity Permission Type',
        'Permission type enumeration for position-based access control', 'PERMISSIONS', 'enum_entity_permission_type', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('e1p1t1y1-0001-0000-0000-000000000001', 'e1p1t1y1-p111-4e11-r111-m111t111y111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('e1p1t1y1-0002-0000-0000-000000000001', 'e1p1t1y1-p111-4e11-r111-m111t111y111', 'code', 'Code', 'text', 1, 1, 0, 1, NULL, 'Permission type code (e.g., REQUESTER, APPROVER, DEVELOPER)', 2),
('e1p1t1y1-0003-0000-0000-000000000001', 'e1p1t1y1-p111-4e11-r111-m111t111y111', 'name', 'Name', 'text', 1, 0, 0, 0, NULL, 'Display name', 3),
('e1p1t1y1-0004-0000-0000-000000000001', 'e1p1t1y1-p111-4e11-r111-m111t111y111', 'description', 'Description', 'text', 0, 0, 0, 0, NULL, 'Purpose and use case', 4);


INSERT INTO enum_entity_permission_type (id, code, name, description) VALUES
  (lower(hex(randomblob(16))), 'REQUESTER', 'Requester', 'Can create and initiate new requests'),
  (lower(hex(randomblob(16))), 'FEASIBLE_ANALYST', 'Feasibility Analyst', 'Can analyze technical and business feasibility, provide estimates'),
  (lower(hex(randomblob(16))), 'APPROVER', 'Approver', 'Can approve or reject requests and decisions'),
  (lower(hex(randomblob(16))), 'DESIGNER', 'Designer', 'Can create technical/visual designs and specifications'),
  (lower(hex(randomblob(16))), 'DEVELOPER', 'Developer', 'Can develop and implement software solutions'),
  (lower(hex(randomblob(16))), 'TESTER', 'Tester', 'Can test, validate quality, and report bugs'),
  (lower(hex(randomblob(16))), 'IMPLEMENTOR', 'Implementor', 'Can deploy, publish, and release to production'),
  (lower(hex(randomblob(16))), 'SUPPORTER', 'Supporter', 'Can provide ongoing support and maintenance'),
  (lower(hex(randomblob(16))), 'REVIEWER', 'Reviewer', 'Can view, monitor, and provide feedback'),
  (lower(hex(randomblob(16))), 'ADMINISTRATIVE', 'Administrator', 'Full administrative access to all features'),
  
  (lower(hex(randomblob(16))), 'CREATOR', 'Creator', 'Entity record creation'),
  (lower(hex(randomblob(16))), 'READER', 'Reader', 'Entity record read'),
  (lower(hex(randomblob(16))), 'UPDATOR', 'Updator', 'Entity record update'),
  (lower(hex(randomblob(16))), 'DELETOR', 'Deletor', 'Entity record delete');
