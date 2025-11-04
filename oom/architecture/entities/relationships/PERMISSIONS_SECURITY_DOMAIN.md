# Permissions & Security Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Permissions & Security domain provides position-based access control, defining who can perform which actions on entities based on their organizational position.

**Domain Code:** `PERMISSIONS`

**Core Entities:** 2
- ENUM_ENTITY_PERMISSION_TYPE
- ENTITY_PERMISSION_DEFINITION

---

## Permission Model

### Position-Based Access Control

```
PERSON (Who)
  ↓
EMPLOYMENT_CONTRACT (Their job)
  ↓
POPULAR_ORGANIZATION_POSITION (Their position)
  ↓
ENTITY_PERMISSION_DEFINITION (What permissions)
  ↓
ENUM_ENTITY_PERMISSION_TYPE (What actions allowed)
  ↓
ACTIONS: CREATE, READ, UPDATE, DELETE (or REQUEST, APPROVER, etc.)
```

---

## 1. ENUM_ENTITY_PERMISSION_TYPE

### Entity Structure
```
ENUM_ENTITY_PERMISSION_TYPE
├─ id* (PK)
├─ code* [REQUEST, APPROVER, IMPLEMENTOR, REVIEWER, ADMIN]
├─ name*
└─ description?
```

### Relationships
```
ENUM_ENTITY_PERMISSION_TYPE
  → ENTITY_PERMISSION_DEFINITION (1:Many)
  → PROCESS_NODE (1:Many) [task requirements]
```

### Standard Permission Types

| Code | Name | Description | Use Case |
|------|------|-------------|----------|
| **REQUEST** | Requester | Can create/initiate requests | Start processes, create drafts |
| **APPROVER** | Approver | Can approve or reject | Review and approve requests |
| **IMPLEMENTOR** | Implementor | Can execute/implement | Publish, deploy, finalize |
| **REVIEWER** | Reviewer | Can view and comment | Read-only review access |
| **ADMIN** | Administrator | Full CRUD access | Entity administration |

---

## 2. ENTITY_PERMISSION_DEFINITION

### Entity Structure
```
ENTITY_PERMISSION_DEFINITION
├─ id* (PK)
├─ entity_id* (FK → ENTITY_DEFINITION)
├─ permission_type_id* (FK → ENUM_ENTITY_PERMISSION_TYPE)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
└─ is_allowed* [1 = allowed, 0 = denied]
```

### Relationships
```
ENTITY_PERMISSION_DEFINITION
  ← ENTITY_DEFINITION (Many:1)
  ← ENUM_ENTITY_PERMISSION_TYPE (Many:1)
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
```

### Purpose
Defines which positions have which permissions on which entities.

**Example:**
```
ENTITY: ORGANIZATION_VACANCY
PERMISSION: APPROVER
POSITION: HR Manager
IS_ALLOWED: 1

→ HR Managers can approve organization vacancies
```

---

## Complete Permission Flow

```
┌─────────────────────────────────────────────────────────────────┐
│              POSITION-BASED PERMISSION FLOW                      │
└─────────────────────────────────────────────────────────────────┘

1. USER ACTION
   John Smith wants to approve a vacancy
   ↓

2. IDENTIFY USER POSITION
   SELECT position_id FROM employment_contract
   WHERE employee_id = 'john-smith-id'
   AND status = 'ACTIVE'
   → Position: "HR Manager"
   ↓

3. CHECK PERMISSION DEFINITION
   SELECT is_allowed FROM entity_permission_definition
   WHERE entity_id = 'ORGANIZATION_VACANCY'
   AND permission_type_id = 'APPROVER'
   AND position_id = 'hr-manager-position-id'
   → is_allowed = 1 ✓
   ↓

4. GRANT ACCESS
   Allow John to approve the vacancy
```

---

## Permission Hierarchy

### Organization-Level Permissions (Bypass Position Checks)

```
Level 1: MAIN_ADMIN (organization.main_admin_id)
  ↓ Full ownership - ALL permissions
  ↓ Cannot be restricted

Level 2: SUPER_ADMIN (organization_admin.role = 'SUPER_ADMIN')
  ↓ Nearly full access
  ↓ Cannot transfer main_admin

Level 3: ADMIN (organization_admin.role = 'ADMIN')
  ↓ Management permissions
  ↓ Can manage employees and settings

Level 4: MODERATOR (organization_admin.role = 'MODERATOR')
  ↓ Read-only access
  ↓ Can view but not modify

Level 5: EMPLOYEE (employment_contract)
  ↓ Position-based permissions ONLY
  ↓ Subject to ENTITY_PERMISSION_DEFINITION checks
```

