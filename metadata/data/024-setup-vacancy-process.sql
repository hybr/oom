-- Quick Setup Script for Vacancy Creation Process
-- Run this after migration to configure the process with positions and permissions

-- ============================================
-- Step 1: Create Placeholder Department and Designation
-- ============================================
-- Note: popular_organization_position requires department_id and designation_id
-- Create placeholder records first

INSERT OR IGNORE INTO popular_organization_department (id, name, code, description, created_at)
VALUES ('00000000-0000-4000-8000-000000000001', 'Placeholder Department', 'PLACEHOLDER', 'Temporary department for process setup - replace with actual departments', datetime('now'));

INSERT OR IGNORE INTO popular_organization_designation (id, name, code, description, created_at)
VALUES ('00000000-0000-4000-8000-000000000001', 'Placeholder Designation', 'PLACEHOLDER', 'Temporary designation for process setup - replace with actual designations', datetime('now'));

-- ============================================
-- Step 2: Create Positions
-- ============================================

INSERT OR IGNORE INTO popular_organization_position (
    id,
    position_name,
    department_id,
    designation_id,
    code,
    description,
    created_at
)
VALUES
    ('POS00001-0000-4000-8000-000000000001', 'HR Manager', '00000000-0000-4000-8000-000000000001', '00000000-0000-4000-8000-000000000001', 'HR_MGR', 'Human Resources Manager responsible for hiring and personnel', datetime('now')),
    ('POS00002-0000-4000-8000-000000000001', 'Finance Manager', '00000000-0000-4000-8000-000000000001', '00000000-0000-4000-8000-000000000001', 'FIN_MGR', 'Finance Manager responsible for budget approvals', datetime('now')),
    ('POS00003-0000-4000-8000-000000000001', 'Department Head', '00000000-0000-4000-8000-000000000001', '00000000-0000-4000-8000-000000000001', 'DEPT_HD', 'Department Head with authority to approve hiring decisions', datetime('now')),
    ('POS00004-0000-4000-8000-000000000001', 'HR Coordinator', '00000000-0000-4000-8000-000000000001', '00000000-0000-4000-8000-000000000001', 'HR_COORD', 'HR Coordinator responsible for posting vacancies', datetime('now'));

-- ============================================
-- Step 3: Permission Types
-- ============================================
-- Note: Permission types are already defined in 014-entity_permission_definitions.sql
-- Using existing IDs:
-- REQUEST: a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c
-- APPROVER: c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e
-- IMPLEMENTOR: a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c

-- ============================================
-- Step 4: Update Process Nodes
-- ============================================

-- Node 1: DRAFT_VACANCY (HR Manager, REQUEST)
UPDATE process_node
SET position_id = 'POS00001-0000-4000-8000-000000000001',
    permission_type_id = 'a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c',
    escalate_to_position_id = 'POS00001-0000-4000-8000-000000000001'
WHERE id = 'VC000002-0000-4000-8000-000000000001';

-- Node 2: HR_REVIEW (HR Manager, APPROVER)
UPDATE process_node
SET position_id = 'POS00001-0000-4000-8000-000000000001',
    permission_type_id = 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e'
WHERE id = 'VC000003-0000-4000-8000-000000000001';

-- Node 3: FINANCE_APPROVAL (Finance Manager, APPROVER)
UPDATE process_node
SET position_id = 'POS00002-0000-4000-8000-000000000001',
    permission_type_id = 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e'
WHERE id = 'VC000005-0000-4000-8000-000000000001';

-- Node 4: DEPT_HEAD_APPROVAL (Department Head, APPROVER)
UPDATE process_node
SET position_id = 'POS00003-0000-4000-8000-000000000001',
    permission_type_id = 'c3d4e5f6-a7b8-49c0-1d2e-3f4a5b6c7d8e'
WHERE id = 'VC000006-0000-4000-8000-000000000001';

-- Node 5: PUBLISH_VACANCY (HR Coordinator, IMPLEMENTOR)
UPDATE process_node
SET position_id = 'POS00004-0000-4000-8000-000000000001',
    permission_type_id = 'a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c'
WHERE id = 'VC000007-0000-4000-8000-000000000001';

-- ============================================
-- Step 5: Verify Configuration
-- ============================================

-- Check all nodes have positions and permissions
SELECT
    pn.node_code,
    pn.node_name,
    p.position_name as position,
    ept.name as permission,
    CASE
        WHEN pn.position_id IS NULL THEN '❌ Missing Position'
        WHEN pn.permission_type_id IS NULL THEN '❌ Missing Permission'
        ELSE '✅ Configured'
    END as status
FROM process_node pn
LEFT JOIN popular_organization_position p ON pn.position_id = p.id
LEFT JOIN enum_entity_permission_type ept ON pn.permission_type_id = ept.id
WHERE pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
  AND pn.node_type = 'TASK'
ORDER BY pn.node_code;
