# Process Setup Guide

## Overview

After installing process definitions, you need to configure them with actual position and permission IDs from your system. This guide shows you how to set up the **Vacancy Creation Process**.

---

## Prerequisites

Before setting up the process, ensure:
1. ✅ Process Flow System installed (`metadata/010-process_flow_system.sql`)
2. ✅ Vacancy Process Entities installed (`metadata/011-vacancy_process_entities.sql`)
3. ✅ Vacancy Creation Process installed (`metadata/processes/vacancy_creation_process.sql`)
4. ✅ Hiring domain installed (`metadata/009-hiring_domain.sql`)

---

## Step 1: Check Installation Status

### Verify Process is Installed

```bash
php scripts/migrate.php migrate
```

**Expected Output:**
```
=== Running Process Definitions ===
  Found 1 pending process definition(s):
    - vacancy_creation_process.sql

  Installing process: vacancy_creation_process.sql
    ⚠ Warning: ... (multiple warnings about NULL values)
    ✓ Completed: 22 statements executed
```

**Note:** The warnings are expected. They occur because position and permission IDs are not yet configured.

### Verify Process Graph Created

```sql
SELECT id, code, name, is_active, is_published
FROM process_graph
WHERE code = 'VACANCY_CREATION';
```

**Expected Result:**
```
id                                   | code              | name                           | is_active | is_published
VC000000-0000-4000-8000-000000000001 | VACANCY_CREATION  | Job Vacancy Creation Process   | 1         | 1
```

---

## Step 2: Get Position IDs

The process requires 4 positions:
1. **HR Manager** - For drafting and reviewing vacancies
2. **Finance Manager** - For high-budget approvals
3. **Department Head** - For final approval
4. **HR Coordinator** - For publishing vacancies

### Query Existing Positions

```sql
SELECT id, title, description
FROM popular_organization_position
ORDER BY title;
```

### Create Positions if Missing

If positions don't exist, create them:

```sql
-- HR Manager
INSERT INTO popular_organization_position (id, title, description, created_at)
VALUES (
    'POS00001-0000-4000-8000-000000000001',
    'HR Manager',
    'Human Resources Manager responsible for hiring and personnel',
    datetime('now')
);

-- Finance Manager
INSERT INTO popular_organization_position (id, title, description, created_at)
VALUES (
    'POS00002-0000-4000-8000-000000000001',
    'Finance Manager',
    'Finance Manager responsible for budget approvals',
    datetime('now')
);

-- Department Head
INSERT INTO popular_organization_position (id, title, description, created_at)
VALUES (
    'POS00003-0000-4000-8000-000000000001',
    'Department Head',
    'Department Head with authority to approve hiring decisions',
    datetime('now')
);

-- HR Coordinator
INSERT INTO popular_organization_position (id, title, description, created_at)
VALUES (
    'POS00004-0000-4000-8000-000000000001',
    'HR Coordinator',
    'HR Coordinator responsible for posting vacancies',
    datetime('now')
);
```

---

## Step 3: Get Permission Type IDs

The process requires 3 permission types:
1. **REQUEST** - For creating/drafting
2. **APPROVER** - For reviewing and approving
3. **IMPLEMENTOR** - For executing/publishing

### Query Existing Permissions

```sql
SELECT id, code, name
FROM enum_entity_permission_type
ORDER BY code;
```

### Create Permission Types if Missing

If permissions don't exist, create them:

```sql
-- REQUEST permission
INSERT INTO enum_entity_permission_type (id, code, name, created_at)
VALUES (
    'PERM0001-0000-4000-8000-000000000001',
    'REQUEST',
    'Request/Create',
    datetime('now')
);

-- APPROVER permission
INSERT INTO enum_entity_permission_type (id, code, name, created_at)
VALUES (
    'PERM0002-0000-4000-8000-000000000001',
    'APPROVER',
    'Approve/Review',
    datetime('now')
);

-- IMPLEMENTOR permission
INSERT INTO enum_entity_permission_type (id, code, name, created_at)
VALUES (
    'PERM0003-0000-4000-8000-000000000001',
    'IMPLEMENTOR',
    'Implement/Execute',
    datetime('now')
);
```

---

## Step 4: Update Process Node Assignments

Now update the process nodes with actual position and permission IDs.

### Get Node IDs

```sql
SELECT id, node_code, node_name
FROM process_node
WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
  AND node_type = 'TASK';
```

**Expected Result:**
```
id                                   | node_code          | node_name
VC000002-0000-4000-8000-000000000001 | DRAFT_VACANCY      | Draft Vacancy Details
VC000003-0000-4000-8000-000000000001 | HR_REVIEW          | HR Review
VC000005-0000-4000-8000-000000000001 | FINANCE_APPROVAL   | Finance Approval
VC000006-0000-4000-8000-000000000001 | DEPT_HEAD_APPROVAL | Department Head Approval
VC000007-0000-4000-8000-000000000001 | PUBLISH_VACANCY    | Publish Vacancy
```

