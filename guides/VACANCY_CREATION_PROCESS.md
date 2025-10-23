# Vacancy Creation Process - Complete Documentation

## Overview

A comprehensive workflow process for creating and publishing job vacancies in your organization. This process implements multi-level approvals, budget-based conditional routing, and complete audit trails using the Process Flow System.

## Process Flow Diagram

```
START
  ↓
[Draft Vacancy] (HR Manager - REQUEST)
  ↓
[HR Review] (HR Manager - APPROVER)
  ├─ APPROVED → {Budget Check Decision}
  │              ├─ High Budget (>$100k) → [Finance Approval] (Finance Manager - APPROVER)
  │              │                            ├─ APPROVED → [Dept Head Approval]
  │              │                            └─ REJECTED → [Draft Vacancy] (loop)
  │              └─ Normal Budget (≤$100k) → [Dept Head Approval] (Dept Head - APPROVER)
  │                                             ├─ APPROVED → [Publish Vacancy]
  │                                             └─ REJECTED → [Draft Vacancy] (loop)
  └─ REJECTED → [Draft Vacancy] (loop back for revision)
       ↓
[Publish Vacancy] (HR Coordinator - IMPLEMENTOR)
  ↓
END (Vacancy Published)
```

## Features

### ✅ Multi-Level Approval Chain
- HR drafts and reviews vacancy
- Finance approves high-budget positions (>$100,000)
- Department head gives final approval
- HR publishes the approved vacancy

### ✅ Conditional Routing
- Automatic budget threshold detection
- High-budget positions route through finance
- Normal positions skip finance approval
- Rejection loops back to drafting for revision

### ✅ Position-Based Security
- Each task assigned to specific position + permission
- Automatic position resolution through employment chain
- Fallback assignments when position vacant
- Permission verification before task assignment

### ✅ SLA & Escalation
- Draft: 48 hours (escalates after 72 hours)
- HR Review: 24 hours
- Finance Approval: 48 hours
- Dept Head Approval: 48 hours
- Publish: 24 hours

### ✅ Complete Audit Trail
- Every action logged immutably
- Who did what, when
- Approval/rejection reasons captured
- State transitions tracked

## Process Nodes

| Node | Type | Position | Permission | SLA (hrs) | Description |
|------|------|----------|------------|-----------|-------------|
| START | START | - | - | - | Entry point |
| DRAFT_VACANCY | TASK | HR Manager | REQUEST | 48 | Create vacancy draft |
| HR_REVIEW | TASK | HR Manager | APPROVER | 24 | Review for compliance |
| BUDGET_CHECK | DECISION | - | - | - | Route by salary |
| FINANCE_APPROVAL | TASK | Finance Manager | APPROVER | 48 | High-budget approval |
| DEPT_HEAD_APPROVAL | TASK | Department Head | APPROVER | 48 | Final approval |
| PUBLISH_VACANCY | TASK | HR Coordinator | IMPLEMENTOR | 24 | Publish vacancy |
| END | END | - | - | - | Process complete |

## Installation

### Step 1: Prerequisites

Ensure the following are already set up:

1. **Process Flow System** - Run migration:
   ```bash
   sqlite3 database/v4l.sqlite < metadata/010-process_flow_system.sql
   ```

2. **Hiring Domain** - Run migration:
   ```bash
   sqlite3 database/v4l.sqlite < metadata/009-hiring_domain.sql
   ```

3. **Position & Permission Setup**:
   - Create positions: HR Manager, Finance Manager, Department Head, HR Coordinator
   - Create permission types: REQUEST, APPROVER, IMPLEMENTOR
   - Link positions to permissions via `entity_permission_definition`

### Step 2: Update Position & Permission IDs

Edit `metadata/processes/vacancy_creation_process.sql` and replace the TODO placeholders:

```sql
-- Find these TODOs in the SQL file and replace with actual IDs:
position_id: NULL,  -- TODO: Replace with actual HR Manager position ID
permission_type_id: NULL,  -- TODO: Replace with REQUEST permission type ID
```

