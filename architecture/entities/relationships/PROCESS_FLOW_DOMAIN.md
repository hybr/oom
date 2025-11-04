# Process Flow System - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Process Flow System provides a complete workflow engine with graph-based process definitions, task management, position-based assignments, and comprehensive audit trails.

**Domain Code:** `PROCESS_FLOW`

**Core Entities:** 8
- PROCESS_GRAPH (Templates)
- PROCESS_NODE (Steps)
- PROCESS_EDGE (Transitions)
- PROCESS_EDGE_CONDITION (Routing logic)
- TASK_FLOW_INSTANCE (Running processes)
- TASK_INSTANCE (Active tasks)
- TASK_AUDIT_LOG (Audit trail)
- PROCESS_FALLBACK_ASSIGNMENT (Fallback assignments)

---

## Three-Layer Architecture

### 1. Definition Layer (What CAN happen)
```
PROCESS_GRAPH (Process template)
  ↓
PROCESS_NODE (Steps: START, TASK, DECISION, FORK, JOIN, END)
  ↓
PROCESS_EDGE (Transitions between nodes)
  ↓
PROCESS_EDGE_CONDITION (Structured conditions for routing)
```

### 2. Execution Layer (What IS happening)
```
TASK_FLOW_INSTANCE (Running process instance)
  ↓
TASK_INSTANCE (Individual tasks)
  ↓
TASK_AUDIT_LOG (Immutable audit trail)
```

### 3. Security Layer (WHO can do what)
```
PROCESS_NODE.position_id → POPULAR_ORGANIZATION_POSITION
  ↓
EMPLOYMENT_CONTRACT (Position resolution chain)
  ↓
PERSON (Task assignee)

Fallback: PROCESS_FALLBACK_ASSIGNMENT
```

---

## Definition Layer Entities

### 1. PROCESS_GRAPH

**Entity Structure:**
```
PROCESS_GRAPH
├─ id* (PK)
├─ code* [VACANCY_CREATION, REQUISITION_APPROVAL, ...]
├─ name*
├─ description?
├─ entity_id? (FK → ENTITY_DEFINITION)
├─ version_number*
├─ is_active*
├─ is_published*
├─ category?
├─ created_by* (FK → PERSON)
├─ published_at?
└─ published_by? (FK → PERSON)
```

**Relationships:**
```
PROCESS_GRAPH
  ← PERSON (Many:1) [as creator]
  ← PERSON (Many:1) [as publisher]
  ← ENTITY_DEFINITION (Many:1) [Optional - linked entity]
  → PROCESS_NODE (1:Many)
  → PROCESS_EDGE (1:Many)
  → TASK_FLOW_INSTANCE (1:Many) [instances of this graph]
```

**Purpose:** Versioned process templates. Each process can have multiple versions.

---

### 2. PROCESS_NODE

**Entity Structure:**
```
PROCESS_NODE
├─ id* (PK)
├─ graph_id* (FK → PROCESS_GRAPH)
├─ node_code*
├─ node_name*
├─ node_type* [START, TASK, DECISION, FORK, JOIN, END]
├─ description?
├─ position_id? (FK → POPULAR_ORGANIZATION_POSITION) [for TASK nodes]
├─ permission_type_id? (FK → ENUM_ENTITY_PERMISSION_TYPE) [for TASK nodes]
├─ sla_hours? [Service Level Agreement]
├─ estimated_duration_hours?
├─ display_x? [Visual positioning]
├─ display_y? [Visual positioning]
├─ form_entities? (JSON array) [for dynamic form generation]
├─ instructions?
├─ notify_on_assignment?
├─ notify_on_due?
├─ escalate_after_hours?
└─ escalate_to_position_id? (FK → POPULAR_ORGANIZATION_POSITION)
```

**Relationships:**
```
PROCESS_NODE
  ← PROCESS_GRAPH (Many:1)
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [task assignee position]
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [escalation position]
  ← ENUM_ENTITY_PERMISSION_TYPE (Many:1) [required permission]
  → PROCESS_EDGE (1:Many) [outgoing edges]
  ← PROCESS_EDGE (1:Many) [incoming edges]
  → TASK_INSTANCE (1:Many) [instances of this node]
```

**Node Types:**
```
START    - Entry point (1 per graph)
TASK     - Work done by a person (requires position + permission)
DECISION - Conditional routing (evaluates edges)
FORK     - Split into parallel tasks
JOIN     - Wait for parallel tasks to complete
END      - Process completion (1+ per graph)
```

---

### 3. PROCESS_EDGE

