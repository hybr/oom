# Popular Organization Structure - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/rules/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Popular Organization Structure domain provides reference/master data for common organizational hierarchies: departments, teams, designations, and positions. These are reusable templates that multiple organizations can reference.

**Domain Code:** `POPULAR_ORG_STRUCTURE`

**Core Entities:** 4
- POPULAR_ORGANIZATION_DEPARTMENTS
- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
- POPULAR_ORGANIZATION_DESIGNATION
- POPULAR_ORGANIZATION_POSITION

**Purpose:** Standardized, reusable organizational structure templates

---

## Hierarchy Structure

```
POPULAR_ORGANIZATION_DEPARTMENTS (e.g., Engineering, Sales, HR)
  ↓ (1:Many)
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (e.g., Backend, Frontend, DevOps)
  ↓ (Many:1)
POPULAR_ORGANIZATION_DESIGNATION (e.g., Junior, Senior, Lead, Manager)
  ↓
POPULAR_ORGANIZATION_POSITION (Combination of Dept + Team + Designation)
  ↓
Used by: ORGANIZATION_VACANCY, EMPLOYMENT_CONTRACT, PROCESS_NODE
```

---

## 1. POPULAR_ORGANIZATION_DEPARTMENTS

### Entity Structure
```
POPULAR_ORGANIZATION_DEPARTMENTS
├─ id* (PK)
├─ name*
├─ code*
├─ description?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DEPARTMENTS
  → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (1:Many)
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Examples
```
Engineering
Sales
Marketing
Human Resources
Finance
Operations
Customer Support
```

---

## 2. POPULAR_ORGANIZATION_DEPARTMENT_TEAMS

### Entity Structure
```
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
├─ id* (PK)
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ name*
├─ code*
├─ description?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
  ← POPULAR_ORGANIZATION_DEPARTMENTS (Many:1)
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Examples
```
Engineering Department:
  ├─ Backend Team
  ├─ Frontend Team
  ├─ Mobile Team
  ├─ DevOps Team
  └─ QA Team

Sales Department:
  ├─ Inside Sales Team
  ├─ Field Sales Team
  └─ Sales Operations Team
```

---

## 3. POPULAR_ORGANIZATION_DESIGNATION

### Entity Structure
```
POPULAR_ORGANIZATION_DESIGNATION
├─ id* (PK)
├─ name*
├─ code*
├─ description?
├─ level? [Entry, Mid, Senior, Lead, Manager, Director, VP, C-Level]
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DESIGNATION
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Examples
```
Technical Track:
  ├─ Junior Developer (Entry)
  ├─ Developer (Mid)
  ├─ Senior Developer (Senior)
  ├─ Lead Developer (Lead)
  ├─ Engineering Manager (Manager)
  ├─ Director of Engineering (Director)
  └─ VP of Engineering (VP)

Management Track:
  ├─ Associate (Entry)
  ├─ Specialist (Mid)
  ├─ Senior Specialist (Senior)
  ├─ Team Lead (Lead)
  └─ Manager (Manager)
```

---

## 4. POPULAR_ORGANIZATION_POSITION

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION
├─ id* (PK)
├─ title*
├─ code*
├─ description?
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ team_id? (FK → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS)
├─ designation_id* (FK → POPULAR_ORGANIZATION_DESIGNATION)
├─ responsibilities?
├─ required_skills?
├─ experience_years?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION
  ← POPULAR_ORGANIZATION_DEPARTMENTS (Many:1)
  ← POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (Many:1) [Optional]
  ← POPULAR_ORGANIZATION_DESIGNATION (Many:1)
  → ORGANIZATION_VACANCY (1:Many)
  → EMPLOYMENT_CONTRACT (1:Many)
  → ENTITY_PERMISSION_DEFINITION (1:Many)
  → PROCESS_NODE (1:Many)
```

### Position Composition
```
Position = Department + Team (optional) + Designation

Examples:
1. Engineering → Backend Team → Senior Developer
   = "Senior Backend Developer"

2. Sales → Inside Sales Team → Manager
   = "Inside Sales Manager"

3. HR → (no team) → Specialist
   = "HR Specialist"
```

---

## Complete Hierarchy Example

```
POPULAR_ORGANIZATION_DEPARTMENTS: Engineering
  ↓
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS: Backend Team
  ↓
POPULAR_ORGANIZATION_DESIGNATION: Senior Developer
  ↓
POPULAR_ORGANIZATION_POSITION: Senior Backend Developer
  ├─ title: "Senior Backend Developer"
  ├─ code: "ENG-BACKEND-SENIOR-DEV"
  ├─ responsibilities: "Design APIs, mentor juniors..."
  ├─ required_skills: "PHP, MySQL, Redis..."
  └─ experience_years: 5

  Used by:
  ├─ ORGANIZATION_VACANCY (Job posting)
  ├─ EMPLOYMENT_CONTRACT (Employee assigned)
  ├─ ENTITY_PERMISSION_DEFINITION (Position permissions)
  └─ PROCESS_NODE (Task assignment)
```

---

## Cross-Domain Relationships

### To Hiring Domain
```
POPULAR_ORGANIZATION_POSITION → ORGANIZATION_VACANCY (1:Many)
POPULAR_ORGANIZATION_POSITION → EMPLOYMENT_CONTRACT (1:Many)
```
See: [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)

