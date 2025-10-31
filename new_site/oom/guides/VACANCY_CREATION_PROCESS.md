# Vacancy Creation & Approval Process

## Overview

A comprehensive workflow for creating and approving job vacancies in your organization. This process implements multi-level approvals, budget-based conditional routing, workstation assignment, and complete audit trails using the Process Flow System.

## Entity Structure

This process operates on two main entities:

### ORGANIZATION_VACANCY
Core vacancy information:
- **organization_id*** - Which organization
- **popular_position_id*** - Reference to POPULAR_ORGANIZATION_POSITION (contains department, team, designation)
- **created_by*** - Person who created the vacancy
- **title*** - Job title
- **description** - Detailed job description
- **requirements** - Required qualifications
- **responsibilities** - Job responsibilities
- **number_of_openings*** - How many positions
- **opening_date*** - When vacancy opens
- **closing_date** - When applications close
- **min_salary** - Minimum salary range
- **max_salary** - Maximum salary range
- **employment_type** - Full-time, Part-time, Contract, etc.
- **status*** - DRAFT, PENDING, APPROVED, OPEN, CLOSED
- **is_urgent** - Priority flag

**Note:** Department, Team, and Designation are NOT stored in ORGANIZATION_VACANCY. They are accessed through `popular_position_id` → POPULAR_ORGANIZATION_POSITION entity.

### ORGANIZATION_VACANCY_WORKSTATION
Links vacancy to physical workspace:
- **organization_vacancy_id*** - Which vacancy
- **organization_workstation_id*** - Which workstation/desk
- **notes** - Assignment notes

## Process Flow Diagram

```
START
  ↓
[Draft Vacancy] (HR Manager OR Department Head - REQUEST)
  • Create ORGANIZATION_VACANCY with:
    REQUIRED FIELDS:
    - organization_id (Organization)
    - popular_position_id (Position - determines dept/team/designation)
    - created_by (Creator)
    - title (Vacancy Title)
    - number_of_openings (How many positions)
    - opening_date (When vacancy opens)
    - status (Set to 'DRAFT')

    OPTIONAL FIELDS:
    - description (Detailed job description)
    - requirements (Required qualifications)
    - responsibilities (Key responsibilities)
    - closing_date (Application deadline)
    - min_salary (Minimum salary range)
    - max_salary (Maximum salary range)
    - employment_type (Full-time, Part-time, Contract, etc.)
    - is_urgent (Priority flag)
  ↓
[HR Review] (HR Manager - APPROVER, SLA: 24h)
  • Review compliance and salary ranges
  ├─ APPROVED → {Budget Check Decision}
  │              ├─ High Budget (max_salary > 100000) → [Finance Approval]
  │              │                                        ├─ APPROVED → [Dept Head Approval]
  │              │                                        └─ REJECTED → [Draft Vacancy] (loop)
  │              └─ Normal Budget (≤100000) → [Dept Head Approval]
  │                                             ├─ APPROVED → [Publish Vacancy]
  │                                             └─ REJECTED → [Draft Vacancy] (loop)
  └─ REJECTED → [Draft Vacancy] (loop back for revision)
       ↓
[Publish Vacancy] (HR Coordinator - IMPLEMENTOR, SLA: 24h)
  • Update status to 'OPEN'
  ↓
END (Vacancy Published)
```

## Features

### ✅ Dual Creator Support
- Both **HR Manager** and **Department Head** can initiate vacancy creation
- Uses REQUEST permission type
- Automatic position resolution via employment chain

### ✅ Multi-Level Approvals
- **HR Review** - Compliance and policy check
- **Finance Approval** - Budget validation (conditional)
- **Department Head** - Final approval with workstation assignment
- **Publication** - HR Coordinator publishes

### ✅ Conditional Finance Approval
- Automatically triggered when `max_salary > 100000`
- Normal budget positions skip finance review
- Budget threshold configurable via edge condition

### ✅ Rejection Loops
- All approval steps can reject and send back for revision
- Rejection comments captured in audit log
- Draft can be revised and resubmitted

### ✅ Workstation Assignment
- Department Head assigns workstation during approval
- Creates `ORGANIZATION_VACANCY_WORKSTATION` record
- Links vacancy to physical workspace

