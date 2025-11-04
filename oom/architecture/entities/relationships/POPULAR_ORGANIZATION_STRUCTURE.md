# Popular Organization Structure - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Popular Organization Structure domain provides reference/master data for common organizational hierarchies: departments, teams, designations, and positions. These are reusable templates that multiple organizations can reference.

**Domain Code:** `POPULAR_ORG_STRUCTURE`

**Core Entities:** 7
- POPULAR_ORGANIZATION_DEPARTMENTS
- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
- POPULAR_ORGANIZATION_DESIGNATION
- POPULAR_ORGANIZATION_POSITION
- POPULAR_ORGANIZATION_POSITION_SKILL
- POPULAR_ORGANIZATION_POSITION_EDUCATION
- POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT

**Purpose:** Standardized, reusable organizational structure templates

---

## Hierarchy Structure

```
POPULAR_ORGANIZATION_DEPARTMENTS (e.g., Engineering, Sales, HR)
  ↓ (1:Many)
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (e.g., Backend, Frontend, DevOps)
  ↓ (Many:1)
POPULAR_ORGANIZATION_DESIGNATION (e.g., Staff, Manager, Director, CEO)
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

Generic designations applicable across all departments and roles. These are reusable, department-agnostic titles that can be combined with departments and teams to form specific positions.

### Entity Structure
```
POPULAR_ORGANIZATION_DESIGNATION
├─ id* (PK)
├─ name*
├─ code*
├─ description?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DESIGNATION
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Examples
```
Generic Designations:
  ├─ Staff
  ├─ Junior Staff
  ├─ Senior Staff
  ├─ Associate
  ├─ Senior Associate
  ├─ Lead
  ├─ Senior Lead
  ├─ Specialist
  ├─ Senior Specialist
  ├─ Coordinator
  ├─ Project Manager
  ├─ Manager
  ├─ Senior Manager
  ├─ Director
  ├─ Senior Director
  ├─ Vice President
  ├─ Senior Vice President
  ├─ Chief Executive Officer
  ├─ Chief Operating Officer
  ├─ Chief Financial Officer
  ├─ Chief Technology Officer
  ├─ Chief Information Officer
  ├─ Chief Human Resources Officer
  ├─ Board Member
  ├─ Chairman
  └─ President
```

**Usage Pattern:**
```
Designation + Department + Team = Position

Examples:
- "Senior Staff" + "Engineering" + "Backend Team" = Senior Backend Staff
- "Manager" + "Sales" + "Inside Sales Team" = Inside Sales Manager
- "Director" + "Human Resources" + (no team) = HR Director
- "Chief Technology Officer" + "Engineering" + (no team) = Chief Technology Officer
```

---

## 4. POPULAR_ORGANIZATION_POSITION

Defines organizational positions by combining department, team (optional), and designation. The position title is automatically derived from these components.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION
├─ id* (PK)
├─ code*
├─ description?
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ team_id? (FK → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS)
├─ designation_id* (FK → POPULAR_ORGANIZATION_DESIGNATION)
├─ responsibilities?
├─ experience_years?
└─ is_active*
```

**Note:** The `title` is derived from: `Department.name + Team.name (if exists) + Designation.name`

### Relationships
```
POPULAR_ORGANIZATION_POSITION
  ← POPULAR_ORGANIZATION_DEPARTMENTS (Many:1)
  ← POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (Many:1) [Optional]
  ← POPULAR_ORGANIZATION_DESIGNATION (Many:1)
  → POPULAR_ORGANIZATION_POSITION_SKILL (1:Many)
  → POPULAR_ORGANIZATION_POSITION_EDUCATION (1:Many)
  → ORGANIZATION_VACANCY (1:Many)
  → EMPLOYMENT_CONTRACT (1:Many)
  → ENTITY_PERMISSION_DEFINITION (1:Many)
  → PROCESS_NODE (1:Many)
```

---

## 5. POPULAR_ORGANIZATION_POSITION_SKILL

Junction table linking positions to required skills.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION_SKILL
├─ id* (PK)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ skill_id* (FK → POPULAR_SKILL)
├─ proficiency_level? (FK → ENUM_SKILL_LEVEL)
├─ is_required*
└─ priority? [1=Critical, 2=Important, 3=Nice-to-have]
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION_SKILL
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← POPULAR_SKILL (Many:1)
  ← ENUM_SKILL_LEVEL (Many:1) [Optional]
```

