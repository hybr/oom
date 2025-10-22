# Quick Start: Process Flow System

## ✅ Installation Complete!

The Task-based Process Flow System has been successfully installed with:
- **8 Database Tables** - All created and indexed
- **4 Core Classes** - ProcessEngine, TaskManager, ConditionEvaluator, PositionResolver
- **4 API Endpoints** - Start process, complete task, my tasks, flow status
- **1 UI Page** - My Tasks inbox
- **8 Entity Definitions** - All registered in the entity manager

## How It Works

### Position Resolution Chain
```
Task needs "Manager with Approval Permission"
         ↓
Position: POPULAR_ORGANIZATION_POSITION
         ↓
ORGANIZATION_VACANCY (links position to job)
         ↓
VACANCY_APPLICATION (who applied)
         ↓
JOB_OFFER (who was offered the job)
         ↓
EMPLOYMENT_CONTRACT (who is currently employed)
         ↓
Person Assigned! ✓
```

If position is vacant → Falls back to organization admin

## Example Usage

### 1. Access My Tasks Page
Navigate to: `http://localhost:8000/pages/process/my-tasks.php`

You'll see three tabs:
- **Pending** - Tasks waiting for you
- **In Progress** - Tasks you're working on
- **Completed** - Your finished tasks

### 2. Create a Simple Process (SQL)

```sql
-- Step 1: Create the process graph
INSERT INTO process_graph (
    id, code, name, description, version_number,
    is_active, is_published, category, created_by
) VALUES (
    lower(hex(randomblob(16))),
    'DOCUMENT_APPROVAL',
    'Document Approval Process',
    'Simple two-step document approval',
    1, 1, 1, 'APPROVAL',
    'YOUR_USER_ID_HERE'
);

-- Step 2: Create START node
INSERT INTO process_node (
    id, graph_id, node_code, node_name, node_type
) SELECT
    lower(hex(randomblob(16))),
    id,
    'START',
    'Start',
    'START'
FROM process_graph WHERE code = 'DOCUMENT_APPROVAL';

-- Step 3: Create REVIEW task (requires APPROVER permission)
INSERT INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    position_id, permission_type_id, sla_hours, instructions
) SELECT
    lower(hex(randomblob(16))),
    pg.id,
    'REVIEW_DOC',
    'Review Document',
    'TASK',
    'YOUR_POSITION_ID',  -- Replace with actual position ID
    ept.id,  -- APPROVER permission
    24,
    'Please review the document and approve or reject'
FROM process_graph pg
CROSS JOIN enum_entity_permission_type ept
WHERE pg.code = 'DOCUMENT_APPROVAL'
AND ept.code = 'APPROVER';

-- Step 4: Create END node
INSERT INTO process_node (
    id, graph_id, node_code, node_name, node_type
) SELECT
    lower(hex(randomblob(16))),
    id,
    'END',
    'End',
    'END'
FROM process_graph WHERE code = 'DOCUMENT_APPROVAL';

-- Step 5: Connect nodes with edges
-- START → REVIEW
INSERT INTO process_edge (
    id, graph_id, from_node_id, to_node_id, edge_label
) SELECT
    lower(hex(randomblob(16))),
    pg.id,
    n1.id,
    n2.id,
    'Begin'
FROM process_graph pg
JOIN process_node n1 ON n1.graph_id = pg.id AND n1.node_code = 'START'
JOIN process_node n2 ON n2.graph_id = pg.id AND n2.node_code = 'REVIEW_DOC'
WHERE pg.code = 'DOCUMENT_APPROVAL';

-- REVIEW → END
INSERT INTO process_edge (
    id, graph_id, from_node_id, to_node_id, edge_label
) SELECT
    lower(hex(randomblob(16))),
    pg.id,
    n1.id,
    n2.id,
    'Complete'
FROM process_graph pg
JOIN process_node n1 ON n1.graph_id = pg.id AND n1.node_code = 'REVIEW_DOC'
JOIN process_node n2 ON n2.graph_id = pg.id AND n2.node_code = 'END'
WHERE pg.code = 'DOCUMENT_APPROVAL';
```

### 3. Start a Process (API)

```javascript
// Using fetch API
fetch('/api/process/start.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        graph_code: 'DOCUMENT_APPROVAL',
        organization_id: 'YOUR_ORG_ID',
        entity_code: null,  // Optional: link to entity
        entity_record_id: null,
        variables: {
            document_name: 'Annual Report 2025',
            urgency: 'high'
        }
    })
})
.then(r => r.json())
.then(data => {
    console.log('Process started!', data.reference_number);
});
```

### 4. Complete a Task (API)

```javascript
fetch('/api/process/task-complete.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        task_instance_id: 'TASK_ID_FROM_MY_TASKS',
        completion_action: 'APPROVE',
        comments: 'Document looks good, approved.',
        completion_data: {
            rating: 5
        }
    })
})
.then(r => r.json())
.then(data => console.log('Task completed!', data));
```

## Advanced: Process with Conditions