**Permission Check Logic:**
```
1. Is user main_admin? → ALLOW ALL
2. Is user super_admin? → ALLOW MOST
3. Is user admin? → CHECK ADMIN PERMISSIONS
4. Is user moderator? → READ ONLY
5. Is user employee? → CHECK ENTITY_PERMISSION_DEFINITION
6. Otherwise → DENY
```

---

## Common Permission Patterns

### Pattern 1: Process Flow Permissions

Used by PROCESS_NODE to determine task assignment:

```
PROCESS_NODE: "HR Review"
  position_id: HR Manager
  permission_type_id: APPROVER
  ↓
ENTITY_PERMISSION_DEFINITION:
  entity: ORGANIZATION_VACANCY
  permission_type: APPROVER
  position: HR Manager
  is_allowed: 1
  ↓
Result: HR Managers can be assigned "HR Review" tasks
```

### Pattern 2: Multiple Positions, Same Permission

```
ENTITY: ORGANIZATION_VACANCY
PERMISSION: REQUEST (can create vacancies)

ENTITY_PERMISSION_DEFINITION:
  ├─ Position: HR Manager → is_allowed = 1
  └─ Position: Department Head → is_allowed = 1

Result: Both HR Managers and Department Heads can create vacancies
```

### Pattern 3: Denied Permissions

```
ENTITY: SENSITIVE_DOCUMENT
PERMISSION: APPROVER
POSITION: Junior Developer
IS_ALLOWED: 0

Result: Explicitly deny junior developers from approving sensitive documents
```

---

## Cross-Domain Relationships

### To Popular Organization Structure
```
ENTITY_PERMISSION_DEFINITION ← POPULAR_ORGANIZATION_POSITION
```
See: [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md)

### To System Metadata
```
ENTITY_PERMISSION_DEFINITION ← ENTITY_DEFINITION
```
See: System Metadata documentation

### To Process Flow System
```
PROCESS_NODE ← ENUM_ENTITY_PERMISSION_TYPE
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

### To Hiring Domain
```
EMPLOYMENT_CONTRACT links PERSON to POSITION
→ Enables position-based permission checks
```
See: [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)

---

## Common Queries

### Check if User Has Permission on Entity
```sql
-- Step 1: Get user's active positions
SELECT ec.position_id
FROM employment_contract ec
WHERE ec.employee_id = ?
AND ec.status = 'ACTIVE'
AND ec.organization_id = ?
AND ec.deleted_at IS NULL;

-- Step 2: Check permission for position
SELECT epd.is_allowed
FROM entity_permission_definition epd
JOIN entity_definition ed ON epd.entity_id = ed.id
WHERE ed.code = 'ORGANIZATION_VACANCY'
AND epd.permission_type_id = (
    SELECT id FROM enum_entity_permission_type
    WHERE code = 'APPROVER'
)
AND epd.position_id IN (
    SELECT position_id FROM employment_contract
    WHERE employee_id = ? AND status = 'ACTIVE'
)
AND epd.is_allowed = 1;
```

### Get All Permissions for Position
```sql
SELECT
    ed.code as entity_code,
    ed.name as entity_name,
    ept.code as permission_code,
    ept.name as permission_name,
    epd.is_allowed
FROM entity_permission_definition epd
JOIN entity_definition ed ON epd.entity_id = ed.id
JOIN enum_entity_permission_type ept ON epd.permission_type_id = ept.id
WHERE epd.position_id = ?
ORDER BY ed.name, ept.name;
```

### Get All Positions with Permission on Entity
```sql
SELECT
    pos.title as position_title,
    dept.name as department,
    team.name as team,
    ept.name as permission_type
FROM entity_permission_definition epd
JOIN popular_organization_position pos ON epd.position_id = pos.id
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN enum_entity_permission_type ept ON epd.permission_type_id = ept.id
JOIN entity_definition ed ON epd.entity_id = ed.id
WHERE ed.code = 'ORGANIZATION_VACANCY'
AND epd.is_allowed = 1
ORDER BY dept.name, team.name, pos.title;
```

### Check Organization Admin Permissions
```sql
-- Check if user is main admin (full permissions)
SELECT COUNT(*) FROM organization
WHERE id = ? AND main_admin_id = ?;