### Update Node 1: DRAFT_VACANCY

```sql
UPDATE process_node
SET position_id = 'POS00001-0000-4000-8000-000000000001',        -- HR Manager
    permission_type_id = 'PERM0001-0000-4000-8000-000000000001',  -- REQUEST
    escalate_to_position_id = 'POS00001-0000-4000-8000-000000000001'  -- HR Manager (or HR Director if you have one)
WHERE id = 'VC000002-0000-4000-8000-000000000001';
```

### Update Node 2: HR_REVIEW

```sql
UPDATE process_node
SET position_id = 'POS00001-0000-4000-8000-000000000001',        -- HR Manager
    permission_type_id = 'PERM0002-0000-4000-8000-000000000001'  -- APPROVER
WHERE id = 'VC000003-0000-4000-8000-000000000001';
```

### Update Node 3: FINANCE_APPROVAL

```sql
UPDATE process_node
SET position_id = 'POS00002-0000-4000-8000-000000000001',        -- Finance Manager
    permission_type_id = 'PERM0002-0000-4000-8000-000000000001'  -- APPROVER
WHERE id = 'VC000005-0000-4000-8000-000000000001';
```

### Update Node 4: DEPT_HEAD_APPROVAL

```sql
UPDATE process_node
SET position_id = 'POS00003-0000-4000-8000-000000000001',        -- Department Head
    permission_type_id = 'PERM0002-0000-4000-8000-000000000001'  -- APPROVER
WHERE id = 'VC000006-0000-4000-8000-000000000001';
```

### Update Node 5: PUBLISH_VACANCY

```sql
UPDATE process_node
SET position_id = 'POS00004-0000-4000-8000-000000000001',        -- HR Coordinator
    permission_type_id = 'PERM0003-0000-4000-8000-000000000001'  -- IMPLEMENTOR
WHERE id = 'VC000007-0000-4000-8000-000000000001';
```

---

## Step 5: Verify Configuration

### Check All Nodes Have Positions

```sql
SELECT
    node_code,
    node_name,
    p.title as position,
    ept.name as permission
FROM process_node pn
LEFT JOIN popular_organization_position p ON pn.position_id = p.id
LEFT JOIN enum_entity_permission_type ept ON pn.permission_type_id = ept.id
WHERE pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
  AND pn.node_type = 'TASK';
```

**Expected Result:**
```
node_code          | node_name                    | position         | permission
DRAFT_VACANCY      | Draft Vacancy Details        | HR Manager       | Request/Create
HR_REVIEW          | HR Review                    | HR Manager       | Approve/Review
FINANCE_APPROVAL   | Finance Approval             | Finance Manager  | Approve/Review
DEPT_HEAD_APPROVAL | Department Head Approval     | Department Head  | Approve/Review
PUBLISH_VACANCY    | Publish Vacancy              | HR Coordinator   | Implement/Execute
```

✅ **If all rows have position and permission, setup is complete!**

❌ **If any show NULL, go back and update those nodes**

---

## Step 6: Set Up Entity Permissions (Optional)

Link positions to entity permissions:

```sql
-- HR Manager can REQUEST and APPROVE on ORGANIZATION_VACANCY
INSERT INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed)
VALUES
    ('EPD00001-0000-4000-8000-000000000001', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'PERM0001-0000-4000-8000-000000000001', 'POS00001-0000-4000-8000-000000000001', 1),
    ('EPD00002-0000-4000-8000-000000000001', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'PERM0002-0000-4000-8000-000000000001', 'POS00001-0000-4000-8000-000000000001', 1);

-- Finance Manager can APPROVE
INSERT INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed)
VALUES
    ('EPD00003-0000-4000-8000-000000000001', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'PERM0002-0000-4000-8000-000000000001', 'POS00002-0000-4000-8000-000000000001', 1);

-- Department Head can APPROVE
INSERT INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed)
VALUES
    ('EPD00004-0000-4000-8000-000000000001', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'PERM0002-0000-4000-8000-000000000001', 'POS00003-0000-4000-8000-000000000001', 1);

-- HR Coordinator can IMPLEMENT
INSERT INTO entity_permission_definition (id, entity_id, permission_type_id, position_id, is_allowed)
VALUES
    ('EPD00005-0000-4000-8000-000000000001', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d', 'PERM0003-0000-4000-8000-000000000001', 'POS00004-0000-4000-8000-000000000001', 1);
```

---

## Step 7: Assign People to Positions

Create employment contracts linking people to positions:

```sql
-- Example: Assign John Doe to HR Manager position
INSERT INTO employment_contract (
    id,
    job_offer_id,
    organization_id,
    employee_id,
    contract_number,
    start_date,
    contract_type,
    status,
    created_at
)
VALUES (
    'CONTRACT1-0000-4000-8000-000000000001',
    NULL,  -- Or link to job_offer if available
    'your-organization-id',
    'john-doe-person-id',
    'EMP-2025-001',
    date('now'),
    'Permanent',
    'active',
    datetime('now')
);

-- Link employment to position via vacancy/offer chain
-- (This requires full hiring workflow setup - see VACANCY_CREATION_PROCESS.md)
```