### Conditional Routing Example
```sql
-- Create a DECISION node
INSERT INTO process_node (
    id, graph_id, node_code, node_name, node_type
) SELECT
    lower(hex(randomblob(16))),
    id,
    'CHECK_AMOUNT',
    'Check Amount',
    'DECISION'
FROM process_graph WHERE code = 'YOUR_PROCESS';

-- Create edges with conditions
-- High amount path
INSERT INTO process_edge (
    id, graph_id, from_node_id, to_node_id,
    edge_label, edge_order
) VALUES (...);

-- Add condition: amount > 10000
INSERT INTO process_edge_condition (
    id, edge_id, field_source, field_name,
    operator, value_type, compare_value
) VALUES (
    lower(hex(randomblob(16))),
    'EDGE_ID_FROM_ABOVE',
    'ENTITY_FIELD',  -- or FLOW_VARIABLE, TASK_VARIABLE, SYSTEM
    'amount',
    'GT',  -- Greater Than
    'NUMBER',
    '10000'
);

-- Default/low amount path
INSERT INTO process_edge (..., is_default = 1);
```

### Supported Operators
- **Comparison**: EQ, NEQ, GT, GTE, LT, LTE
- **Membership**: IN, NOT_IN
- **String**: CONTAINS, NOT_CONTAINS, STARTS_WITH, ENDS_WITH, REGEX
- **Range**: BETWEEN
- **Null checks**: IS_NULL, IS_NOT_NULL

### Logical Operators
- **AND** - All conditions must be true
- **OR** - Any condition can be true
- **Groups** - Use condition_group to create complex logic

## Parallel Execution with FORK/JOIN

```sql
-- FORK node - starts parallel tasks
INSERT INTO process_node (..., node_type = 'FORK');

-- Multiple edges from FORK (all paths execute)
INSERT INTO process_edge (..., from_node_id = 'FORK_NODE', to_node_id = 'TASK_A');
INSERT INTO process_edge (..., from_node_id = 'FORK_NODE', to_node_id = 'TASK_B');
INSERT INTO process_edge (..., from_node_id = 'FORK_NODE', to_node_id = 'TASK_C');

-- JOIN node - waits for all tasks to complete
INSERT INTO process_node (..., node_type = 'JOIN');

-- Connect tasks to JOIN
INSERT INTO process_edge (..., from_node_id = 'TASK_A', to_node_id = 'JOIN_NODE');
INSERT INTO process_edge (..., from_node_id = 'TASK_B', to_node_id = 'JOIN_NODE');
INSERT INTO process_edge (..., from_node_id = 'TASK_C', to_node_id = 'JOIN_NODE');
```

## Fallback Assignments

When a position is vacant, configure fallback:

```sql
INSERT INTO process_fallback_assignment (
    id, organization_id, position_id,
    fallback_type, fallback_person_id, priority
) VALUES (
    lower(hex(randomblob(16))),
    'YOUR_ORG_ID',
    'VACANT_POSITION_ID',
    'PERSON',  -- or 'POSITION', 'AUTO_ADMIN'
    'FALLBACK_PERSON_ID',
    0  -- Lower number = higher priority
);
```

## Monitoring & Debugging

### Check Process Status
```sql
SELECT
    tfi.reference_number,
    tfi.status,
    pn.node_name as current_step,
    tfi.started_at,
    tfi.completed_at
FROM task_flow_instance tfi
LEFT JOIN process_node pn ON tfi.current_node_id = pn.id
WHERE tfi.organization_id = 'YOUR_ORG_ID'
ORDER BY tfi.created_at DESC;
```

### View Audit Trail
```sql
SELECT
    tal.created_at,
    tal.action,
    p.first_name || ' ' || p.last_name as actor,
    tal.comments
FROM task_audit_log tal
LEFT JOIN person p ON tal.actor_id = p.id
WHERE tal.flow_instance_id = 'FLOW_ID'
ORDER BY tal.created_at DESC;
```

### Find Overdue Tasks
```sql
SELECT
    ti.id,
    pn.node_name,
    tfi.reference_number,
    ti.due_date,
    p.first_name || ' ' || p.last_name as assigned_to
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
LEFT JOIN person p ON ti.assigned_to = p.id
WHERE ti.status IN ('PENDING', 'IN_PROGRESS')
AND ti.due_date < datetime('now');
```

## Next Steps

1. **Build Process Designer UI** - Visual drag-and-drop process builder
2. **Add Notifications** - Email/SMS for task assignments
3. **Create Dashboard** - Process performance metrics
4. **Build Reports** - Analyze process efficiency
5. **Add Versioning UI** - Manage process versions
6. **Implement Escalation** - Auto-escalate overdue tasks

## Need Help?

- 📖 Full documentation: `PROCESS_FLOW_SYSTEM.md`
- 🗄️ Database schema: `metadata/010-process_flow_system.sql`
- 🔧 Core classes: `lib/ProcessEngine.php`, `lib/TaskManager.php`
- 🌐 API endpoints: `public/api/process/*.php`
- 📱 UI pages: `public/pages/process/*.php`

## Summary of What's Available

### ✅ Installed Components
- 8 Database tables with indexes
- 4 PHP classes (ProcessEngine, TaskManager, ConditionEvaluator, PositionResolver)
- 4 API endpoints (start, task-complete, my-tasks, flow-status)
- 1 UI page (My Tasks)
- Complete documentation

### ✅ Features
- Graph-based workflows
- Position-based security
- Parallel execution (FORK/JOIN)
- Dynamic conditions
- Versioning
- SLA tracking
- Complete audit trail
- Fallback assignments
- Multi-organization support

### 🎯 Ready to Use!
The system is fully functional and ready for process creation!