### Purpose
Defines the skills required for a position with optional proficiency levels and priorities.

---

## 6. POPULAR_ORGANIZATION_POSITION_EDUCATION

Defines education level requirements for positions.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION_EDUCATION
├─ id* (PK)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ education_level_id* (FK → ENUM_EDUCATION_LEVELS)
├─ is_required*
└─ description?
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION_EDUCATION
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← ENUM_EDUCATION_LEVELS (Many:1)
  → POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT (1:Many)
```

### Purpose
Specifies minimum education levels required for a position (e.g., Bachelor's degree, Master's degree).

---

## 7. POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT

Junction table linking position education requirements to specific subjects/fields of study.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT
├─ id* (PK)
├─ position_education_id* (FK → POPULAR_ORGANIZATION_POSITION_EDUCATION)
├─ subject_id* (FK → POPULAR_EDUCATION_SUBJECT)
└─ is_preferred* [Required vs Preferred subject]
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT
  ← POPULAR_ORGANIZATION_POSITION_EDUCATION (Many:1)
  ← POPULAR_EDUCATION_SUBJECT (Many:1)
```

### Purpose
Specifies which subjects/fields of study are required or preferred for the education requirement (e.g., Computer Science, Engineering, Business Administration).

---

### Position Composition
```
Position = Department + Team (optional) + Designation

Examples:
1. Engineering → Backend Team → Senior Staff
   = "Senior Backend Staff"

2. Sales → Inside Sales Team → Manager
   = "Inside Sales Manager"

3. HR → (no team) → Director
   = "HR Director"

4. (no department) → (no team) → Chief Technology Officer
   = "Chief Technology Officer"
```

### Title Derivation Logic
```sql
-- Formula for deriving position title:
CASE
    WHEN team_id IS NOT NULL THEN
        designation.name || ' ' || team.name || ' ' || department.name
    WHEN department_id IS NOT NULL THEN
        designation.name || ' ' || department.name
    ELSE
        designation.name
END

-- Examples:
-- Senior Staff + Backend Team + Engineering = "Senior Staff Backend Team Engineering"
-- Director + NULL + Sales = "Director Sales"
-- Chief Executive Officer + NULL + NULL = "Chief Executive Officer"
```

---

## Complete Hierarchy Example

```
POPULAR_ORGANIZATION_DEPARTMENTS: Engineering
  ↓
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS: Backend Team
  ↓
POPULAR_ORGANIZATION_DESIGNATION: Senior Staff
  ↓
POPULAR_ORGANIZATION_POSITION: Senior Backend Staff
  ├─ code: "ENG-BACKEND-SENIOR-STAFF"
  ├─ title: "Senior Backend Staff" (derived from components)
  ├─ responsibilities: "Design APIs, mentor juniors..."
  └─ experience_years: 5

  Required Skills (via POPULAR_ORGANIZATION_POSITION_SKILL):
  ├─ PHP (Critical, Expert level)
  ├─ MySQL (Critical, Advanced level)
  ├─ Redis (Important, Intermediate level)
  └─ Docker (Nice-to-have, Intermediate level)

  Education Requirements (via POPULAR_ORGANIZATION_POSITION_EDUCATION):
  └─ Bachelor's Degree or higher
      └─ Subjects: Computer Science, Software Engineering (preferred)

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

### To Person & Identity Domain
```
POPULAR_ORGANIZATION_POSITION_SKILL → POPULAR_SKILL (Many:1)
POPULAR_ORGANIZATION_POSITION_SKILL → ENUM_SKILL_LEVEL (Many:1)
POPULAR_ORGANIZATION_POSITION_EDUCATION → ENUM_EDUCATION_LEVELS (Many:1)
POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT → POPULAR_EDUCATION_SUBJECT (Many:1)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

**Rationale:** Positions reference the same skill and education taxonomies used for person profiles, enabling direct matching of candidate qualifications to job requirements.

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
      → Position: "Senior Backend Staff" (Engineering → Backend Team → Senior Staff)
          → Has permission: APPROVER on ORGANIZATION_VACANCY
          → Can be assigned: "HR Review" tasks in vacancy creation process