### To Process Flow Domain
```
POPULAR_ORGANIZATION_POSITION → PROCESS_NODE (1:Many) [task assignment]
POPULAR_ORGANIZATION_POSITION → PROCESS_FALLBACK_ASSIGNMENT (1:Many)
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

### To Permissions Domain
```
POPULAR_ORGANIZATION_POSITION → ENTITY_PERMISSION_DEFINITION (1:Many)
```
See: [PERMISSIONS_SECURITY_DOMAIN.md](PERMISSIONS_SECURITY_DOMAIN.md)

---

## Why "Popular" Organization Structure?

**Design Philosophy:**
- **Reusable Templates:** Organizations don't reinvent the wheel
- **Standardization:** Common industry-standard positions
- **Multi-Tenant:** Multiple organizations can reference same positions
- **Extensible:** Organizations can add custom positions

**vs. Organization-Specific Structure:**
```
❌ Each organization defines "Senior Developer" separately
   → Redundant data
   → Inconsistent naming
   → Hard to compare across organizations

✅ Use POPULAR_ORGANIZATION_POSITION
   → Single source of truth
   → Consistent naming
   → Easy cross-organization analytics
   → Organizations can still add custom positions if needed
```

---

## Position Resolution Chain

The system uses this hierarchy to resolve who can do what:

```
1. PERSON (Who)
   ↓
2. EMPLOYMENT_CONTRACT (Their job)
   ↓
3. POPULAR_ORGANIZATION_POSITION (Their position)
   ↓
4. ENTITY_PERMISSION_DEFINITION (What they can do)
   ↓
5. PROCESS_NODE (Tasks they can be assigned)
```

**Example:**
```
John Smith (PERSON)
  → Employment Contract #12345
      → Position: "Senior Backend Developer"
          → Has permission: APPROVER on ORGANIZATION_VACANCY
          → Can be assigned: "HR Review" tasks in vacancy creation process
```

---

## Common Queries

### Get Complete Position Details
```sql
SELECT
    pos.title,
    pos.code,
    dept.name as department,
    team.name as team,
    desig.name as designation,
    desig.level,
    pos.required_skills,
    pos.experience_years
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.id = ?
AND pos.is_active = 1;
```

### Get All Positions in Department
```sql
SELECT
    pos.title,
    team.name as team,
    desig.name as designation,
    desig.level
FROM popular_organization_position pos
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.department_id = ?
AND pos.is_active = 1
ORDER BY desig.level, team.name, pos.title;
```

### Find Positions by Designation Level
```sql
SELECT
    pos.title,
    dept.name as department,
    team.name as team
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE desig.level = 'Senior'
AND pos.is_active = 1;
```

### Get Teams in Department
```sql
SELECT * FROM popular_organization_department_teams
WHERE department_id = ?
AND is_active = 1
ORDER BY name;
```

---

## Creating New Positions

### Pattern: Build from Components
```sql
-- Step 1: Find department
SELECT id FROM popular_organization_departments
WHERE code = 'ENG';

-- Step 2: Find team (optional)
SELECT id FROM popular_organization_department_teams
WHERE department_id = ? AND code = 'BACKEND';

-- Step 3: Find designation
SELECT id FROM popular_organization_designation
WHERE code = 'SENIOR';

-- Step 4: Create position
INSERT INTO popular_organization_position (
    id, title, code,
    department_id, team_id, designation_id,
    responsibilities, required_skills, experience_years
) VALUES (
    ?,
    'Senior Backend Developer',
    'ENG-BACKEND-SENIOR-DEV',
    ?, ?, ?,
    'Design APIs, mentor juniors, code reviews',
    'PHP, MySQL, Redis, Docker',
    5
);
```

---

## Data Integrity Rules

1. **Unique Codes:**
   - Department codes, team codes, designation codes must be unique
   - Position codes should be unique (pattern: DEPT-TEAM-DESIGNATION)

2. **Active Status:**
   - Inactive positions cannot be assigned to new vacancies
   - Existing contracts remain valid even if position becomes inactive

3. **Team Optional:**
   - Positions can exist without a team (department-level positions)
   - Example: "HR Manager" (no specific team)

4. **Hierarchical Consistency:**
   - If position has a team, the team must belong to the same department

---

## Common Position Templates

### Engineering Department
```
Backend Team:
  - Junior Backend Developer
  - Backend Developer
  - Senior Backend Developer
  - Lead Backend Developer

Frontend Team:
  - Junior Frontend Developer
  - Frontend Developer
  - Senior Frontend Developer
  - Lead Frontend Developer

DevOps Team:
  - DevOps Engineer
  - Senior DevOps Engineer
  - DevOps Team Lead
```

### Sales Department
```
Inside Sales Team:
  - Sales Development Representative (SDR)
  - Account Executive
  - Senior Account Executive

Field Sales Team:
  - Territory Manager
  - Regional Sales Manager
```

### Human Resources
```
(No specific teams)
  - HR Coordinator
  - HR Specialist
  - Senior HR Specialist
  - HR Manager
  - HR Director
```

---

## Related Documentation

- **Entity Creation Rules:** [/rules/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Hiring Guide:** [/guides/VACANCY_CREATION_PROCESS.md](../../guides/VACANCY_CREATION_PROCESS.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Popular Organization Structure (Reference Data)