**How to find IDs:**
```sql
-- Get position IDs
SELECT id, title FROM popular_organization_position;

-- Get permission type IDs
SELECT id, code, name FROM enum_entity_permission_type;
```

**Positions to map:**
- HR Manager position ID → `DRAFT_VACANCY` and `HR_REVIEW` nodes
- Finance Manager position ID → `FINANCE_APPROVAL` node
- Department Head position ID → `DEPT_HEAD_APPROVAL` node
- HR Coordinator position ID → `PUBLISH_VACANCY` node

**Permissions to map:**
- REQUEST permission → `DRAFT_VACANCY` node
- APPROVER permission → `HR_REVIEW`, `FINANCE_APPROVAL`, `DEPT_HEAD_APPROVAL` nodes
- IMPLEMENTOR permission → `PUBLISH_VACANCY` node

### Step 3: Run the Process Definition

```bash
sqlite3 database/v4l.sqlite < metadata/processes/vacancy_creation_process.sql
```

### Step 4: Verify Installation

```sql
-- Check process graph created
SELECT * FROM process_graph WHERE code = 'VACANCY_CREATION';

-- Check all nodes created
SELECT node_code, node_name, node_type FROM process_node
WHERE graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Check all edges created
SELECT edge_label, from_node_id, to_node_id FROM process_edge
WHERE graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Should show:
-- - 1 process graph
-- - 8 nodes (1 START, 5 TASK, 1 DECISION, 1 END)
-- - 11 edges
-- - 7 conditions
```

## Usage

### Starting a New Vacancy Creation Process

**Option 1: Via API**

```bash
POST /api/process/start.php
```

```json
{
  "graph_code": "VACANCY_CREATION",
  "organization_id": "your-org-id",
  "entity_code": "ORGANIZATION_VACANCY",
  "entity_record_id": "vacancy-record-id",
  "variables": {
    "department": "Engineering",
    "urgency": "high"
  }
}
```

**Response:**
```json
{
  "success": true,
  "flow_instance_id": "uuid",
  "reference_number": "VAC-20251023-A3F2"
}
```

**Option 2: Via ProcessEngine (PHP)**

```php
require_once 'lib/ProcessEngine.php';

$engine = new ProcessEngine($db);

// Start the process
$result = $engine->startProcess(
    'VACANCY_CREATION',           // graph_code
    'your-organization-id',        // organization_id
    'user-id',                     // started_by
    'ORGANIZATION_VACANCY',        // entity_code
    'vacancy-record-id',           // entity_record_id
    ['department' => 'Engineering'] // variables
);

echo "Flow started: " . $result['flow_instance_id'];
echo "Reference: " . $result['reference_number'];
```

### Completing Tasks

**Option 1: Via API**

```bash
POST /api/process/task-complete.php
```

**Draft Vacancy (COMPLETE):**
```json
{
  "task_instance_id": "uuid",
  "completion_action": "COMPLETE",
  "comments": "Vacancy draft completed",
  "completion_data": {
    "title": "Senior Software Engineer",
    "max_salary": 120000,
    "min_salary": 90000
  }
}
```

**HR Review (APPROVE):**
```json
{
  "task_instance_id": "uuid",
  "completion_action": "APPROVE",
  "comments": "Vacancy details look good, approved for budget review"
}
```

**HR Review (REJECT):**
```json
{
  "task_instance_id": "uuid",
  "completion_action": "REJECT",
  "comments": "Salary range too high, please revise"
}
```

**Finance Approval (APPROVE):**
```json
{
  "task_instance_id": "uuid",
  "completion_action": "APPROVE",
  "comments": "Budget approved, proceed with department head approval"
}
```

**Department Head Approval (APPROVE):**
```json
{
  "task_instance_id": "uuid",
  "completion_action": "APPROVE",
  "comments": "Approved, please publish immediately"
}
```

**Publish Vacancy (COMPLETE):**
```json
{
  "task_instance_id": "uuid",
  "completion_action": "COMPLETE",
  "comments": "Vacancy published to LinkedIn, Indeed, and internal job board"
}
```

