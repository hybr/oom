# Process Flow System - Complete Documentation

> **📚 Note:** This is a rules and architecture reference. For implementation guides, tutorials, and examples, see the `/guides` folder (e.g., `VACANCY_CREATION_PROCESS.md`, `VACANCY_PROCESS_ENTITIES.md`).

## Overview

A comprehensive task-based workflow engine with graph-based processes, position-based permissions, parallel execution support, and complete audit trails.

## Architecture

### Three-Layer Design

1. **Definition Layer** - What CAN happen
   - `PROCESS_GRAPH` - Process templates with versioning
   - `PROCESS_NODE` - Steps in the process (START, TASK, DECISION, FORK, JOIN, END)
   - `PROCESS_EDGE` - Transitions between nodes
   - `PROCESS_EDGE_CONDITION` - Structured conditions for transitions
   - `ENTITY_PERMISSION_DEFINITION` - Position permissions per entity

2. **Execution Layer** - What IS happening
   - `TASK_FLOW_INSTANCE` - Running process instances
   - `TASK_INSTANCE` - Active tasks
   - `TASK_AUDIT_LOG` - Complete audit trail

3. **Security Layer** - WHO can do what
   - Position resolution through employment chain:
     ```
     EMPLOYMENT_CONTRACT → JOB_OFFER → VACANCY_APPLICATION →
     ORGANIZATION_VACANCY → POPULAR_ORGANIZATION_POSITION
     ```
   - `PROCESS_FALLBACK_ASSIGNMENT` - Fallback when position is vacant

## Core Classes

### ProcessEngine (`lib/ProcessEngine.php`)
- Start new process instances
- Transition between nodes
- Handle FORK/JOIN for parallel execution
- Evaluate edge conditions
- Complete/cancel processes

### TaskManager (`lib/TaskManager.php`)
- Create task instances
- Assign tasks based on positions
- Complete tasks
- Reassign tasks
- Track SLA and escalation
- Get user's tasks

### ConditionEvaluator (`lib/ConditionEvaluator.php`)
- Evaluate structured conditions
- Support multiple data sources (entity fields, flow variables, task variables, system)
- Operators: EQ, NEQ, GT, GTE, LT, LTE, IN, NOT_IN, CONTAINS, STARTS_WITH, ENDS_WITH, IS_NULL, IS_NOT_NULL, REGEX, BETWEEN
- Logical operators: AND, OR
- Condition grouping

### PositionResolver (`lib/PositionResolver.php`)
- Resolve position to person via employment chain
- Check permissions
- Handle fallback assignments
- Get organization admin as last resort

## API Endpoints

### Start Process
**POST** `/api/process/start.php`
```json
{
  "graph_code": "SIMPLE_APPROVAL",
  "organization_id": "uuid",
  "entity_code": "PURCHASE_REQUEST",
  "entity_record_id": "uuid",
  "variables": {"key": "value"}
}
```

### Complete Task
**POST** `/api/process/task-complete.php`
```json
{
  "task_instance_id": "uuid",
  "completion_action": "APPROVE",
  "comments": "Looks good",
  "completion_data": {"field": "value"}
}
```

### Get My Tasks
**GET** `/api/process/my-tasks.php?status=PENDING`

### Get Flow Status
**GET** `/api/process/flow-status.php?flow_instance_id=uuid`

## UI Pages

### My Tasks (`/pages/process/my-tasks.php`)
- View pending, in-progress, and completed tasks
- Complete tasks with actions (APPROVE, REJECT, COMPLETE)
- Filter by status
- See due dates and overdue tasks

## Node Types

1. **START** - Entry point of process
2. **TASK** - Work to be done by a person
   - Assigned based on position + permission
   - Has SLA and due dates
   - Can have instructions and form templates
3. **DECISION** - Conditional routing (evaluates edges)
4. **FORK** - Split into parallel tasks
5. **JOIN** - Wait for parallel tasks to complete
6. **END** - Process completion

## Example: Purchase Approval Process

```
START
  ↓
[Create Request] (Designer)
  ↓
{Budget Check Decision}
  ├─ If budget > 10000 → [Manager Approval] (Approver)
  │                        ↓
  │                      [Finance Approval] (Approver)
  └─ If budget ≤ 10000 → [Manager Approval] (Approver)
                           ↓
                      [Create PO] (Implementor)
                           ↓
                          END
```

### Creating This Process

1. **Create Process Graph**
```sql
INSERT INTO process_graph (id, code, name, version_number, is_active, is_published, created_by)
VALUES ('uuid', 'PURCHASE_APPROVAL', 'Purchase Approval Process', 1, 1, 1, 'user_id');
```

