-- =====================================================================
-- ORGANIZATION CREATION WORKFLOW - Create and setup new organizations
-- From initial application to full activation
-- Generated: 2025-11-12
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO process_graph (
    id, code, name, description, entity_id, version_number,
    is_active, is_published, category, created_at
) VALUES (
    'ORG00000-CREA-4111-W111-000000000001',
    'ORGANIZATION_CREATION_WORKFLOW',
    'Organization Creation & Setup Process',
    'Complete workflow for creating and activating new organizations',
    'o1r1g1a1-n1z1-4t1n-a111-111111111111',  -- ORGANIZATION entity
    1, 1, 1, 'ORGANIZATION_MANAGEMENT', datetime('now')
);

-- Nodes (compact format)
INSERT OR IGNORE INTO process_node (id, graph_id, node_code, node_name, node_type, description, position_id, permission_type_id, sla_hours, display_x, display_y, created_at) VALUES
('ORG00001-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'START', 'Start', 'START', 'Organization creation initiated', NULL, NULL, NULL, 100, 100, datetime('now')),
('ORG00002-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'SUBMIT_DETAILS', 'Submit Organization Details', 'TASK', 'Enter basic organization info (name, type, industry, etc.)', NULL, NULL, 24, 200, 100, datetime('now')),
('ORG00003-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'VERIFY_LEGAL_DOCS', 'Verify Legal Documents', 'TASK', 'Upload and verify business license, registration, tax documents', NULL, NULL, 72, 300, 100, datetime('now')),
('ORG00004-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'REVIEW_APPLICATION', 'Review Application', 'TASK', 'Admin reviews organization application', NULL, NULL, 48, 400, 100, datetime('now')),
('ORG00005-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'CREATE_ORG_RECORD', 'Create Organization Record', 'TASK', 'Create organization entry in database', NULL, NULL, 2, 500, 100, datetime('now')),
('ORG00006-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'SETUP_ADDRESS', 'Setup Addresses', 'TASK', 'Configure headquarters and branch addresses', NULL, NULL, 24, 600, 100, datetime('now')),
('ORG00007-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'SETUP_STRUCTURE', 'Setup Organizational Structure', 'TASK', 'Define departments, teams, and hierarchy', NULL, NULL, 48, 700, 100, datetime('now')),
('ORG00008-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ADD_ADMIN_USERS', 'Add Administrator Users', 'TASK', 'Assign organization admin users and roles', NULL, NULL, 8, 800, 100, datetime('now')),
('ORG00009-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'CONFIGURE_SETTINGS', 'Configure Organization Settings', 'TASK', 'Set up permissions, workflows, and preferences', NULL, NULL, 24, 900, 100, datetime('now')),
('ORG00010-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'SETUP_PAYMENT', 'Setup Payment & Billing', 'TASK', 'Configure payment methods and billing details', NULL, NULL, 24, 1000, 100, datetime('now')),
('ORG00011-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ACTIVATE_ORG', 'Activate Organization', 'TASK', 'Final activation and enable for use', NULL, NULL, 2, 1100, 100, datetime('now')),
('ORG00012-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'SEND_WELCOME', 'Send Welcome Communication', 'TASK', 'Send welcome email with login credentials', NULL, NULL, 1, 1200, 100, datetime('now')),
('ORG00013-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'END', 'End', 'END', 'Organization creation complete', NULL, NULL, NULL, 1300, 100, datetime('now'));

-- Edges
INSERT OR IGNORE INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, edge_order, completion_action, created_at) VALUES
('ORGE0001-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00001-CREA-4111-W111-000000000001', 'ORG00002-CREA-4111-W111-000000000001', 'Begin', 1, NULL, datetime('now')),
('ORGE0002-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00002-CREA-4111-W111-000000000001', 'ORG00003-CREA-4111-W111-000000000001', 'Details Submitted', 1, 'COMPLETE', datetime('now')),
('ORGE0003-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00003-CREA-4111-W111-000000000001', 'ORG00004-CREA-4111-W111-000000000001', 'Documents Uploaded', 1, 'COMPLETE', datetime('now')),
('ORGE0004-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00004-CREA-4111-W111-000000000001', 'ORG00005-CREA-4111-W111-000000000001', 'Approved', 1, 'APPROVE', datetime('now')),
('ORGE0005-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00004-CREA-4111-W111-000000000001', 'ORG00002-CREA-4111-W111-000000000001', 'Rejected - Resubmit', 2, 'REJECT', datetime('now')),
('ORGE0006-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00005-CREA-4111-W111-000000000001', 'ORG00006-CREA-4111-W111-000000000001', 'Record Created', 1, 'COMPLETE', datetime('now')),
('ORGE0007-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00006-CREA-4111-W111-000000000001', 'ORG00007-CREA-4111-W111-000000000001', 'Addresses Configured', 1, 'COMPLETE', datetime('now')),
('ORGE0008-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00007-CREA-4111-W111-000000000001', 'ORG00008-CREA-4111-W111-000000000001', 'Structure Defined', 1, 'COMPLETE', datetime('now')),
('ORGE0009-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00008-CREA-4111-W111-000000000001', 'ORG00009-CREA-4111-W111-000000000001', 'Admins Added', 1, 'COMPLETE', datetime('now')),
('ORGE0010-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00009-CREA-4111-W111-000000000001', 'ORG00010-CREA-4111-W111-000000000001', 'Settings Configured', 1, 'COMPLETE', datetime('now')),
('ORGE0011-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00010-CREA-4111-W111-000000000001', 'ORG00011-CREA-4111-W111-000000000001', 'Payment Setup', 1, 'COMPLETE', datetime('now')),
('ORGE0012-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00011-CREA-4111-W111-000000000001', 'ORG00012-CREA-4111-W111-000000000001', 'Activated', 1, 'COMPLETE', datetime('now')),
('ORGE0013-CREA-4111-W111-000000000001', 'ORG00000-CREA-4111-W111-000000000001', 'ORG00012-CREA-4111-W111-000000000001', 'ORG00013-CREA-4111-W111-000000000001', 'Welcome Sent', 1, 'COMPLETE', datetime('now'));