```

---

## Common Queries

### Get Complete Position Details
```sql
-- Get position with derived title
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    dept.name as department,
    team.name as team,
    desig.name as designation,
    desig.code as designation_code,
    pos.responsibilities,
    pos.experience_years
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.id = ?
AND pos.is_active = 1;

-- Get position required skills
SELECT
    ps.name as skill_name,
    esl.name as proficiency_level,
    pops.is_required,
    pops.priority
FROM popular_organization_position_skill pops
JOIN popular_skill ps ON pops.skill_id = ps.id
LEFT JOIN enum_skill_level esl ON pops.proficiency_level = esl.id
WHERE pops.position_id = ?
ORDER BY pops.priority, pops.is_required DESC;

-- Get position education requirements
SELECT
    eel.name as education_level,
    pope.is_required,
    pope.description
FROM popular_organization_position_education pope
JOIN enum_education_level eel ON pope.education_level_id = eel.id
WHERE pope.position_id = ?;

-- Get position education subjects
SELECT
    eel.name as education_level,
    pes.name as subject_name,
    popes.is_preferred
FROM popular_organization_position_education pope
JOIN popular_organization_position_education_subject popes ON pope.id = popes.position_education_id
JOIN popular_education_subject pes ON popes.subject_id = pes.id
JOIN enum_education_level eel ON pope.education_level_id = eel.id
WHERE pope.position_id = ?
ORDER BY eel.code, popes.is_preferred DESC;
```

### Get All Positions in Department
```sql
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name
        ELSE desig.name
    END as derived_title,
    team.name as team,
    desig.name as designation,
    desig.code as designation_code
FROM popular_organization_position pos
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.department_id = ?
AND pos.is_active = 1
ORDER BY desig.name, team.name;
```

### Find Positions by Designation
```sql
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    dept.name as department,
    team.name as team,
    desig.name as designation
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE desig.code = 'MANAGER'
AND pos.is_active = 1;
```

### Get Teams in Department
```sql
SELECT * FROM popular_organization_department_teams
WHERE department_id = ?
AND is_active = 1
ORDER BY name;
```

### Find Positions Requiring Specific Skill
```sql
SELECT DISTINCT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    ps.name as skill_name,
    esl.name as proficiency_level,
    pops.is_required,
    pops.priority
FROM popular_organization_position pos
JOIN popular_organization_position_skill pops ON pos.id = pops.position_id
JOIN popular_skill ps ON pops.skill_id = ps.id
LEFT JOIN enum_skill_level esl ON pops.proficiency_level = esl.id
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE ps.name = 'PHP'
  AND pops.is_required = 1
  AND pos.is_active = 1;
```

### Find Positions by Education Level
```sql
SELECT DISTINCT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    eel.name as education_level,
    pope.is_required
FROM popular_organization_position pos
JOIN popular_organization_position_education pope ON pos.id = pope.position_id
JOIN enum_education_level eel ON pope.education_level_id = eel.id
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE eel.code IN ('BACHELOR', 'MASTER')
  AND pos.is_active = 1;
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
WHERE code = 'SENIOR_STAFF';

-- Step 4: Create position
INSERT INTO popular_organization_position (
    id, code,
    department_id, team_id, designation_id,
    responsibilities, experience_years, is_active
) VALUES (
    ?,
    'ENG-BACKEND-SENIOR-STAFF',
    ?, ?, ?,
    'Design APIs, mentor juniors, code reviews',
    5,
    1
);

-- Step 5: Add required skills
INSERT INTO popular_organization_position_skill (
    id, position_id, skill_id, proficiency_level, is_required, priority
) VALUES
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'PHP'),
     (SELECT id FROM enum_skill_level WHERE code = 'EXPERT'), 1, 1),
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'MySQL'),
     (SELECT id FROM enum_skill_level WHERE code = 'ADVANCED'), 1, 1),
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'Redis'),
     (SELECT id FROM enum_skill_level WHERE code = 'INTERMEDIATE'), 1, 2),
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'Docker'),
     (SELECT id FROM enum_skill_level WHERE code = 'INTERMEDIATE'), 0, 3);

