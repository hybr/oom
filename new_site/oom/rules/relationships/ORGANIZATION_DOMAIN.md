# Organization Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/rules/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Organization domain manages organizational structures, membership, infrastructure (branches, buildings, workstations), and admin permissions.

**Domain Code:** `ORGANIZATION`

**Core Entities:** 7
- ORGANIZATION
- ORGANIZATION_ADMIN
- ORGANIZATION_BRANCH
- ORGANIZATION_BUILDING
- WORKSTATION
- POSTAL_ADDRESS (shared with Geographic domain)
- EMPLOYMENT_CONTRACT (links to Hiring domain)

---

## Organization Hierarchy

```
ORGANIZATION (Root)
  ├── ORGANIZATION_ADMIN (Membership - 3 levels)
  ├── ORGANIZATION_BRANCH (Locations)
  │     └── ORGANIZATION_BUILDING
  │           └── WORKSTATION
  ├── POSTAL_ADDRESS (Addresses)
  ├── EMPLOYMENT_CONTRACT (Employees)
  └── ORGANIZATION_VACANCY (Open positions)
```

---

## 1. ORGANIZATION (Core Entity)

### Entity Structure
```
ORGANIZATION
├─ id* (PK)
├─ short_name*
├─ legal_category_id? (FK → PERSON)
├─ registration_number?
├─ tax_id?
├─ industry?
├─ description?
├─ logo_url?
├─ website?
├─ public_email_address?
├─ public_phone_address?
├─ main_admin_id* (FK → PERSON) [Owner]
├─ founded_date?
├─ employee_count?
└─ is_active*
```

### Relationships
```
ORGANIZATION
  ← PERSON (Many:1) [via main_admin_id] - Owner
  → ORGANIZATION_ADMIN (1:Many) - Admin members
  → ORGANIZATION_BRANCH (1:Many) - Physical branches
  → ORGANIZATION_BUILDING (1:Many) - Buildings/facilities
  → POSTAL_ADDRESS (1:Many) - Addresses
  → EMPLOYMENT_CONTRACT (1:Many) - Employees
  → ORGANIZATION_VACANCY (1:Many) - Job openings
  → TASK_FLOW_INSTANCE (1:Many) - Process instances
```

---

## 2. ORGANIZATION_ADMIN (Membership & Permissions)

### Entity Structure
```
ORGANIZATION_ADMIN
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ person_id* (FK → PERSON)
├─ role* [SUPER_ADMIN, ADMIN, MODERATOR]
├─ permissions? (JSON)
├─ appointed_by? (FK → PERSON)
├─ appointed_at*
├─ is_active*
└─ notes?
```

### Relationships
```
ORGANIZATION_ADMIN
  ← ORGANIZATION (Many:1)
  ← PERSON (Many:1) [as admin member]
  ← PERSON (Many:1) [as appointer]
```

### Permission Hierarchy
```
Level 1: MAIN_ADMIN (organization.main_admin_id)
  ↓ Full ownership, cannot be removed

Level 2: SUPER_ADMIN (organization_admin.role)
  ↓ Can transfer main_admin, manage all admins

Level 3: ADMIN (organization_admin.role)
  ↓ Can manage employees, view reports

Level 4: MODERATOR (organization_admin.role)
  ↓ Read-only access

Level 5: EMPLOYEE (employment_contract)
  ↓ Position-based permissions only
```

---

## 3. ORGANIZATION_BRANCH

### Entity Structure
```
ORGANIZATION_BRANCH
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ name*
├─ code*
├─ description?
├─ branch_type?
├─ manager_id? (FK → PERSON)
└─ postal_address_id? (FK → POSTAL_ADDRESS)
```

### Relationships
```
ORGANIZATION_BRANCH
  ← ORGANIZATION (Many:1)
  ← PERSON (Many:1) [as manager]
  ← POSTAL_ADDRESS (1:1) [Optional]
  → ORGANIZATION_BUILDING (1:Many)
```

---

## 4. ORGANIZATION_BUILDING

### Entity Structure
```
ORGANIZATION_BUILDING
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ branch_id? (FK → ORGANIZATION_BRANCH)
├─ name*
├─ code*
├─ building_type?
├─ total_floors?
├─ postal_address_id? (FK → POSTAL_ADDRESS)
└─ facilities?
```

### Relationships
```
ORGANIZATION_BUILDING
  ← ORGANIZATION (Many:1)
  ← ORGANIZATION_BRANCH (Many:1) [Optional]
  ← POSTAL_ADDRESS (1:1) [Optional]
  → WORKSTATION (1:Many)
```

**Note:** A building can exist without a branch (e.g., standalone office).

---

## 5. WORKSTATION

### Entity Structure
```
WORKSTATION
├─ id* (PK)
├─ organization_building_id* (FK → ORGANIZATION_BUILDING)
├─ workstation_code*
├─ floor_number?
├─ area_section?
├─ workstation_type?
├─ capacity?
└─ amenities?
```

### Relationships
```
WORKSTATION
  ← ORGANIZATION_BUILDING (Many:1)
  → ORGANIZATION_VACANCY_WORKSTATION (1:Many) - Assigned to vacancies
```

---

## Complete Organization Infrastructure