**Entity Structure:**
```
PROCESS_EDGE
├─ id* (PK)
├─ graph_id* (FK → PROCESS_GRAPH)
├─ from_node_id* (FK → PROCESS_NODE)
├─ to_node_id* (FK → PROCESS_NODE)
├─ edge_label?
├─ edge_order* [Evaluation order]
├─ is_default? [Default path if no conditions match]
└─ description?
```

**Relationships:**
```
PROCESS_EDGE
  ← PROCESS_GRAPH (Many:1)
  ← PROCESS_NODE (Many:1) [from_node]
  → PROCESS_NODE (Many:1) [to_node]
  → PROCESS_EDGE_CONDITION (1:Many) [routing conditions]
```

**Purpose:** Defines transitions between nodes. Edges with conditions enable dynamic routing.

---

### 4. PROCESS_EDGE_CONDITION

**Entity Structure:**
```
PROCESS_EDGE_CONDITION
├─ id* (PK)
├─ edge_id* (FK → PROCESS_EDGE)
├─ condition_order*
├─ field_source* [ENTITY_FIELD, FLOW_VARIABLE, TASK_VARIABLE, SYSTEM]
├─ field_name*
├─ operator* [EQ, NEQ, GT, LT, GTE, LTE, IN, NOT_IN, CONTAINS, ...]
├─ value_type* [STRING, NUMBER, BOOLEAN, DATE]
├─ compare_value*
├─ logic_operator* [AND, OR]
└─ condition_group* [For grouping complex logic]
```

**Relationships:**
```
PROCESS_EDGE_CONDITION
  ← PROCESS_EDGE (Many:1)
```

**Purpose:** Structured condition storage for dynamic routing decisions.

**Example:**
```sql
-- Condition: max_salary > 100000
field_source = 'ENTITY_FIELD'
field_name = 'max_salary'
operator = 'GT'
value_type = 'NUMBER'
compare_value = '100000'
```

---

## Execution Layer Entities

### 5. TASK_FLOW_INSTANCE

**Entity Structure:**
```
TASK_FLOW_INSTANCE
├─ id* (PK)
├─ graph_id* (FK → PROCESS_GRAPH)
├─ organization_id* (FK → ORGANIZATION)
├─ entity_code? [e.g., ORGANIZATION_VACANCY]
├─ entity_record_id? [FK to entity record]
├─ reference_number* [AUTO-GENERATED: FLW-20251031-0001]
├─ current_node_id? (FK → PROCESS_NODE)
├─ status* [PENDING, RUNNING, COMPLETED, CANCELLED, FAILED]
├─ started_by* (FK → PERSON)
├─ started_at*
├─ completed_at?
├─ cancelled_at?
├─ cancellation_reason?
└─ flow_variables? (JSON) [Process-level variables]
```

**Relationships:**
```
TASK_FLOW_INSTANCE
  ← PROCESS_GRAPH (Many:1) [which template]
  ← ORGANIZATION (Many:1) [which organization]
  ← PROCESS_NODE (Many:1) [current step]
  ← PERSON (Many:1) [who started it]
  → TASK_INSTANCE (1:Many) [all tasks in this flow]
  → TASK_AUDIT_LOG (1:Many) [audit trail]
```

**Purpose:** Running instance of a process graph. Locked to specific version.

---

### 6. TASK_INSTANCE

**Entity Structure:**
```
TASK_INSTANCE
├─ id* (PK)
├─ flow_instance_id* (FK → TASK_FLOW_INSTANCE)
├─ node_id* (FK → PROCESS_NODE)
├─ assigned_to? (FK → PERSON)
├─ status* [PENDING, IN_PROGRESS, COMPLETED, CANCELLED]
├─ created_at*
├─ started_at?
├─ completed_at?
├─ due_date? [calculated from SLA]
├─ completion_action? [COMPLETE, APPROVE, REJECT, CANCEL]
├─ completion_comments?
├─ completion_data? (JSON) [form submissions]
└─ task_variables? (JSON) [task-level variables]
```

**Relationships:**
```
TASK_INSTANCE
  ← TASK_FLOW_INSTANCE (Many:1)
  ← PROCESS_NODE (Many:1) [which step]
  ← PERSON (Many:1) [assigned to whom]
  → TASK_AUDIT_LOG (1:Many) [task history]
```

**Purpose:** Individual work item. Status transitions: PENDING → IN_PROGRESS → COMPLETED.

---

### 7. TASK_AUDIT_LOG

**Entity Structure:**
```
TASK_AUDIT_LOG
├─ id* (PK)
├─ flow_instance_id* (FK → TASK_FLOW_INSTANCE)
├─ task_instance_id? (FK → TASK_INSTANCE)
├─ from_node_id? (FK → PROCESS_NODE)
├─ to_node_id? (FK → PROCESS_NODE)
├─ action* [START, ASSIGN, COMPLETE, TRANSITION, CANCEL]
├─ actor_id* (FK → PERSON)
├─ comments?
├─ changes? (JSON) [before/after state]
└─ created_at*
```