### ✅ Dynamic Forms
- Forms automatically generated from ORGANIZATION_VACANCY entity metadata
- Multi-entity forms (vacancy + workstation)
- Draft saving without validation
- Full validation on completion

### ✅ SLA Management
- Draft Vacancy: 48 hours
- HR Review: 24 hours
- Finance Approval: 48 hours
- Department Head: 48 hours
- Publication: 24 hours

## Installation

### Prerequisites

1. **Process Flow System** must be installed:
   ```bash
   sqlite3 database/v4l.sqlite < metadata/011-process_flow_system.sql
   sqlite3 database/v4l.sqlite < metadata/012-add-form-entities-to-process-node.sql
   ```

2. **Hiring Domain** must be installed:
   ```bash
   sqlite3 database/v4l.sqlite < metadata/010-hiring_domain.sql
   ```

### Step 1: Install Process Definition

```bash
sqlite3 database/v4l.sqlite < metadata/processes/vacancy_creation_process.sql
```

### Step 2: Configure Positions

The process requires these positions with permissions:

| Position | Permission | Used In |
|----------|------------|---------|
| HR Manager | REQUEST | Draft Vacancy |
| Department Head | REQUEST | Draft Vacancy |
| HR Manager | APPROVER | HR Review |
| Finance Manager | APPROVER | Finance Approval |
| Department Head | APPROVER | Department Head Approval |
| HR Coordinator | IMPLEMENTOR | Publish Vacancy |

**Get Position and Permission IDs:**
```sql
-- Get position IDs
SELECT id, title FROM popular_organization_position
WHERE title IN ('HR Manager', 'Department Head', 'Finance Manager', 'HR Coordinator');

-- Get permission type IDs
SELECT id, code, name FROM enum_entity_permission_type
WHERE code IN ('REQUEST', 'APPROVER', 'IMPLEMENTOR');
```

**Update Process Nodes:**
```sql
-- Update Draft Vacancy node (HR Manager or Department Head)
UPDATE process_node
SET position_id = 'YOUR_HR_MANAGER_POSITION_ID',
    permission_type_id = 'YOUR_REQUEST_PERMISSION_ID'
WHERE node_code = 'DRAFT_VACANCY'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Update HR Review node
UPDATE process_node
SET position_id = 'YOUR_HR_MANAGER_POSITION_ID',
    permission_type_id = 'YOUR_APPROVER_PERMISSION_ID'
WHERE node_code = 'HR_REVIEW'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Update Finance Approval node
UPDATE process_node
SET position_id = 'YOUR_FINANCE_MANAGER_POSITION_ID',
    permission_type_id = 'YOUR_APPROVER_PERMISSION_ID'
WHERE node_code = 'FINANCE_APPROVAL'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Update Department Head Approval node
UPDATE process_node
SET position_id = 'YOUR_DEPT_HEAD_POSITION_ID',
    permission_type_id = 'YOUR_APPROVER_PERMISSION_ID'
WHERE node_code = 'DEPT_HEAD_APPROVAL'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Update Publish Vacancy node
UPDATE process_node
SET position_id = 'YOUR_HR_COORDINATOR_POSITION_ID',
    permission_type_id = 'YOUR_IMPLEMENTOR_PERMISSION_ID'
WHERE node_code = 'PUBLISH_VACANCY'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';
```

### Step 3: Verify Installation

```sql
-- Check process graph
SELECT * FROM process_graph WHERE code = 'VACANCY_CREATION';

-- Check all nodes
SELECT node_code, node_name, node_type, sla_hours
FROM process_node
WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
ORDER BY display_x;

-- Check edges
SELECT pe.edge_label, pn1.node_code as from_node, pn2.node_code as to_node
FROM process_edge pe
JOIN process_node pn1 ON pe.from_node_id = pn1.id
JOIN process_node pn2 ON pe.to_node_id = pn2.id
WHERE pe.graph_id = 'VC000000-0000-4000-8000-000000000001'
ORDER BY pe.edge_order;
```

Expected output:
- 1 process graph: `VACANCY_CREATION`
- 8 nodes: START, DRAFT_VACANCY, HR_REVIEW, BUDGET_CHECK, FINANCE_APPROVAL, DEPT_HEAD_APPROVAL, PUBLISH_VACANCY, END
- 11 edges with conditions
- 7 edge conditions

## Usage

### Starting a New Vacancy Process

#### Option 1: Via API