**Option 2: Via TaskManager (PHP)**

```php
require_once 'lib/TaskManager.php';

$taskManager = new TaskManager($db);

// Complete a task
$result = $taskManager->completeTask(
    $taskInstanceId,
    $userId,
    'APPROVE',                           // completion_action
    'Looks good to proceed',             // comments
    ['reviewed_date' => date('Y-m-d')]   // completion_data
);
```

### Viewing Your Tasks

**Via API:**
```bash
GET /api/process/my-tasks.php?status=PENDING
```

**Via UI:**
Navigate to `/pages/process/my-tasks.php`

### Tracking Flow Status

**Via API:**
```bash
GET /api/process/flow-status.php?flow_instance_id=uuid
```

**Response:**
```json
{
  "flow": {
    "id": "uuid",
    "reference_number": "VAC-20251023-A3F2",
    "status": "RUNNING",
    "current_node": "HR_REVIEW",
    "started_at": "2025-10-23 10:30:00"
  },
  "tasks": [
    {
      "id": "task-uuid",
      "node_name": "Draft Vacancy Details",
      "assigned_to": "John Doe",
      "status": "COMPLETED",
      "completed_at": "2025-10-23 11:00:00"
    },
    {
      "id": "task-uuid-2",
      "node_name": "HR Review",
      "assigned_to": "Jane Smith",
      "status": "IN_PROGRESS",
      "due_date": "2025-10-24 11:00:00"
    }
  ],
  "audit_log": [...]
}
```

## Process Execution Examples

### Example 1: High Budget Position ($120k)

```
1. START
   ↓
2. Draft Vacancy (HR Manager creates draft)
   - Title: "Senior Software Engineer"
   - Max Salary: $120,000
   - Min Salary: $90,000
   ↓ COMPLETE
3. HR Review (HR Manager reviews)
   ↓ APPROVE
4. Budget Check Decision
   - Condition: max_salary (120000) > 100000 → TRUE
   - Routes to: Finance Approval (HIGH BUDGET PATH)
   ↓
5. Finance Approval (Finance Manager)
   ↓ APPROVE
6. Department Head Approval (Department Head)
   ↓ APPROVE
7. Publish Vacancy (HR Coordinator)
   ↓ COMPLETE
8. END - Process Completed
```

**Total Approvals:** 4 (HR Review, Finance, Dept Head, Publish)

### Example 2: Normal Budget Position ($75k)

```
1. START
   ↓
2. Draft Vacancy (HR Manager creates draft)
   - Title: "Junior Developer"
   - Max Salary: $75,000
   - Min Salary: $55,000
   ↓ COMPLETE
3. HR Review (HR Manager reviews)
   ↓ APPROVE
4. Budget Check Decision
   - Condition: max_salary (75000) > 100000 → FALSE
   - Uses default edge: Department Head Approval (NORMAL BUDGET PATH)
   ↓
5. Department Head Approval (Department Head)
   ↓ APPROVE
6. Publish Vacancy (HR Coordinator)
   ↓ COMPLETE
7. END - Process Completed
```

**Total Approvals:** 3 (HR Review, Dept Head, Publish) - Finance skipped

### Example 3: Rejection Loop

```
1. START
   ↓
2. Draft Vacancy (HR Manager)
   ↓ COMPLETE
3. HR Review (HR Manager)
   ↓ REJECT (salary too high)
4. Back to Draft Vacancy (LOOP)
   - HR Manager revises draft
   - Reduces max_salary
   ↓ COMPLETE
5. HR Review (HR Manager)
   ↓ APPROVE
6. Budget Check → ... → END
```

## Conditional Routing Logic

### Budget Check Decision

The `BUDGET_CHECK` node uses these conditions:

**High Budget Edge (Edge Priority 1):**
```
IF entity_field.max_salary > 100000
THEN route to FINANCE_APPROVAL
```

**Normal Budget Edge (Edge Priority 2, Default):**
```
IF no other condition matches (default)
THEN route to DEPT_HEAD_APPROVAL
```

### Approval/Rejection Routing