**Relationships:**
```
TASK_AUDIT_LOG
  ← TASK_FLOW_INSTANCE (Many:1)
  ← TASK_INSTANCE (Many:1) [Optional - flow-level events have no task]
  ← PROCESS_NODE (Many:1) [from_node]
  ← PROCESS_NODE (Many:1) [to_node]
  ← PERSON (Many:1) [who did it]
```

**Purpose:** Immutable audit trail. NO deleted_at column (never soft-delete audits).

---

### 8. PROCESS_FALLBACK_ASSIGNMENT

**Entity Structure:**
```
PROCESS_FALLBACK_ASSIGNMENT
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ fallback_type* [PERSON, POSITION, AUTO_ADMIN]
├─ fallback_person_id? (FK → PERSON)
├─ fallback_position_id? (FK → POPULAR_ORGANIZATION_POSITION)
├─ priority* [0 = highest]
└─ is_active*
```

**Relationships:**
```
PROCESS_FALLBACK_ASSIGNMENT
  ← ORGANIZATION (Many:1)
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [vacant position]
  ← PERSON (Many:1) [fallback person]
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [fallback position]
```

**Purpose:** Define fallback when a position has no active employee.

---

## Complete Process Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    PROCESS EXECUTION FLOW                        │
└─────────────────────────────────────────────────────────────────┘

PROCESS_GRAPH (Template: "Vacancy Creation v1.0")
  ↓
TASK_FLOW_INSTANCE (Instance: FLW-20251031-0001)
  status: RUNNING
  current_node_id: HR_REVIEW
  ↓
TASK_INSTANCE (Task: "Review Vacancy")
  node: HR_REVIEW (TASK type)
  assigned_to: (resolved via position chain)
  status: PENDING
  ↓
Position Resolution:
  PROCESS_NODE.position_id → HR_MANAGER
    ↓
  EMPLOYMENT_CONTRACT (who has this position?)
    ↓
  PERSON (John Smith) → assigned_to
  ↓
User completes task → TASK_INSTANCE.status = COMPLETED
  ↓
ProcessEngine evaluates PROCESS_EDGE conditions
  ↓
Transition to next PROCESS_NODE
  ↓
Create new TASK_INSTANCE for next step
  ↓
All logged in TASK_AUDIT_LOG
```

---

## Position Resolution Chain

Critical for task assignment:

```
PROCESS_NODE.position_id (e.g., "HR Manager")
  ↓
EMPLOYMENT_CONTRACT (active contracts for this position)
  ↓ (via job_offer_id)
JOB_OFFER
  ↓ (via application_id)
VACANCY_APPLICATION
  ↓ (via vacancy_id)
ORGANIZATION_VACANCY
  ↓ (via popular_position_id)
POPULAR_ORGANIZATION_POSITION
  ↓
PERSON (assigned to task)

If no active employment → PROCESS_FALLBACK_ASSIGNMENT
If no fallback → ORGANIZATION.main_admin_id
```

---

## Cross-Domain Relationships

### To Person Domain
```
PROCESS_GRAPH ← PERSON (via created_by, published_by)
TASK_FLOW_INSTANCE ← PERSON (via started_by)
TASK_INSTANCE ← PERSON (via assigned_to)
TASK_AUDIT_LOG ← PERSON (via actor_id)
PROCESS_FALLBACK_ASSIGNMENT ← PERSON (via fallback_person_id)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

### To Organization Domain
```
TASK_FLOW_INSTANCE ← ORGANIZATION
PROCESS_FALLBACK_ASSIGNMENT ← ORGANIZATION
```
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

### To Popular Organization Structure
```
PROCESS_NODE ← POPULAR_ORGANIZATION_POSITION (via position_id)
PROCESS_NODE ← POPULAR_ORGANIZATION_POSITION (via escalate_to_position_id)
PROCESS_FALLBACK_ASSIGNMENT ← POPULAR_ORGANIZATION_POSITION
```
See: [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md)

### To Permissions Domain
```
PROCESS_NODE ← ENUM_ENTITY_PERMISSION_TYPE (via permission_type_id)
```
See: [PERMISSIONS_SECURITY_DOMAIN.md](PERMISSIONS_SECURITY_DOMAIN.md)

### From Hiring Domain
```
TASK_FLOW_INSTANCE.entity_code = 'ORGANIZATION_VACANCY'
TASK_FLOW_INSTANCE.entity_record_id → ORGANIZATION_VACANCY.id
```
See: [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)