**First, create the ORGANIZATION_VACANCY record:**
```javascript
// Create vacancy record with DRAFT status
const vacancyResponse = await fetch('/api/entity/create.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        entity_code: 'ORGANIZATION_VACANCY',
        data: {
            organization_id: 'your-org-id',
            popular_position_id: 'position-uuid',  // Which position to fill
            created_by: 'user-person-id',
            title: 'Senior Software Engineer',
            description: 'We are looking for...',
            requirements: 'Bachelor degree in CS, 5+ years experience...',
            responsibilities: 'Design and develop...',
            number_of_openings: 1,
            opening_date: '2025-11-01',
            closing_date: '2025-12-31',
            min_salary: 90000,
            max_salary: 120000,
            employment_type: 'Full-time',
            status: 'DRAFT',
            is_urgent: false
        }
    })
});
const vacancy = await vacancyResponse.json();

// Start the approval process
const processResponse = await fetch('/api/process/start.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        graph_code: 'VACANCY_CREATION',
        organization_id: 'your-org-id',
        entity_code: 'ORGANIZATION_VACANCY',
        entity_record_id: vacancy.id,
        variables: {
            urgency: vacancy.data.is_urgent ? 'high' : 'normal'
        }
    })
});
const process = await processResponse.json();
console.log('Process started:', process.reference_number);
```

#### Option 2: Via PHP

```php
require_once 'lib/ProcessEngine.php';

$engine = new ProcessEngine($db);

// Create vacancy record first
$vacancyId = createVacancy([
    'organization_id' => $orgId,
    'popular_position_id' => $positionId,
    'created_by' => $userId,
    'title' => 'Senior Software Engineer',
    'max_salary' => 120000,
    'min_salary' => 90000,
    'status' => 'DRAFT',
    // ... other fields
]);

// Start the process
$result = $engine->startProcess(
    'VACANCY_CREATION',          // graph_code
    $orgId,                       // organization_id
    $userId,                      // started_by
    'ORGANIZATION_VACANCY',       // entity_code
    $vacancyId,                   // entity_record_id
    ['urgency' => 'high']         // variables
);

echo "Flow started: " . $result['flow_instance_id'];
echo "Reference: " . $result['reference_number'];
```

### Completing Tasks

#### Draft Vacancy Task

```javascript
// Complete draft and submit for HR review
fetch('/api/process/save-task-data.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        action: 'complete',
        form_data: {
            title: 'Senior Software Engineer',
            popular_position_id: 'position-uuid',
            max_salary: 120000,
            min_salary: 90000,
            // ... all required fields
        },
        comments: 'Draft completed, ready for HR review',
        completion_action: 'COMPLETE'
    })
});
```

#### HR Review Task

**Approve:**
```javascript
fetch('/api/process/task-complete.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        completion_action: 'APPROVE',
        comments: 'Salary range is appropriate, job description is compliant'
    })
});
```

**Reject:**
```javascript
fetch('/api/process/task-complete.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        completion_action: 'REJECT',
        comments: 'Please reduce max salary to $100k to align with market rates'
    })
});
```

#### Finance Approval Task

```javascript
fetch('/api/process/task-complete.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        completion_action: 'APPROVE',
        comments: 'Budget approved from Q4 headcount allocation, cost center: CC-ENG-2025',
        completion_data: {
            cost_center: 'CC-ENG-2025',
            budget_code: 'HC-Q4-001'
        }
    })
});
```

#### Department Head Approval with Workstation Assignment

```javascript
fetch('/api/process/save-task-data.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        action: 'complete',
        form_data: {
            // Workstation assignment
            organization_vacancy_id: 'vacancy-uuid',
            organization_workstation_id: 'workstation-uuid',
            notes: 'Assigned to desk E-205, Building A'
        },
        comments: 'Approved. Workstation assigned. Reports to Sarah Chen.',
        completion_action: 'APPROVE'
    })
});
```

#### Publish Vacancy Task

```javascript
fetch('/api/process/task-complete.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'task-uuid',
        completion_action: 'COMPLETE',
        comments: 'Published to LinkedIn, Indeed, and internal board',
        completion_data: {
            published_channels: ['LinkedIn', 'Indeed', 'Internal'],
            published_date: '2025-10-26'
        }
    })
});

// Also update the vacancy status
fetch('/api/entity/update.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        entity_code: 'ORGANIZATION_VACANCY',
        record_id: 'vacancy-uuid',
        data: {
            status: 'OPEN'  // or 'APPROVED'
        }
    })
});
```