2. **Create Nodes**
```sql
-- START node
INSERT INTO process_node (id, graph_id, node_code, node_name, node_type)
VALUES ('node1', 'graph_id', 'START', 'Start', 'START');

-- Create Request task
INSERT INTO process_node (id, graph_id, node_code, node_name, node_type, position_id, permission_type_id, sla_hours)
VALUES ('node2', 'graph_id', 'CREATE_REQ', 'Create Request', 'TASK', 'designer_position_id', 'designer_perm_id', 24);

-- Budget check decision
INSERT INTO process_node (id, graph_id, node_code, node_name, node_type)
VALUES ('node3', 'graph_id', 'BUDGET_CHECK', 'Budget Check', 'DECISION');

-- Manager approval task
INSERT INTO process_node (id, graph_id, node_code, node_name, node_type, position_id, permission_type_id, sla_hours)
VALUES ('node4', 'graph_id', 'MGR_APPROVE', 'Manager Approval', 'TASK', 'manager_position_id', 'approver_perm_id', 48);

-- And so on...
```

3. **Create Edges**
```sql
-- START to Create Request
INSERT INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label)
VALUES ('edge1', 'graph_id', 'node1', 'node2', 'Begin');

-- Create Request to Budget Check
INSERT INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label)
VALUES ('edge2', 'graph_id', 'node2', 'node3', 'Submitted');

-- Budget Check to Manager (high budget)
INSERT INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, edge_order)
VALUES ('edge3', 'graph_id', 'node3', 'node4', 'High Budget', 1);

-- Budget Check to Manager (low budget)
INSERT INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, edge_order, is_default)
VALUES ('edge4', 'graph_id', 'node3', 'node4', 'Low Budget', 2, 1);
```

4. **Create Conditions**
```sql
-- High budget condition: budget > 10000
INSERT INTO process_edge_condition (id, edge_id, field_source, field_name, operator, value_type, compare_value)
VALUES ('cond1', 'edge3', 'ENTITY_FIELD', 'budget', 'GT', 'NUMBER', '10000');
```

## Features

### ✅ Versioning
- Each process graph can have multiple versions
- Running instances locked to specific version
- Safe to modify processes without affecting running instances

### ✅ Parallel Execution
- FORK node creates multiple parallel tasks
- JOIN node waits for all tasks to complete
- Each branch can have its own path

### ✅ Dynamic Conditions
- Structured condition storage
- Multiple data sources
- Complex logical expressions (AND/OR grouping)
- 12+ operators

### ✅ Position-Based Security
- Tasks assigned to positions, not specific people
- Position resolved through employment chain
- Permission verification
- Automatic fallback to admin/supervisor

### ✅ SLA Tracking
- Due dates calculated from SLA hours
- Overdue task detection
- Escalation support
- Notifications on assignment and due date

### ✅ Complete Audit Trail
- Every action logged immutably
- Who did what, when
- State transitions tracked
- Comments captured

### ✅ Flexible Assignment
- Auto-assignment based on position
- Manual reassignment
- Escalation on SLA breach
- Fallback when position vacant

## Database Migration

Run the migration:
```bash
sqlite3 database/v4l.sqlite < metadata/010-process_flow_system.sql
```

This creates:
- 8 new tables
- 8 entity definitions
- Sample attributes
- Relationships
- Indexes for performance

## Next Steps

1. **Run Migration** - Execute the SQL file
2. **Create Processes** - Design your workflows in process_graph
3. **Test** - Start a process and complete tasks
4. **Build UI** - Create process designer, monitoring dashboards
5. **Notifications** - Implement email/SMS for task assignments
6. **Reports** - Add analytics on process performance

## Security Considerations

- All API endpoints require authentication
- Task completion validates assignment
- Permission checks on position-based actions
- Audit log is immutable
- Organization isolation enforced

## Performance Tips

- Indexes on all foreign keys
- Use LIMIT on task queries
- Cache position resolutions
- Archive completed flows after retention period
- Monitor audit log growth

## Important Implementation Notes

### Transaction Management
- **DO NOT nest transactions** in ProcessEngine methods
- `startProcess()` manages the outer transaction
- Helper methods like `transitionToNode()` and `handleFork()` should NOT start their own transactions
- SQLite does not support true nested transactions - attempting to nest will cause "no active transaction" errors

### Authentication & Person IDs
- Use `Auth::user()['person_id']` NOT `Auth::id()` for foreign keys
- `Auth::id()` returns the credential ID from `person_credential.id`
- Foreign keys in process tables reference `person.id` (the person_id field)
- Always validate that user has an associated person record before starting processes

### Audit Log Schema
- `task_audit_log` has NO `deleted_at` column (audit logs are immutable)
- `task_audit_log` has NO single `node_id` column
- Use `from_node_id` and `to_node_id` to track state transitions
- Never filter audit logs by deletion status

### Process Node Schema
- `process_node` has NO `display_order` column
- Use `display_x` and `display_y` for visual positioning
- Order nodes by `node_type DESC, created_at` for logical display

### Organization Admin Field
- Organization table uses `main_admin_id` NOT `admin_id`
- Update all queries to use the correct column name

### Position Resolution Fallback
- `organization_vacancy` table does not exist yet
- All position resolution queries are wrapped in try-catch blocks
- When position lookup fails, system falls back to organization main admin
- This allows processes to run even without full vacancy/hiring workflow implementation

## Support

For issues or questions:
- Check audit logs for debugging
- Verify position → person resolution chain
- Test condition evaluation with sample data
- Review fallback assignments
- Ensure user has person_id before starting processes
- Verify no nested transactions in process engine methods
