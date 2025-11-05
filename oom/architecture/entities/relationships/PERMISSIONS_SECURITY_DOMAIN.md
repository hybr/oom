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
├─ code* (Unique permission type code)
├─ name* (Display name)
└─ description? (Purpose and use case)
```

**Common Permission Type Codes:**
`REQUESTER`, `FEASIBLE_ANALYST`, `APPROVER`, `DESIGNER`, `DEVELOPER`, `TESTER`, `IMPLEMENTOR`, `SUPPORTER`, `REVIEWER`, `ADMINISTRATOR`

### Relationships
```
ENUM_ENTITY_PERMISSION_TYPE
  → ENTITY_PERMISSION_DEFINITION (1:Many)
  → PROCESS_NODE (1:Many) [task requirements]
```

### Standard Permission Types

#### Core Permission Types

| Code | Name | Description | Use Case |
|------|------|-------------|----------|
| **REQUESTER** | Requester | Can create/initiate requests | Start processes, create drafts, submit new items |
| **FEASIBLE_ANALYST** | Feasibility Analyst | Can analyze feasibility and viability | Assess technical/business feasibility, provide estimates |
| **APPROVER** | Approver | Can approve or reject | Review and approve/reject requests |
| **DESIGNER** | Designer | Can design solutions | Create technical/visual designs, specifications |
| **DEVELOPER** | Developer | Can develop/build | Write code, implement features |
| **TESTER** | Tester | Can test and validate | Quality assurance, testing, bug reporting |
| **IMPLEMENTOR** | Implementor | Can execute/implement | Publish, deploy, finalize, release to production |
| **SUPPORTER** | Supporter | Can provide support | Handle issues, provide maintenance, user support |
| **REVIEWER** | Reviewer | Can view and comment | Read-only review access |
| **ADMINISTRATOR** | Administrator | Full CRUD access | Entity administration |

#### Permission Type Categories

**1. Initiation & Planning:**
- `REQUESTER` - Start new work items
- `FEASIBLE_ANALYST` - Assess viability

**2. Approval & Decision:**
- `APPROVER` - Approve/reject decisions

**3. Design & Development:**
- `DESIGNER` - Create designs
- `DEVELOPER` - Build solutions
- `TESTER` - Quality assurance

**4. Deployment & Support:**
- `IMPLEMENTOR` - Deploy to production
- `SUPPORTER` - Ongoing support

**5. Oversight:**
- `REVIEWER` - Monitor and review
- `ADMINISTRATOR` - Full control

### Software Development Lifecycle Example

**Entity: FEATURE_REQUEST**

| Position | REQUESTER | FEASIBLE_ANALYST | APPROVER | DESIGNER | DEVELOPER | TESTER | IMPLEMENTOR | SUPPORTER | REVIEWER |
|----------|---------|------------------|----------|----------|-----------|--------|-------------|-----------|----------|
| **Product Manager** | ✓ | ✓ | ✓ | - | - | - | - | - | ✓ |
| **Technical Lead** | ✓ | ✓ | ✓ | ✓ | - | - | - | - | ✓ |
| **Designer** | - | - | - | ✓ | - | - | - | - | ✓ |
| **Senior Developer** | - | ✓ | - | - | ✓ | - | - | - | ✓ |
| **Developer** | - | - | - | - | ✓ | - | - | - | ✓ |
| **QA Engineer** | - | - | - | - | - | ✓ | - | - | ✓ |
| **DevOps Engineer** | - | - | - | - | - | - | ✓ | - | ✓ |
| **Support Engineer** | - | - | - | - | - | - | - | ✓ | ✓ |

**Workflow:**
```
1. Product Manager creates FEATURE_REQUEST (REQUEST)
   ↓
2. Technical Lead performs feasibility analysis (FEASIBLE_ANALYST)
   ↓
3. Product Manager approves for development (APPROVER)
   ↓
4. Designer creates UI/UX designs (DESIGNER)
   ↓
5. Senior Developer assigns to Developer (DEVELOPER)
   Developer implements the feature
   ↓
6. QA Engineer tests the feature (TESTER)
   ↓
7. DevOps Engineer deploys to production (IMPLEMENTOR)
   ↓
8. Support Engineer handles user issues (SUPPORTER)
   ↓
9. All roles can review progress (REVIEWER)
```

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

## Initializing Permission Types

### Standard Permission Types Setup

To initialize all standard permission types in your system:

```sql
-- Create standard permission types for full development lifecycle
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
  (lower(hex(randomblob(16))), 'ADMINISTRATIVE', 'Administrator', 'Full administrative access to all features');
```

**Note:** The `REQUESTER` permission type is the code used in the database, while "Requester" is the display name shown to users.

---

## Adding Custom Permission Types

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

**Last Updated:** 2025-11-05
**Domain:** Permissions & Security
