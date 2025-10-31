# Organization Membership & Permissions System

**Version**: 1.0
**Date**: 2025-01-23
**Status**: Active

---

## Table of Contents

1. [Overview](#overview)
2. [Three Membership Types](#three-membership-types)
3. [Permission Hierarchy](#permission-hierarchy)
4. [Database Schema](#database-schema)
5. [PHP API Reference](#php-api-reference)
6. [SQL Query Patterns](#sql-query-patterns)
7. [REST API Integration](#rest-api-integration)
8. [UI Implementation](#ui-implementation)
9. [Security Best Practices](#security-best-practices)
10. [Migration Guide](#migration-guide)
11. [Troubleshooting](#troubleshooting)

---

## Overview

The system supports **three distinct ways** a user (person) can belong to an organization:

```
┌─────────────────────────────────────────────────────────────┐
│                    ORGANIZATION                              │
│  ┌───────────────────────────────────────────────────────┐  │
│  │  1. Main Admin (main_admin_id)                        │  │
│  │     - Single owner with full control                  │  │
│  │     - Stored in ORGANIZATION table                    │  │
│  └───────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌───────────────────────────────────────────────────────┐  │
│  │  2. Organization Admins (ORGANIZATION_ADMIN)          │  │
│  │     - Multiple admins with role-based access          │  │
│  │     - Roles: SUPER_ADMIN, ADMIN, MODERATOR            │  │
│  └───────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌───────────────────────────────────────────────────────┐  │
│  │  3. Employees (EMPLOYMENT_CONTRACT)                   │  │
│  │     - Position-based permissions                      │  │
│  │     - Controlled by ENTITY_PERMISSION_DEFINITION      │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### Key Principles

1. **Hierarchical Permissions**: Main Admin > Org Admin > Employee
2. **Multiple Memberships**: A person can be both admin AND employee
3. **Highest Permission Wins**: If user has multiple roles, highest applies
4. **Position-Based Security**: Employees have granular, position-specific permissions

---

## Three Membership Types

### 1. Main Admin (Primary Owner)

**Table**: `ORGANIZATION.main_admin_id`
**Access Level**: **FULL** (All operations, including ownership transfer)

**Characteristics**:
- Single person per organization
- Cannot be removed (only transferred)
- Automatically has SUPER_ADMIN privileges
- Can manage all admins and employees
- Can transfer ownership to another person

**When Created**:
- Set when organization is created
- Can be transferred via `transfer_main_admin()` API

**Database Check**:
```sql
SELECT * FROM organization
WHERE main_admin_id = :person_id
  AND id = :organization_id
  AND deleted_at IS NULL;
```

**Use Cases**:
- Founder/owner of the organization
- Primary account holder
- Ultimate authority for all decisions

---

### 2. Organization Admin

**Table**: `ORGANIZATION_ADMIN`
**Access Level**: **Role-Based** (SUPER_ADMIN, ADMIN, MODERATOR)

#### Role Definitions

| Role | Level | Can Manage Admins | Can Manage Employees | Can Transfer Ownership | Entity Access |
|------|-------|-------------------|----------------------|------------------------|---------------|
| **SUPER_ADMIN** | 2nd Highest | ✅ All (except main) | ✅ All | ✅ Yes | ✅ Full |
| **ADMIN** | Mid | ❌ No | ✅ All | ❌ No | ✅ Full |
| **MODERATOR** | Low | ❌ No | ❌ No | ❌ No | 👁️ Read-only |

**Attributes**:
```
organization_id  - Which organization
person_id        - Who is the admin
role             - SUPER_ADMIN, ADMIN, MODERATOR
permissions      - JSON for additional custom permissions
appointed_by     - Who added this admin
appointed_at     - When appointed
is_active        - Can be deactivated
notes            - Optional appointment notes
```

**When Created**:
- Added by Main Admin or SUPER_ADMIN
- Via API endpoint `/api/organization/admin/add.php`

**Database Check**:
```sql
SELECT * FROM organization_admin
WHERE person_id = :person_id
  AND organization_id = :organization_id
  AND is_active = 1
  AND deleted_at IS NULL;
```

**Use Cases**:
- **SUPER_ADMIN**: CEO, Co-founder, Operations Director
- **ADMIN**: Department Heads, HR Managers, IT Admins
- **MODERATOR**: Auditors, Consultants, Read-only observers

---

### 3. Employee (Position-Based)

**Table**: `EMPLOYMENT_CONTRACT`
**Access Level**: **Position-Based** (via ENTITY_PERMISSION_DEFINITION)

**Characteristics**:
- Has a specific job position
- Permissions defined by position type
- Can perform only actions their position allows
- Most granular permission level

**Attributes**:
```
organization_id  - Which organization
employee_id      - Who is the employee (person_id)
position_id      - Reference to POPULAR_ORGANIZATION_POSITION
job_title        - Job title string
status           - ACTIVE, TERMINATED, SUSPENDED
start_date       - Employment start
end_date         - Employment end (if applicable)
```

**When Created**:
- Hired through vacancy application process
- Manual employment contract creation
- Via HR system integration

**Database Check**:
```sql
SELECT * FROM employment_contract
WHERE employee_id = :person_id
  AND organization_id = :organization_id
  AND status = 'ACTIVE'
  AND deleted_at IS NULL;
```

**Permission Resolution**:
```sql
-- Get allowed actions for employee's position
SELECT DISTINCT pt.code as permission_type
FROM employment_contract ec
JOIN entity_permission_definition epd ON epd.position_id = ec.position_id
JOIN enum_entity_permission_type pt ON pt.id = epd.permission_type_id
WHERE ec.employee_id = :person_id
  AND ec.organization_id = :organization_id
  AND ec.status = 'ACTIVE'
  AND epd.is_allowed = 1
  AND epd.entity_id = :entity_id;
```

**Use Cases**:
- Regular employees: Developers, Accountants, Sales reps
- Contract workers
- Interns
- Anyone with a job position

---

## Permission Hierarchy

### Visual Representation

```
┌─────────────────────────────────────────────────────────┐
│  Level 1: MAIN_ADMIN (Highest)                         │
│  ├─ Full ownership                                      │
│  ├─ Transfer ownership                                  │
│  ├─ CRUD all admins                                     │
│  └─ All employee permissions                            │
├─────────────────────────────────────────────────────────┤
│  Level 2: SUPER_ADMIN (Organization Admin)             │
│  ├─ Transfer main_admin_id                              │
│  ├─ CRUD other admins (not main)                        │
│  └─ All employee permissions                            │
├─────────────────────────────────────────────────────────┤
│  Level 3: ADMIN (Organization Admin)                   │
│  ├─ Manage employees                                    │
│  ├─ Cannot manage admins                                │
│  └─ All employee permissions                            │
├─────────────────────────────────────────────────────────┤
│  Level 4: MODERATOR (Organization Admin)               │
│  ├─ Read-only access                                    │
│  └─ Cannot modify data                                  │
├─────────────────────────────────────────────────────────┤
│  Level 5: EMPLOYEE (Lowest)                            │
│  └─ Position-specific permissions only                  │
└─────────────────────────────────────────────────────────┘
```

### Permission Matrix

| Action | Main Admin | SUPER_ADMIN | ADMIN | MODERATOR | EMPLOYEE |
|--------|------------|-------------|-------|-----------|----------|
| View org data | ✅ | ✅ | ✅ | ✅ | ✅* |
| Edit org profile | ✅ | ✅ | ✅ | ❌ | ❌ |
| Add/remove admins | ✅ | ✅ | ❌ | ❌ | ❌ |
| Transfer ownership | ✅ | ✅ | ❌ | ❌ | ❌ |
| Hire employees | ✅ | ✅ | ✅ | ❌ | ❌ |
| Fire employees | ✅ | ✅ | ✅ | ❌ | ❌ |
| Start processes | ✅ | ✅ | ✅ | ❌ | ✅* |
| Approve requests | ✅ | ✅ | ✅ | ❌ | ✅* |
| Delete organization | ✅ | ❌ | ❌ | ❌ | ❌ |

*\*Position-based - depends on ENTITY_PERMISSION_DEFINITION*

### Multiple Memberships

A person can have multiple membership types simultaneously:

**Example**: John Smith
- Main Admin of "Tech Corp" (MAIN_ADMIN)
- Admin of "Startup LLC" (ADMIN in ORGANIZATION_ADMIN)
- Employee of "Consulting Firm" (EMPLOYEE via EMPLOYMENT_CONTRACT)
- Employee of "Tech Corp" (also has employment contract)

**Resolution Rule**: **Highest permission level wins**
```
If person is MAIN_ADMIN → Use MAIN_ADMIN permissions
Else if SUPER_ADMIN → Use SUPER_ADMIN permissions
Else if ADMIN → Use ADMIN permissions
Else if MODERATOR → Use MODERATOR permissions
Else → Use EMPLOYEE position-based permissions
```

---

## Database Schema

### Relevant Tables

#### 1. organization
```sql
CREATE TABLE organization (
    id TEXT PRIMARY KEY,
    short_name TEXT NOT NULL,
    main_admin_id TEXT NOT NULL,  -- FK to person(id)
    -- ... other fields
    FOREIGN KEY(main_admin_id) REFERENCES person(id)
);
```

#### 2. organization_admin
```sql
CREATE TABLE organization_admin (
    id TEXT PRIMARY KEY,
    organization_id TEXT NOT NULL,
    person_id TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'ADMIN',  -- SUPER_ADMIN, ADMIN, MODERATOR
    permissions TEXT,                     -- JSON
    appointed_by TEXT,
    appointed_at TEXT DEFAULT (datetime('now')),
    is_active INTEGER DEFAULT 1,
    notes TEXT,
    -- ... audit fields
    FOREIGN KEY(organization_id) REFERENCES organization(id),
    FOREIGN KEY(person_id) REFERENCES person(id),
    UNIQUE(organization_id, person_id)
);
```

#### 3. employment_contract
```sql
CREATE TABLE employment_contract (
    id TEXT PRIMARY KEY,
    organization_id TEXT NOT NULL,
    employee_id TEXT NOT NULL,            -- FK to person(id)
    position_id TEXT,                     -- FK to popular_organization_position(id)
    job_title TEXT,
    status TEXT DEFAULT 'ACTIVE',         -- ACTIVE, TERMINATED, SUSPENDED
    start_date TEXT,
    end_date TEXT,
    -- ... other fields
    FOREIGN KEY(organization_id) REFERENCES organization(id),
    FOREIGN KEY(employee_id) REFERENCES person(id),
    FOREIGN KEY(position_id) REFERENCES popular_organization_position(id)
);
```

#### 4. entity_permission_definition
```sql
CREATE TABLE entity_permission_definition (
    id TEXT PRIMARY KEY,
    entity_id TEXT NOT NULL,              -- FK to entity_definition(id)
    permission_type_id TEXT NOT NULL,     -- FK to enum_entity_permission_type(id)
    position_id TEXT NOT NULL,            -- FK to popular_organization_position(id)
    is_allowed INTEGER NOT NULL,          -- 1 = allowed, 0 = denied
    -- ... audit fields
);
```

---

## PHP API Reference

### Auth Class Methods

Located in: `lib/Auth.php`

#### belongsToOrganization()
```php
/**
 * Check if user belongs to organization (any capacity)
 * @param string $organizationId
 * @param string|null $userId Optional, defaults to current user
 * @return bool
 */
Auth::belongsToOrganization($organizationId, $userId = null): bool
```

**Example**:
```php
if (Auth::belongsToOrganization('org-uuid-123')) {
    echo "You are a member of this organization";
}
```

#### getOrganizationMembershipType()
```php
/**
 * Get user's membership type in organization
 * @return string|null 'MAIN_ADMIN', 'ORGANIZATION_ADMIN', 'EMPLOYEE', or null
 */
Auth::getOrganizationMembershipType($organizationId, $userId = null): ?string
```

**Example**:
```php
$type = Auth::getOrganizationMembershipType('org-uuid-123');
// Returns: 'MAIN_ADMIN', 'ORGANIZATION_ADMIN', 'EMPLOYEE', or null
```

#### getOrganizationPermissionLevel()
```php
/**
 * Get user's highest permission level
 * @return string|null 'MAIN_ADMIN', 'SUPER_ADMIN', 'ADMIN', 'MODERATOR', 'EMPLOYEE'
 */
Auth::getOrganizationPermissionLevel($organizationId, $userId = null): ?string
```

**Example**:
```php
$level = Auth::getOrganizationPermissionLevel('org-uuid-123');

switch ($level) {
    case 'MAIN_ADMIN':
    case 'SUPER_ADMIN':
        // Show admin panel
        break;
    case 'ADMIN':
        // Show management panel
        break;
    case 'EMPLOYEE':
        // Show employee dashboard
        break;
}
```

#### hasAdminAccess()
```php
/**
 * Check if user has full admin access (main admin or org admin)
 * @return bool
 */
Auth::hasAdminAccess($organizationId, $userId = null): bool
```

**Example**:
```php
if (Auth::hasAdminAccess($orgId)) {
    // User can perform admin actions
}
```

#### canPerformAction()
```php
/**
 * Check if user can perform action on entity
 * For employees: checks ENTITY_PERMISSION_DEFINITION
 * For admins: automatically grants access
 * @param string $entityCode Entity code (e.g., 'REQUISITION')
 * @param string $permissionType Permission type (e.g., 'APPROVER')
 * @param string $organizationId
 * @param string|null $userId
 * @return bool
 */
Auth::canPerformAction($entityCode, $permissionType, $organizationId, $userId = null): bool
```

**Example**:
```php
if (Auth::canPerformAction('REQUISITION', 'APPROVER', $orgId)) {
    // Show approve button
}

if (Auth::canPerformAction('VACANCY', 'REQUEST', $orgId)) {
    // User can request new vacancy
}
```

#### isMainAdmin()
```php
/**
 * Check if user is main admin
 * @return bool
 */
Auth::isMainAdmin($organizationId, $userId = null): bool
```

#### isOrganizationAdmin()
```php
/**
 * Check if user is organization admin (any role)
 * @return bool
 */
Auth::isOrganizationAdmin($organizationId, $userId = null): bool
```

#### getAdminRole()
```php
/**
 * Get admin role (for organization admins only)
 * @return string|null 'SUPER_ADMIN', 'ADMIN', 'MODERATOR', or null
 */
Auth::getAdminRole($organizationId, $userId = null): ?string
```

---

## SQL Query Patterns

### Pattern 1: Get All User's Organizations

```sql
-- Returns all organizations user belongs to (any membership type)
SELECT DISTINCT
    o.id,
    o.short_name,
    CASE
        WHEN o.main_admin_id = :person_id THEN 'MAIN_ADMIN'
        WHEN oa.person_id IS NOT NULL THEN 'ORGANIZATION_ADMIN'
        WHEN ec.employee_id IS NOT NULL THEN 'EMPLOYEE'
    END as membership_type,
    CASE
        WHEN o.main_admin_id = :person_id THEN 'SUPER_ADMIN'
        WHEN oa.role IS NOT NULL THEN oa.role
        ELSE NULL
    END as role,
    ec.job_title,
    ec.position_id
FROM organization o
LEFT JOIN organization_admin oa ON o.id = oa.organization_id
    AND oa.person_id = :person_id
    AND oa.is_active = 1
    AND oa.deleted_at IS NULL
LEFT JOIN employment_contract ec ON o.id = ec.organization_id
    AND ec.employee_id = :person_id
    AND ec.status = 'ACTIVE'
    AND ec.deleted_at IS NULL
WHERE (
    o.main_admin_id = :person_id
    OR oa.person_id = :person_id
    OR ec.employee_id = :person_id
)
AND o.deleted_at IS NULL
ORDER BY o.short_name;
```

### Pattern 2: Check Specific Membership

```sql
-- Check if person belongs to organization (any way)
SELECT COUNT(*) as is_member
FROM (
    SELECT 1 FROM organization
    WHERE id = :organization_id
      AND main_admin_id = :person_id
      AND deleted_at IS NULL

    UNION

    SELECT 1 FROM organization_admin
    WHERE organization_id = :organization_id
      AND person_id = :person_id
      AND is_active = 1
      AND deleted_at IS NULL

    UNION

    SELECT 1 FROM employment_contract
    WHERE organization_id = :organization_id
      AND employee_id = :person_id
      AND status = 'ACTIVE'
      AND deleted_at IS NULL
) memberships;
```

### Pattern 3: Get Highest Permission Level

```sql
-- Returns highest permission level
SELECT
    CASE
        WHEN main_admin.id IS NOT NULL THEN 1  -- MAIN_ADMIN
        WHEN org_admin.role = 'SUPER_ADMIN' THEN 2
        WHEN org_admin.role = 'ADMIN' THEN 3
        WHEN org_admin.role = 'MODERATOR' THEN 4
        WHEN employee.id IS NOT NULL THEN 5  -- EMPLOYEE
        ELSE 999  -- No access
    END as permission_level,
    CASE
        WHEN main_admin.id IS NOT NULL THEN 'MAIN_ADMIN'
        WHEN org_admin.role = 'SUPER_ADMIN' THEN 'SUPER_ADMIN'
        WHEN org_admin.role = 'ADMIN' THEN 'ADMIN'
        WHEN org_admin.role = 'MODERATOR' THEN 'MODERATOR'
        WHEN employee.id IS NOT NULL THEN 'EMPLOYEE'
        ELSE NULL
    END as permission_level_name
FROM organization o
LEFT JOIN (
    SELECT id, main_admin_id
    FROM organization
    WHERE id = :organization_id
      AND main_admin_id = :person_id
      AND deleted_at IS NULL
) main_admin ON main_admin.main_admin_id = :person_id
LEFT JOIN (
    SELECT role
    FROM organization_admin
    WHERE organization_id = :organization_id
      AND person_id = :person_id
      AND is_active = 1
      AND deleted_at IS NULL
) org_admin ON 1=1
LEFT JOIN (
    SELECT id
    FROM employment_contract
    WHERE organization_id = :organization_id
      AND employee_id = :person_id
      AND status = 'ACTIVE'
      AND deleted_at IS NULL
) employee ON 1=1
WHERE o.id = :organization_id
ORDER BY permission_level
LIMIT 1;
```

### Pattern 4: Check Employee Position Permission

```sql
-- Check if employee can perform specific action
SELECT COUNT(*) > 0 as can_perform
FROM employment_contract ec
JOIN entity_permission_definition epd
    ON epd.position_id = ec.position_id
JOIN entity_definition ed
    ON ed.id = epd.entity_id
JOIN enum_entity_permission_type ept
    ON ept.id = epd.permission_type_id
WHERE ec.employee_id = :person_id
  AND ec.organization_id = :organization_id
  AND ec.status = 'ACTIVE'
  AND ec.deleted_at IS NULL
  AND ed.code = :entity_code
  AND ept.code = :permission_type
  AND epd.is_allowed = 1;
```

---

## REST API Integration

### Get User's Organizations

**Endpoint**: `GET /api/organization/my-organizations.php`

**Response**:
```json
{
  "success": true,
  "organizations": [
    {
      "id": "org-uuid-1",
      "short_name": "Tech Solutions Inc",
      "memberships": [
        {
          "type": "MAIN_ADMIN",
          "role": "SUPER_ADMIN",
          "details": null
        }
      ],
      "highest_level": "MAIN_ADMIN"
    },
    {
      "id": "org-uuid-2",
      "short_name": "Startup LLC",
      "memberships": [
        {
          "type": "ORGANIZATION_ADMIN",
          "role": "ADMIN",
          "details": {
            "appointed_by": "person-uuid",
            "appointed_at": "2025-01-15"
          }
        },
        {
          "type": "EMPLOYEE",
          "role": null,
          "details": {
            "job_title": "Senior Developer",
            "position_id": "pos-uuid"
          }
        }
      ],
      "highest_level": "ADMIN"
    }
  ],
  "current_organization_id": "org-uuid-1"
}
```

### Check Permission

**Endpoint**: `POST /api/organization/check-permission.php`

**Request**:
```json
{
  "organization_id": "org-uuid-123",
  "entity_code": "REQUISITION",
  "permission_type": "APPROVER"
}
```

**Response**:
```json
{
  "success": true,
  "has_permission": true,
  "reason": "ADMIN",
  "details": {
    "membership_type": "ORGANIZATION_ADMIN",
    "role": "ADMIN"
  }
}
```

---

## UI Implementation

### Display Membership Badges

```php
<?php
$level = Auth::getOrganizationPermissionLevel($orgId);
$membershipType = Auth::getOrganizationMembershipType($orgId);

switch ($level) {
    case 'MAIN_ADMIN':
        echo '<span class="badge bg-danger"><i class="bi bi-gem"></i> Owner</span>';
        break;
    case 'SUPER_ADMIN':
        echo '<span class="badge bg-danger"><i class="bi bi-shield-fill-check"></i> Super Admin</span>';
        break;
    case 'ADMIN':
        echo '<span class="badge bg-warning"><i class="bi bi-shield-check"></i> Admin</span>';
        break;
    case 'MODERATOR':
        echo '<span class="badge bg-info"><i class="bi bi-eye"></i> Moderator</span>';
        break;
    case 'EMPLOYEE':
        echo '<span class="badge bg-secondary"><i class="bi bi-person-badge"></i> Employee</span>';
        break;
}
?>
```

### Conditional UI Elements

```php
<?php if (Auth::hasAdminAccess($orgId)): ?>
    <!-- Admin Panel -->
    <div class="admin-panel">
        <h3>Administration</h3>
        <?php if (Auth::isMainAdmin($orgId)): ?>
            <button>Transfer Ownership</button>
        <?php endif; ?>
        <button>Manage Admins</button>
        <button>Manage Employees</button>
    </div>
<?php endif; ?>

<?php if (Auth::canPerformAction('REQUISITION', 'APPROVER', $orgId)): ?>
    <button class="btn btn-success">Approve Requisition</button>
<?php endif; ?>
```

### Organization Selector with Badges

```html
<select id="orgSelector" class="form-select">
    <?php foreach ($organizations as $org): ?>
        <option value="<?= $org['id'] ?>"
                data-level="<?= $org['highest_level'] ?>">
            <?= htmlspecialchars($org['short_name']) ?>
            [<?= $org['highest_level'] ?>]
        </option>
    <?php endforeach; ?>
</select>
```

---

## Security Best Practices

### 1. Always Verify Membership

```php
// WRONG: Assuming user has access
$orgId = $_GET['organization_id'];
// ... perform admin action

// RIGHT: Always check membership first
if (!Auth::belongsToOrganization($orgId)) {
    http_response_code(403);
    die('Access denied');
}
```

### 2. Check Permission Level for Actions

```php
// WRONG: Allowing any member to perform admin actions
if (Auth::belongsToOrganization($orgId)) {
    // Delete organization - UNSAFE!
}

// RIGHT: Check admin access
if (Auth::hasAdminAccess($orgId)) {
    // Perform admin action
} else {
    http_response_code(403);
    die('Admin access required');
}
```

### 3. Use Granular Checks for Employees

```php
// For position-based permissions
if (!Auth::canPerformAction('REQUISITION', 'APPROVER', $orgId)) {
    die('You do not have approval permissions');
}
```

### 4. Validate Current Organization in Session

```php
// Ensure current organization is valid for user
$currentOrgId = Auth::currentOrganizationId();
if ($currentOrgId && !Auth::belongsToOrganization($currentOrgId)) {
    // Invalid session - clear and re-authenticate
    Auth::clearCurrentOrganization();
    Auth::initializeDefaultOrganization(Auth::id());
}
```

### 5. Log Permission Checks

```php
// Audit trail for sensitive actions
if (Auth::isMainAdmin($orgId)) {
    Logger::log("User {$userId} performed admin action on {$orgId}");
}
```

---

## Migration Guide

### Updating Existing Code

#### Before (Old System - Employment Contract Only)

```php
// Old: Only checked employment contract
$sql = "SELECT organization_id
        FROM employment_contract
        WHERE employee_id = ? AND status = 'ACTIVE'";
$orgs = Database::fetchAll($sql, [$userId]);
```

#### After (New System - Three-Tier)

```php
// New: Check all three membership types
$orgs = Auth::getUserOrganizations($userId);
// or
if (Auth::belongsToOrganization($orgId, $userId)) {
    // User is member
}
```

### Updating Process Start Logic

#### Before
```php
// Only employees could start processes
if ($empContract) {
    // Start process
}
```

#### After
```php
// Admins AND employees can start processes
if (Auth::hasAdminAccess($orgId) || Auth::canPerformAction('PROCESS', 'START', $orgId)) {
    // Start process
}
```

---

## Troubleshooting

### Issue: User can't see their organization

**Possible Causes**:
1. Not main admin, no admin record, no employment contract
2. `is_active = 0` in organization_admin
3. `status != 'ACTIVE'` in employment_contract
4. `deleted_at IS NOT NULL` in any table

**Solution**:
```sql
-- Check all three membership sources
SELECT 'MAIN_ADMIN' as source, COUNT(*) as count
FROM organization
WHERE main_admin_id = :person_id AND deleted_at IS NULL

UNION

SELECT 'ORG_ADMIN', COUNT(*)
FROM organization_admin
WHERE person_id = :person_id AND is_active = 1 AND deleted_at IS NULL

UNION

SELECT 'EMPLOYEE', COUNT(*)
FROM employment_contract
WHERE employee_id = :person_id AND status = 'ACTIVE' AND deleted_at IS NULL;
```

### Issue: Permission denied for action user should have

**Possible Causes**:
1. Position not assigned proper permissions in ENTITY_PERMISSION_DEFINITION
2. Wrong entity_code or permission_type
3. User is MODERATOR (read-only)

**Solution**:
```sql
-- Check permission definition
SELECT * FROM entity_permission_definition epd
JOIN entity_definition ed ON ed.id = epd.entity_id
JOIN enum_entity_permission_type ept ON ept.id = epd.permission_type_id
WHERE epd.position_id IN (
    SELECT position_id FROM employment_contract
    WHERE employee_id = :person_id
)
AND ed.code = :entity_code;
```

### Issue: Multiple memberships causing confusion

**Solution**: Use `getOrganizationPermissionLevel()` to get the highest level:
```php
$level = Auth::getOrganizationPermissionLevel($orgId);
// This automatically returns the highest permission level
```

---

## Summary

The three-tier organization membership system provides:

✅ **Flexible Ownership**: Main admin can transfer ownership
✅ **Role-Based Admin**: Multiple admins with different access levels
✅ **Granular Employee Permissions**: Position-based security
✅ **Multiple Memberships**: Users can have multiple roles
✅ **Hierarchical Resolution**: Highest permission automatically applies
✅ **Comprehensive API**: Easy-to-use PHP methods for all checks
✅ **Security-First**: Always validate membership and permissions

**Key Takeaway**: Always use `Auth::belongsToOrganization()` first, then use more specific checks like `hasAdminAccess()` or `canPerformAction()` based on the required permission level.

---

**For Further Assistance**:
- See `lib/Auth.php` for implementation details
- Check `/api/organization/` for REST API endpoints
- Review `metadata/011-organization_admin.sql` for entity definition

**Last Updated**: 2025-01-23
**Version**: 1.0