Each approval task has two outgoing edges:

**Approval Edge:**
```
IF task_variable.completion_action = 'APPROVE'
THEN proceed to next step
```

**Rejection Edge:**
```
IF task_variable.completion_action = 'REJECT'
THEN loop back to DRAFT_VACANCY
```

## Customization

### Changing Budget Threshold

Edit the condition in `process_edge_condition` table:

```sql
UPDATE process_edge_condition
SET compare_value = '150000'  -- New threshold
WHERE id = 'VCC00001-0000-4000-8000-000000000001';
```

### Adding New Approval Steps

Example: Add Legal Review after Finance Approval

1. **Create new node:**
```sql
INSERT INTO process_node (id, graph_id, node_code, node_name, node_type, ...)
VALUES ('VC000009-...', 'VC000000-...', 'LEGAL_REVIEW', 'Legal Review', 'TASK', ...);
```

2. **Modify edges:**
```sql
-- Change Finance → Dept Head edge to Finance → Legal
UPDATE process_edge
SET to_node_id = 'VC000009-...'  -- Legal Review node
WHERE id = 'VCE00007-0000-4000-8000-000000000001';

-- Add new Legal → Dept Head edge
INSERT INTO process_edge (...)
VALUES (...);
```

### Adding Parallel Approval (FORK/JOIN)

Replace DEPT_HEAD_APPROVAL with:

```
FORK Node
  ├─ Department Head Approval
  └─ HR Director Approval
       ↓
JOIN Node (waits for both)
  ↓
Publish Vacancy
```

### Customizing SLA Times

```sql
-- Change HR Review SLA from 24 to 48 hours
UPDATE process_node
SET sla_hours = 48
WHERE node_code = 'HR_REVIEW';
```

### Adding Escalation

```sql
-- Add escalation to HR Director after 72 hours
UPDATE process_node
SET escalate_after_hours = 72,
    escalate_to_position_id = 'hr-director-position-id'
WHERE node_code = 'HR_REVIEW';
```

## Fallback Assignments

If a position is vacant, configure fallback:

```sql
INSERT INTO process_fallback_assignment (
    id,
    organization_id,
    position_id,           -- Vacant position
    fallback_type,         -- PERSON, POSITION, or AUTO_ADMIN
    fallback_person_id,    -- Specific person
    fallback_position_id,  -- Or alternative position
    priority,              -- Lower = higher priority
    is_active
)
VALUES (
    'uuid',
    'org-id',
    'hr-manager-position-id',
    'POSITION',
    NULL,
    'hr-director-position-id',  -- Escalate to HR Director
    1,
    1
);
```

## Monitoring & Reports

### Active Flows

```sql
SELECT
    fi.reference_number,
    fi.status,
    pn.node_name as current_step,
    fi.started_at,
    fi.started_by
FROM task_flow_instance fi
JOIN process_node pn ON fi.current_node_id = pn.id
WHERE fi.graph_id = 'VC000000-0000-4000-8000-000000000001'
  AND fi.status = 'RUNNING'
ORDER BY fi.started_at DESC;
```

### Overdue Tasks

```sql
SELECT
    ti.id,
    pn.node_name,
    p.name as assigned_to,
    ti.due_date,
    ROUND((JULIANDAY('now') - JULIANDAY(ti.due_date)) * 24, 1) as hours_overdue
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN person p ON ti.assigned_to = p.id
WHERE ti.status IN ('PENDING', 'IN_PROGRESS')
  AND ti.due_date < datetime('now')
  AND pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
ORDER BY hours_overdue DESC;
```

### Average Completion Time

```sql
SELECT
    pn.node_name,
    COUNT(*) as completed_count,
    ROUND(AVG(JULIANDAY(ti.completed_at) - JULIANDAY(ti.created_at)) * 24, 1) as avg_hours,
    MIN(JULIANDAY(ti.completed_at) - JULIANDAY(ti.created_at)) * 24 as min_hours,
    MAX(JULIANDAY(ti.completed_at) - JULIANDAY(ti.created_at)) * 24 as max_hours
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
WHERE ti.status = 'COMPLETED'
  AND pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
GROUP BY pn.node_name;
```