---

## Common Queries

### Get Active Processes for Organization
```sql
SELECT
    tfi.reference_number,
    pg.name as process_name,
    pn.node_name as current_step,
    tfi.started_at,
    p.first_name || ' ' || p.last_name as started_by_name
FROM task_flow_instance tfi
JOIN process_graph pg ON tfi.graph_id = pg.id
LEFT JOIN process_node pn ON tfi.current_node_id = pn.id
JOIN person p ON tfi.started_by = p.id
WHERE tfi.organization_id = ?
AND tfi.status = 'RUNNING'
ORDER BY tfi.started_at DESC;
```

### Get My Pending Tasks
```sql
SELECT
    ti.id,
    pn.node_name,
    pn.instructions,
    tfi.reference_number,
    ti.due_date,
    ti.status,
    CASE
      WHEN ti.due_date < datetime('now') THEN 1
      ELSE 0
    END as is_overdue
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
WHERE ti.assigned_to = ?
AND ti.status IN ('PENDING', 'IN_PROGRESS')
AND ti.deleted_at IS NULL
ORDER BY is_overdue DESC, ti.due_date ASC;
```

### Get Process Audit Trail
```sql
SELECT
    tal.created_at,
    tal.action,
    p.first_name || ' ' || p.last_name as actor,
    pn_from.node_name as from_step,
    pn_to.node_name as to_step,
    tal.comments
FROM task_audit_log tal
JOIN person p ON tal.actor_id = p.id
LEFT JOIN process_node pn_from ON tal.from_node_id = pn_from.id
LEFT JOIN process_node pn_to ON tal.to_node_id = pn_to.id
WHERE tal.flow_instance_id = ?
ORDER BY tal.created_at ASC;
```

### Find Overdue Tasks
```sql
SELECT
    ti.id,
    pn.node_name,
    tfi.reference_number,
    p.first_name || ' ' || p.last_name as assigned_to_name,
    ti.due_date,
    ROUND((JULIANDAY('now') - JULIANDAY(ti.due_date)) * 24, 1) as hours_overdue
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
JOIN task_flow_instance tfi ON ti.flow_instance_id = tfi.id
JOIN person p ON ti.assigned_to = p.id
WHERE ti.status IN ('PENDING', 'IN_PROGRESS')
AND ti.due_date < datetime('now')
AND tfi.organization_id = ?
ORDER BY hours_overdue DESC;
```

---

## Key Features

### ✅ Versioning
- Each process graph has version_number
- Running instances locked to specific version
- Safe to modify processes without affecting running instances

### ✅ Parallel Execution
- FORK node creates multiple parallel tasks
- JOIN node waits for all parallel tasks to complete
- Each branch maintains independent state

### ✅ Dynamic Conditions
- Structured condition storage (no eval!)
- Multiple data sources (entity fields, variables, system)
- Complex logical expressions (AND/OR grouping)
- 12+ operators (EQ, GT, IN, CONTAINS, REGEX, etc.)

### ✅ Position-Based Security
- Tasks assigned to positions, not people
- Automatic position resolution via employment chain
- Permission verification (APPROVER, IMPLEMENTOR, etc.)
- Fallback to admin if position vacant

### ✅ SLA Tracking
- Due dates calculated from sla_hours
- Overdue task detection
- Escalation support
- Notifications on assignment and due date

### ✅ Dynamic Form Generation
- Forms auto-generated from entity metadata
- Multi-entity forms via `form_entities` JSON field
- Draft saving without validation
- Pre-population with existing data

---

## Data Integrity Rules

1. **No Nested Transactions:**
   - ProcessEngine methods manage outer transaction
   - Helper methods must NOT start their own transactions

2. **Audit Log Immutability:**
   - TASK_AUDIT_LOG has NO deleted_at column
   - Never delete or modify audit records

3. **Person ID vs Credential ID:**
   - Use Auth::user()['person_id'] for foreign keys
   - Auth::id() returns credential ID, not person ID

4. **Organization Isolation:**
   - Always filter by organization_id
   - Prevent cross-organization data access

5. **Process Version Locking:**
   - Running instances reference specific graph version
   - Cannot change graph version of running instance

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Process Flow Guide:** [/architecture/processes/PROCESS_FLOW_SYSTEM.md](../PROCESS_FLOW_SYSTEM.md)
- **Quick Start:** [/architecture/processes/PROCESS_SYSTEM_QUICK_START.md](../PROCESS_SYSTEM_QUICK_START.md)
- **Vacancy Process Example:** [/guides/features/VACANCY_CREATION_PROCESS.md](../../guides/features/VACANCY_CREATION_PROCESS.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Process Flow System