### Viewing Tasks

**Via UI:**
Navigate to `/pages/process/my-tasks.php`

**Via API:**
```bash
GET /api/process/my-tasks.php?status=PENDING
```

**Via SQL:**
```sql
SELECT
    ti.id,
    pn.node_name,
    tfi.reference_number,
    v.title as vacancy_title,
    ti.due_date,
    ti.status
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
LEFT JOIN organization_vacancy v ON tfi.entity_record_id = v.id
WHERE ti.assigned_to = 'your-person-id'
AND ti.status IN ('PENDING', 'IN_PROGRESS')
ORDER BY ti.due_date ASC;
```

## Process Execution Examples

### Example 1: High Budget Position ($120k)

```
1. START
   ↓
2. Draft Vacancy (HR Manager creates)
   - title: "Senior Software Engineer"
   - max_salary: 120000
   - min_salary: 90000
   - popular_position_id: (references position with dept/team/designation)
   - number_of_openings: 1
   - employment_type: "Full-time"
   - status: "DRAFT"
   ↓ COMPLETE
3. HR Review (HR Manager reviews)
   ↓ APPROVE
4. Budget Check Decision
   - Condition: max_salary (120000) > 100000 → TRUE
   - Routes to: Finance Approval (HIGH BUDGET PATH)
   ↓
5. Finance Approval (Finance Manager)
   - Reviews budget allocation
   ↓ APPROVE
6. Department Head Approval (Engineering Director)
   - Assigns workstation: Desk E-205
   ↓ APPROVE
7. Publish Vacancy (HR Coordinator)
   - Sets status to OPEN
   ↓ COMPLETE
8. END - Vacancy is now live!
```

**Total Time:** 3-5 days (depending on SLA adherence)
**Approvals:** 4 (HR, Finance, Dept Head, Publish)

### Example 2: Normal Budget Position ($75k)

```
1. START
   ↓
2. Draft Vacancy (Department Head creates)
   - title: "Junior Developer"
   - max_salary: 75000
   - min_salary: 55000
   - popular_position_id: (references position)
   - status: "DRAFT"
   ↓ COMPLETE
3. HR Review (HR Manager)
   ↓ APPROVE
4. Budget Check Decision
   - Condition: max_salary (75000) > 100000 → FALSE
   - Uses default edge: Department Head Approval (NORMAL BUDGET PATH)
   ↓
5. Department Head Approval (Engineering Director)
   - Assigns workstation: Desk E-210
   ↓ APPROVE
6. Publish Vacancy (HR Coordinator)
   ↓ COMPLETE
7. END
```

**Total Time:** 2-4 days
**Approvals:** 3 (HR, Dept Head, Publish) - Finance skipped

### Example 3: Rejection and Revision Loop

```
1. START
   ↓
2. Draft Vacancy (HR Manager)
   - max_salary: 150000 (too high)
   ↓ COMPLETE
3. HR Review (HR Manager)
   ↓ REJECT "Salary exceeds market rate by 30%, please reduce"
4. Back to Draft Vacancy (LOOP)
   - HR Manager revises
   - max_salary: 115000 (updated)
   ↓ COMPLETE
5. HR Review (HR Manager)
   ↓ APPROVE
6. Budget Check → Finance Approval → ... → END
```

## Customization

### Changing Budget Threshold

```sql
-- Change from $100k to $150k
UPDATE process_edge_condition
SET compare_value = '150000'
WHERE id = 'VCC00001-0000-4000-8000-000000000001';
```

### Modifying SLA Times

```sql
-- Change HR Review SLA from 24 to 48 hours
UPDATE process_node
SET sla_hours = 48
WHERE node_code = 'HR_REVIEW'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';
```

### Adding Escalation

```sql
-- Escalate to HR Director after 48 hours
UPDATE process_node
SET escalate_after_hours = 48,
    escalate_to_position_id = 'hr-director-position-id'
WHERE node_code = 'HR_REVIEW'
AND graph_id = 'VC000000-0000-4000-8000-000000000001';
```

## Monitoring & Reports

### Active Vacancy Processes