-- Check organization admin role
SELECT role FROM organization_admin
WHERE organization_id = ?
AND person_id = ?
AND is_active = 1
AND deleted_at IS NULL;
```

---

## Permission Matrix Example

### ORGANIZATION_VACANCY Entity

| Position | REQUEST | APPROVER | IMPLEMENTOR | REVIEWER |
|----------|---------|----------|-------------|----------|
| **HR Manager** | ✓ | ✓ | ✓ | ✓ |
| **Department Head** | ✓ | ✓ | - | ✓ |
| **Finance Manager** | - | ✓ | - | ✓ |
| **HR Coordinator** | - | - | ✓ | ✓ |
| **Employee** | - | - | - | ✓ |

**Implementation:**
```sql
-- HR Manager: Full access
INSERT INTO entity_permission_definition VALUES
  (?, 'ORGANIZATION_VACANCY', 'REQUEST', 'HR_MANAGER', 1),
  (?, 'ORGANIZATION_VACANCY', 'APPROVER', 'HR_MANAGER', 1),
  (?, 'ORGANIZATION_VACANCY', 'IMPLEMENTOR', 'HR_MANAGER', 1),
  (?, 'ORGANIZATION_VACANCY', 'REVIEWER', 'HR_MANAGER', 1);

-- Department Head: Request and Approve
INSERT INTO entity_permission_definition VALUES
  (?, 'ORGANIZATION_VACANCY', 'REQUEST', 'DEPT_HEAD', 1),
  (?, 'ORGANIZATION_VACANCY', 'APPROVER', 'DEPT_HEAD', 1),
  (?, 'ORGANIZATION_VACANCY', 'REVIEWER', 'DEPT_HEAD', 1);

-- Finance Manager: Approve only
INSERT INTO entity_permission_definition VALUES
  (?, 'ORGANIZATION_VACANCY', 'APPROVER', 'FINANCE_MGR', 1),
  (?, 'ORGANIZATION_VACANCY', 'REVIEWER', 'FINANCE_MGR', 1);

-- HR Coordinator: Implement only
INSERT INTO entity_permission_definition VALUES
  (?, 'ORGANIZATION_VACANCY', 'IMPLEMENTOR', 'HR_COORD', 1),
  (?, 'ORGANIZATION_VACANCY', 'REVIEWER', 'HR_COORD', 1);

-- Employee: Review only
INSERT INTO entity_permission_definition VALUES
  (?, 'ORGANIZATION_VACANCY', 'REVIEWER', 'EMPLOYEE', 1);
```

---

## Security Best Practices

### 1. Principle of Least Privilege
- Grant only necessary permissions
- Use specific permission types (not blanket "ADMIN")
- Review permissions regularly

### 2. Explicit Denials
- Use `is_allowed = 0` to explicitly deny
- Useful for exceptions (e.g., deny interns from sensitive data)

### 3. Organization Isolation
- Always check organization_id
- Prevent cross-organization permission checks
- Employment contracts are org-specific

### 4. Position-Level Granularity
- Define permissions at position level, not person level
- Automatically applies to all employees in that position
- Easier to manage and audit

### 5. Permission Caching
- Cache permission checks for performance
- Invalidate cache on employment contract changes
- Cache key: `org:person:entity:permission`

---

## Data Integrity Rules

1. **Unique Constraints:**
   - One permission definition per (entity, permission_type, position) combination
   - Enforced at database level

2. **Default Deny:**
   - If no permission definition exists → DENY
   - Must explicitly grant permissions

3. **Organization Context:**
   - All permission checks must include organization_id
   - User must be employed by organization to have position-based permissions

4. **Admin Bypass:**
   - Main admin and super admin bypass position checks
   - Enforced at application level

5. **Soft Deletes:**
   - Inactive employment contracts don't grant permissions
   - Check `status = 'ACTIVE'` and `deleted_at IS NULL`

---

## Adding New Permission Types

To add a custom permission type:

```sql
-- 1. Add to enum table
INSERT INTO enum_entity_permission_type (id, code, name, description)
VALUES (
    lower(hex(randomblob(16))),
    'CUSTOM_ACTION',
    'Custom Action',
    'Description of what this permission allows'
);

-- 2. Define which positions have this permission
INSERT INTO entity_permission_definition (
    id, entity_id, permission_type_id, position_id, is_allowed
) VALUES (
    lower(hex(randomblob(16))),
    (SELECT id FROM entity_definition WHERE code = 'MY_ENTITY'),
    (SELECT id FROM enum_entity_permission_type WHERE code = 'CUSTOM_ACTION'),
    (SELECT id FROM popular_organization_position WHERE code = 'MY_POSITION'),
    1
);

-- 3. Update application logic to check for this permission
```

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Organization Membership:** [/guides/features/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md](../../guides/features/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md)
- **Process Flow System:** [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Permissions & Security