### Rejection Rate by Step

```sql
SELECT
    pn.node_name,
    COUNT(*) as total_completions,
    SUM(CASE WHEN ti.completion_action = 'REJECT' THEN 1 ELSE 0 END) as rejections,
    ROUND(100.0 * SUM(CASE WHEN ti.completion_action = 'REJECT' THEN 1 ELSE 0 END) / COUNT(*), 1) as rejection_rate
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
WHERE ti.status = 'COMPLETED'
  AND pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
GROUP BY pn.node_name;
```

## Troubleshooting

### Task Not Assigned

**Symptom:** Task created but no one assigned

**Causes:**
1. Position has no active employee
2. Person doesn't have required permission
3. No fallback assignment configured

**Solution:**
```sql
-- Check position employment
SELECT ec.*, p.name
FROM employment_contract ec
JOIN person p ON ec.employee_id = p.id
WHERE ec.organization_id = 'your-org-id'
  AND ec.status = 'ACTIVE'
  AND (ec.end_date IS NULL OR ec.end_date > date('now'));

-- Check permissions
SELECT * FROM entity_permission_definition
WHERE position_id = 'position-id'
  AND permission_type_id = 'permission-id'
  AND is_allowed = 1;

-- Add fallback
INSERT INTO process_fallback_assignment ...
```

### Process Stuck at DECISION Node

**Symptom:** Process not moving past Budget Check

**Cause:** Conditions not evaluating correctly

**Solution:**
```sql
-- Check entity field value
SELECT max_salary FROM organization_vacancy
WHERE id = 'entity-record-id';

-- Test condition manually
SELECT
    CASE
        WHEN max_salary > 100000 THEN 'Finance Approval'
        ELSE 'Dept Head Approval'
    END as next_step
FROM organization_vacancy
WHERE id = 'entity-record-id';
```

### Rejection Loop Not Working

**Symptom:** REJECT action doesn't loop back

**Cause:** Condition not matching completion_action

**Solution:**
```sql
-- Verify completion_action stored correctly
SELECT completion_action, completion_comments
FROM task_instance
WHERE id = 'task-id';

-- Check edge condition
SELECT * FROM process_edge_condition
WHERE edge_id IN (
    SELECT id FROM process_edge
    WHERE from_node_id = 'hr-review-node-id'
);
```

## Security Considerations

1. **Authentication Required:** All API endpoints require valid user session
2. **Authorization Checks:** Only assigned user or org admin can complete tasks
3. **Permission Verification:** System verifies position permissions before assignment
4. **Audit Trail:** All actions logged with user ID, timestamp, and IP address
5. **Organization Isolation:** Processes scoped to organization context
6. **Immutable Audit:** Audit log is append-only, no updates or deletes

## Performance Tips

1. **Index Optimization:** Indexes already created on critical foreign keys
2. **Limit Queries:** Use LIMIT when fetching tasks
3. **Cache Position Resolutions:** Store resolved position → person mappings
4. **Archive Completed Flows:** Move old flows to archive table after 6-12 months
5. **Monitor Audit Log Growth:** Implement log rotation or archival strategy

## Next Steps

1. ✅ Install the process definition
2. ✅ Map positions and permissions
3. ✅ Test with sample vacancy
4. Create UI forms for each task with guided input fields
5. Add email/SMS notifications on task assignment
6. Build process monitoring dashboard
7. Create admin panel for process management
8. Add analytics and reporting
9. Implement process version management
10. Create visual process designer tool

## Support

For issues or questions:
- Review audit logs: `SELECT * FROM task_audit_log WHERE flow_instance_id = 'uuid'`
- Check task status: `SELECT * FROM task_instance WHERE flow_instance_id = 'uuid'`
- Verify position resolution chain
- Test condition evaluation with sample data
- Review `lib/ProcessEngine.php`, `lib/TaskManager.php`, `lib/ConditionEvaluator.php`

## License

This process definition is part of your organization's workflow system.