```
ORGANIZATION
  ↓
ORGANIZATION_BRANCH (Optional grouping)
  ↓
ORGANIZATION_BUILDING (Physical facility)
  ↓
WORKSTATION (Desk/cubicle/office)
  ↓
ORGANIZATION_VACANCY_WORKSTATION (Assignment to job opening)
```

**Example:**
```
Tech Solutions Inc (Organization)
  ├─ San Francisco Branch
  │    ├─ Main Office Building
  │    │    ├─ Workstation SF-101 (Floor 1)
  │    │    ├─ Workstation SF-102 (Floor 1)
  │    │    └─ Workstation SF-201 (Floor 2)
  │    └─ Warehouse Building
  │         └─ Workstation WH-001
  └─ New York Branch
       └─ NY Office Building
            └─ Workstation NY-301
```

---

## Three-Tier Membership Model

A person can relate to an organization in three ways:

### Tier 1: Main Admin (Owner)
```sql
-- Via organization.main_admin_id
SELECT * FROM organization
WHERE main_admin_id = ?;
```
- **Permissions:** Full ownership
- **Count:** Exactly 1 per organization
- **Transferable:** Yes (by current main_admin or super_admin)

### Tier 2: Organization Admin
```sql
-- Via organization_admin table
SELECT * FROM organization_admin
WHERE person_id = ?
AND is_active = 1;
```
- **Permissions:** Role-based (SUPER_ADMIN, ADMIN, MODERATOR)
- **Count:** 0 to many per organization
- **Revocable:** Yes (by main_admin or super_admin)

### Tier 3: Employee
```sql
-- Via employment_contract
SELECT * FROM employment_contract
WHERE employee_id = ?
AND status = 'ACTIVE';
```
- **Permissions:** Position-based only
- **Count:** 0 to many per organization
- **Managed by:** Admins with appropriate permissions

**A person can hold multiple tiers simultaneously!**

---

## Cross-Domain Relationships

### To Person Domain
```
ORGANIZATION ← PERSON (via main_admin_id)
ORGANIZATION_ADMIN ← PERSON (via person_id)
ORGANIZATION_BRANCH ← PERSON (via manager_id)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

### To Geographic Domain
```
ORGANIZATION → POSTAL_ADDRESS (1:Many)
ORGANIZATION_BRANCH → POSTAL_ADDRESS (1:1)
ORGANIZATION_BUILDING → POSTAL_ADDRESS (1:1)
```
See: [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)

### To Hiring Domain
```
ORGANIZATION → ORGANIZATION_VACANCY (1:Many)
ORGANIZATION → EMPLOYMENT_CONTRACT (1:Many)
WORKSTATION → ORGANIZATION_VACANCY_WORKSTATION (1:Many)
```
See: [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)

### To Process Flow Domain
```
ORGANIZATION → TASK_FLOW_INSTANCE (1:Many)
ORGANIZATION → PROCESS_FALLBACK_ASSIGNMENT (1:Many)
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

---

## Common Queries

### Get Organization with All Admins
```sql
-- Organization details
SELECT * FROM organization WHERE id = ?;

-- Main admin
SELECT p.* FROM person p
JOIN organization o ON o.main_admin_id = p.id
WHERE o.id = ?;

-- All admins
SELECT p.*, oa.role, oa.appointed_at
FROM organization_admin oa
JOIN person p ON oa.person_id = p.id
WHERE oa.organization_id = ?
AND oa.is_active = 1
ORDER BY
  CASE oa.role
    WHEN 'SUPER_ADMIN' THEN 1
    WHEN 'ADMIN' THEN 2
    WHEN 'MODERATOR' THEN 3
  END;
```

### Get Complete Infrastructure
```sql
SELECT
    b.name as branch_name,
    bld.name as building_name,
    w.workstation_code,
    w.floor_number,
    w.workstation_type
FROM organization o
LEFT JOIN organization_branch b ON b.organization_id = o.id
LEFT JOIN organization_building bld ON bld.organization_id = o.id
LEFT JOIN workstation w ON w.organization_building_id = bld.id
WHERE o.id = ?
ORDER BY b.name, bld.name, w.floor_number, w.workstation_code;
```

### Check User Permission Level
```sql
-- Check if user is main admin
SELECT COUNT(*) FROM organization
WHERE id = ? AND main_admin_id = ?;

-- Check admin role
SELECT role FROM organization_admin
WHERE organization_id = ? AND person_id = ?
AND is_active = 1;

-- Check if employee
SELECT COUNT(*) FROM employment_contract
WHERE organization_id = ? AND employee_id = ?
AND status = 'ACTIVE';
```

---

## Data Integrity Rules

1. **Main Admin Requirement:**
   - Every organization MUST have a main_admin_id
   - Main admin cannot be removed (must be transferred first)

2. **Admin Appointment:**
   - Only main_admin or super_admin can appoint other admins
   - Track appointer via appointed_by field

3. **Infrastructure Hierarchy:**
   - Workstations MUST belong to a building
   - Buildings CAN exist without a branch
   - Branches MUST belong to an organization

4. **Soft Deletes:**
   - All organization entities use soft deletes
   - Check `deleted_at IS NULL` in all queries

5. **Organization Isolation:**
   - Enforce organization_id in all multi-tenant queries
   - Prevent cross-organization data access

---

## Related Documentation

- **Entity Creation Rules:** [/rules/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Membership Guide:** [/guides/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md](../../guides/ORGANIZATION_MEMBERSHIP_PERMISSIONS.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Organization