---

## Step 8: Test the Process

### Start a Test Process

```bash
POST /api/process/start.php
```

```json
{
  "graph_code": "VACANCY_CREATION",
  "organization_id": "your-org-id",
  "entity_code": "ORGANIZATION_VACANCY",
  "entity_record_id": "test-vacancy-id",
  "variables": {
    "test": true
  }
}
```

### Check Process Status

```sql
SELECT
    id,
    reference_number,
    status,
    started_at
FROM task_flow_instance
WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
ORDER BY started_at DESC
LIMIT 1;
```

### Check Task Assignment

```sql
SELECT
    ti.id,
    pn.node_name,
    p.name as assigned_to,
    ti.status,
    ti.due_date
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
LEFT JOIN person p ON ti.assigned_to = p.id
WHERE ti.flow_instance_id = 'your-flow-instance-id';
```

---

## Troubleshooting

### Issue: Task Not Assigned to Anyone

**Cause:** Position has no active employee

**Solution:**
1. Check employment contracts:
```sql
SELECT * FROM employment_contract
WHERE organization_id = 'your-org-id'
  AND status = 'active';
```

2. Create employment contract or set up fallback assignment

### Issue: "Permission Denied"

**Cause:** Position doesn't have required permission

**Solution:**
1. Check entity permissions:
```sql
SELECT * FROM entity_permission_definition
WHERE position_id = 'your-position-id'
  AND entity_id = '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d';
```

2. Add permission (see Step 6)

### Issue: Process Doesn't Start

**Cause:** Process graph not published

**Solution:**
```sql
UPDATE process_graph
SET is_published = 1,
    published_at = datetime('now'),
    published_by = 'your-user-id'
WHERE code = 'VACANCY_CREATION';
```

---

## Quick Setup Script

For convenience, here's a complete setup script:

```sql
-- 1. Create Positions
INSERT OR IGNORE INTO popular_organization_position (id, title, description) VALUES
    ('POS00001-0000-4000-8000-000000000001', 'HR Manager', 'HR Manager'),
    ('POS00002-0000-4000-8000-000000000001', 'Finance Manager', 'Finance Manager'),
    ('POS00003-0000-4000-8000-000000000001', 'Department Head', 'Department Head'),
    ('POS00004-0000-4000-8000-000000000001', 'HR Coordinator', 'HR Coordinator');

-- 2. Create Permission Types
INSERT OR IGNORE INTO enum_entity_permission_type (id, code, name) VALUES
    ('PERM0001-0000-4000-8000-000000000001', 'REQUEST', 'Request/Create'),
    ('PERM0002-0000-4000-8000-000000000001', 'APPROVER', 'Approve/Review'),
    ('PERM0003-0000-4000-8000-000000000001', 'IMPLEMENTOR', 'Implement/Execute');

-- 3. Update Process Nodes
UPDATE process_node SET position_id = 'POS00001-0000-4000-8000-000000000001', permission_type_id = 'PERM0001-0000-4000-8000-000000000001' WHERE id = 'VC000002-0000-4000-8000-000000000001';
UPDATE process_node SET position_id = 'POS00001-0000-4000-8000-000000000001', permission_type_id = 'PERM0002-0000-4000-8000-000000000001' WHERE id = 'VC000003-0000-4000-8000-000000000001';
UPDATE process_node SET position_id = 'POS00002-0000-4000-8000-000000000001', permission_type_id = 'PERM0002-0000-4000-8000-000000000001' WHERE id = 'VC000005-0000-4000-8000-000000000001';
UPDATE process_node SET position_id = 'POS00003-0000-4000-8000-000000000001', permission_type_id = 'PERM0002-0000-4000-8000-000000000001' WHERE id = 'VC000006-0000-4000-8000-000000000001';
UPDATE process_node SET position_id = 'POS00004-0000-4000-8000-000000000001', permission_type_id = 'PERM0003-0000-4000-8000-000000000001' WHERE id = 'VC000007-0000-4000-8000-000000000001';

-- 4. Verify
SELECT node_code, node_name, position_id, permission_type_id
FROM process_node
WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
  AND node_type = 'TASK';
```

This script is available at `database/setup_vacancy_process.sql`. Run it with:
```bash
sqlite3 database/v4l.sqlite < database/setup_vacancy_process.sql
```

---

## Summary

✅ **Setup Complete When:**
- All positions created
- All permission types created
- All process nodes updated with position/permission IDs
- At least one person assigned to each position
- Entity permissions configured (optional but recommended)

🎉 **Your Vacancy Creation Process is now ready to use!**

For usage instructions, see `/guides/VACANCY_CREATION_PROCESS.md`