```sql
SELECT
    tfi.reference_number,
    v.title,
    pn.node_name as current_step,
    tfi.started_at,
    tfi.status
FROM task_flow_instance tfi
JOIN process_node pn ON tfi.current_node_id = pn.id
LEFT JOIN organization_vacancy v ON tfi.entity_record_id = v.id
WHERE tfi.graph_id = 'VC000000-0000-4000-8000-000000000001'
AND tfi.status = 'RUNNING'
ORDER BY tfi.started_at DESC;
```

### Overdue Tasks

```sql
SELECT
    ti.id,
    pn.node_name,
    v.title as vacancy_title,
    p.first_name || ' ' || p.last_name as assigned_to,
    ti.due_date,
    ROUND((JULIANDAY('now') - JULIANDAY(ti.due_date)) * 24, 1) as hours_overdue
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
LEFT JOIN organization_vacancy v ON tfi.entity_record_id = v.id
LEFT JOIN person p ON ti.assigned_to = p.id
WHERE ti.status IN ('PENDING', 'IN_PROGRESS')
AND ti.due_date < datetime('now')
AND pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
ORDER BY hours_overdue DESC;
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
AND pn.node_type = 'TASK'
GROUP BY pn.node_name;
```

### Average Time Per Step

```sql
SELECT
    pn.node_name,
    COUNT(*) as completed_count,
    ROUND(AVG(JULIANDAY(ti.completed_at) - JULIANDAY(ti.created_at)) * 24, 1) as avg_hours
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
WHERE ti.status = 'COMPLETED'
AND pn.graph_id = 'VC000000-0000-4000-8000-000000000001'
GROUP BY pn.node_name
ORDER BY avg_hours DESC;
```

## Troubleshooting

### Task Not Assigned

**Symptom:** Task created but no one assigned

**Solution:**
1. Check if position has active employee
2. Verify person has required permission
3. Configure fallback assignment
4. System will fallback to organization admin if all fails

```sql
-- Check employment for position
SELECT ec.*, p.first_name, p.last_name
FROM employment_contract ec
JOIN person p ON ec.employee_id = p.id
WHERE ec.position_id = 'your-position-id'
AND ec.status = 'ACTIVE';
```

### Process Stuck at Budget Check

**Symptom:** Process not advancing past BUDGET_CHECK decision

**Solution:**
Check that max_salary field has a value:

```sql
SELECT max_salary FROM organization_vacancy
WHERE id = 'vacancy-uuid';

-- Test condition manually
SELECT
    max_salary,
    CASE
        WHEN max_salary > 100000 THEN 'Finance Approval'
        ELSE 'Dept Head Approval'
    END as next_step
FROM organization_vacancy
WHERE id = 'vacancy-uuid';
```

### Workstation Not Assigned

**Symptom:** Department Head approved but no workstation link created

**Solution:**
Department Head must submit the ORGANIZATION_VACANCY_WORKSTATION form during their approval:

```sql
-- Check if workstation was assigned
SELECT * FROM organization_vacancy_workstation
WHERE organization_vacancy_id = 'vacancy-uuid';

-- Manually create if needed
INSERT INTO organization_vacancy_workstation (
    id, organization_vacancy_id, organization_workstation_id, notes
) VALUES (
    lower(hex(randomblob(16))),
    'vacancy-uuid',
    'workstation-uuid',
    'Assigned during process completion'
);
```

## Next Steps

1. ✅ Install the process definition
2. ✅ Configure positions and permissions
3. ✅ Test with sample vacancy
4. Build custom forms for each task type
5. Add email/SMS notifications
6. Create monitoring dashboard
7. Add rejection reason tracking
8. Implement approval analytics

## Support

For issues or questions:
- Review audit logs: `SELECT * FROM task_audit_log WHERE flow_instance_id = 'uuid'`
- Check task status: `SELECT * FROM task_instance WHERE flow_instance_id = 'uuid'`
- Verify position resolution
- Test conditions with sample data
- Review process engine logs

## Summary

This vacancy creation process provides:
- ✅ Complete approval workflow
- ✅ Budget-based routing
- ✅ Workstation assignment
- ✅ Rejection loops
- ✅ Dynamic forms
- ✅ SLA tracking
- ✅ Complete audit trail
- ✅ Multi-creator support

Ready to streamline your hiring process! 🎉