-- Step 6: Add education requirements
INSERT INTO popular_organization_position_education (
    id, position_id, education_level_id, is_required, description
) VALUES (
    ?,
    ?,
    (SELECT id FROM enum_education_level WHERE code = 'BACHELOR'),
    1,
    'Bachelor degree or higher in relevant field'
);

-- Step 7: Add preferred education subjects
INSERT INTO popular_organization_position_education_subject (
    id, position_education_id, subject_id, is_preferred
) VALUES
    (?, ?, (SELECT id FROM popular_education_subject WHERE name = 'Computer Science'), 0),
    (?, ?, (SELECT id FROM popular_education_subject WHERE name = 'Software Engineering'), 1);
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
   - Example: "HR Director" (no specific team)
   - Executive positions may have neither department nor team

4. **Hierarchical Consistency:**
   - If position has a team, the team must belong to the same department

5. **Title Derivation:**
   - Position title is NOT stored, it is derived from components
   - Applications should compute title using: Designation + Team + Department
   - This ensures consistency and eliminates duplicate data

6. **Skills and Education:**
   - A position can have multiple required/preferred skills
   - Skills can have different priority levels (Critical, Important, Nice-to-have)
   - A position can have multiple education requirements (e.g., Bachelor OR Master)
   - Education requirements can specify preferred subjects

7. **Cascade Deletion:**
   - Deleting a position should cascade to position_skill, position_education
   - Deleting position_education should cascade to position_education_subject
   - Do not delete positions that are referenced by active vacancies or contracts

---

## Benefits of Normalized Structure

### 1. **No Data Duplication**
- Title is computed, not stored → eliminates inconsistency
- Skills are in separate table → easy to update/add/remove
- Education requirements are normalized → flexible matching

### 2. **Powerful Querying**
```sql
-- Find all positions requiring "Python" skill
-- Find all positions requiring Bachelor's in Computer Science
-- Find positions with specific skill proficiency levels
-- Compare positions by education requirements
```

### 3. **Flexible Requirements**
- Position can require multiple skills with different priorities
- Position can accept multiple education levels (Bachelor OR Master)
- Can specify preferred vs required subjects
- Easy to add/remove requirements without schema changes

### 4. **Better Matching**
```sql
-- Match candidates to positions based on:
--   ✓ Skills they have vs skills position requires
--   ✓ Education level and subjects
--   ✓ Proficiency levels
--   ✓ Required vs preferred criteria
```

### 5. **Consistency**
- Title always matches components (Department + Team + Designation)
- No risk of title being out of sync with structure
- Easier to maintain and update

---

## Common Position Templates

Using generic designations combined with departments and teams:

### Engineering Department
```
Backend Team:
  - Junior Staff (Engineering → Backend Team)
  - Staff (Engineering → Backend Team)
  - Senior Staff (Engineering → Backend Team)
  - Lead (Engineering → Backend Team)
  - Manager (Engineering → Backend Team)

Frontend Team:
  - Junior Staff (Engineering → Frontend Team)
  - Staff (Engineering → Frontend Team)
  - Senior Staff (Engineering → Frontend Team)
  - Lead (Engineering → Frontend Team)

Department Level:
  - Director (Engineering)
  - Senior Director (Engineering)
  - Vice President (Engineering)
  - Chief Technology Officer
```

### Sales Department
```
Inside Sales Team:
  - Staff (Sales → Inside Sales Team)
  - Senior Staff (Sales → Inside Sales Team)
  - Lead (Sales → Inside Sales Team)
  - Manager (Sales → Inside Sales Team)

Field Sales Team:
  - Staff (Sales → Field Sales Team)
  - Manager (Sales → Field Sales Team)

Department Level:
  - Director (Sales)
  - Vice President (Sales)
```

### Human Resources
```
(No specific teams)
  - Coordinator (Human Resources)
  - Specialist (Human Resources)
  - Senior Specialist (Human Resources)
  - Manager (Human Resources)
  - Director (Human Resources)
  - Chief Human Resources Officer
```

### Executive Level
```
(No department/team - organization-wide)
  - Chief Executive Officer
  - Chief Operating Officer
  - Chief Financial Officer
  - President
  - Board Member
  - Chairman
```

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Hiring Guide:** [/guides/features/VACANCY_CREATION_PROCESS.md](../../guides/features/VACANCY_CREATION_PROCESS.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-11-01
**Domain:** Popular Organization Structure (Reference Data)
