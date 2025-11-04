# Migration System Fixes - Summary

**Date:** 2025-10-23
**Status:** ✅ All fixes applied and tested

---

## Issues Fixed

### 1. ✅ Syntax Errors in Process Definition (7 errors)

**Problem:** Lines containing only `0` were being removed during SQL comment filtering.

**Root Cause:** PHP's `!empty('0')` returns `false` because the string `'0'` is falsy in PHP.

**Location:** `scripts/migrate.php:434`

**Fix:**
```php
// Before (WRONG):
return !empty($trimmed) && !preg_match('/^--/', $trimmed);

// After (CORRECT):
return strlen($trimmed) > 0 && !preg_match('/^--/', $trimmed);
```

**Impact:** All 7 `process_edge_condition` INSERT statements now execute successfully.

---

### 2. ✅ Missing Column in Process Node INSERTs

**Problem:** `process_node` table has a `form_template` column that wasn't included in INSERT statements.

**Root Cause:** Process definition SQL was missing the `form_template` column in TASK node INSERTs.

**Location:** `metadata/processes/vacancy_creation_process.sql`

**Fix:** Added `form_template` column with NULL value to all 5 TASK node INSERTs:
```sql
INSERT INTO process_node (
    id, graph_id, node_code, node_name, node_type,
    description, position_id, permission_type_id,
    sla_hours, estimated_duration_hours,
    display_x, display_y,
    form_template,  -- ← ADDED
    instructions, notify_on_assignment, notify_on_due,
    escalate_after_hours, escalate_to_position_id
)
VALUES (
    ...,
    NULL,  -- ← form_template (use default form)
    ...
);
```

---

### 3. ✅ Foreign Key Constraint Violations

**Problem:** Process definitions reference positions and permissions that don't exist during installation.

**Root Cause:** Process nodes have foreign keys to `popular_organization_position` and `enum_entity_permission_type`, but these records are created by the setup script AFTER migration.

**Location:** `scripts/migrate.php:420-476`

**Fix:** Temporarily disable foreign key constraints during process definition execution:
```php
private function executeProcessDefinition($file)
{
    // Disable FK constraints
    $this->db->exec("PRAGMA foreign_keys = OFF");

    // ... execute statements ...

    // Re-enable FK constraints
    $this->db->exec("PRAGMA foreign_keys = ON");
}
```

**Also:** Added note in `vacancy_creation_process.sql`:
```sql
-- NOTE: This process definition requires positions and permissions to be created first.
-- Run database/setup_vacancy_process.sql after migration to configure the process.
```

---

### 4. ✅ Sample Data Foreign Key Error

**Problem:** Sample process graph in `010-process_flow_system.sql` references `person` table that doesn't exist yet.

**Root Cause:** Sample INSERT has `created_by` field with FK to person table, but person table is created later by entity table creator.

**Location:** `metadata/010-process_flow_system.sql:493-504`

**Fix:** Removed sample data INSERT and added explanatory comment:
```sql
-- NOTE: Sample process graphs have been moved to metadata/processes/ directory
-- This avoids foreign key issues with person table during initial migration
-- Example: See metadata/processes/vacancy_creation_process.sql
```

---

### 5. ✅ Duplicate Column Error

**Problem:** Entity table creation failed with "duplicate column name: changed_by"

**Root Cause:** Entity attributes included standard field names that were already added to CREATE TABLE.

**Location:** `scripts/migrate.php:222-230`

**Fix:** Added filtering to skip entity attributes that conflict with standard fields:
```php
$standardFields = ['id', 'created_at', 'updated_at', 'deleted_at', 'version_no', 'changed_by'];

foreach ($attributes as $attr) {
    // Skip system attributes
    if (isset($attr['is_system']) && $attr['is_system'] == 1) continue;

    // Skip attributes that conflict with standard field names
    if (in_array($attr['code'], $standardFields)) continue;

    // ... create column
}
```

---

## Files Modified

### 1. `scripts/migrate.php`
- Line 222-230: Added `$standardFields` filtering
- Line 426: Added `PRAGMA foreign_keys = OFF` before process execution
- Line 434: Fixed `!empty()` to `strlen() > 0` (applied to 2 locations)
- Line 468: Added `PRAGMA foreign_keys = ON` after process execution
- Line 463-466: Added debug output for syntax errors

### 2. `metadata/processes/vacancy_creation_process.sql`
- Lines 15-22: Added NOTE about expected warnings and PRAGMA foreign_keys = OFF
- Lines 86, 137, 206, 251, 296: Added `form_template` column to 5 TASK node INSERTs

### 3. `metadata/010-process_flow_system.sql`
- Lines 491-493: Removed sample process graph INSERT, added explanatory comment

### 4. File Organization
- Moved `001-setup_vacancy_process.sql` from `metadata/processes/` to `database/`
- Renamed `002-vacancy_creation_process.sql` to `vacancy_creation_process.sql`

---

## Migration Results

### Before Fixes:
```
❌ 7 syntax errors in process_edge_condition INSERTs
❌ 1 foreign key error in 010-process_flow_system.sql
❌ 1 duplicate column error in entity table creation
❌ Only 6-7 statements executed (should be 29)
```

### After Fixes:
```
✅ Zero warnings
✅ Zero errors
✅ 29 statements executed successfully
✅ All process components installed:
   - 1 process graph
   - 8 process nodes
   - 11 process edges
   - 7 edge conditions
```

---

## Verification

Run the following to verify the process installation:

```bash
sqlite3 database/v4l.sqlite << 'EOF'
SELECT 'Process Graph:' as section, count(*) as count
FROM process_graph WHERE code = 'VACANCY_CREATION'
UNION ALL
SELECT 'Process Nodes:', count(*)
FROM process_node WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
UNION ALL
SELECT 'Process Edges:', count(*)
FROM process_edge WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
UNION ALL
SELECT 'Edge Conditions:', count(*)
FROM process_edge_condition
WHERE edge_id IN (
    SELECT id FROM process_edge
    WHERE graph_id = 'VC000000-0000-4000-8000-000000000001'
);
EOF
```

**Expected Output:**
```
Process Graph:|1
Process Nodes:|8
Process Edges:|11
Edge Conditions:|7
```

---

## Next Steps

1. ✅ Migration system working with zero warnings
2. 📋 Run setup script to configure positions and permissions:
   ```bash
   sqlite3 database/v4l.sqlite < database/setup_vacancy_process.sql
   ```
3. 📋 Test process execution via API
4. 📋 Create additional process definitions as needed

---

## Lessons Learned

1. **PHP Type Coercion:** Always use explicit checks like `strlen() > 0` instead of `!empty()` when filtering strings that might contain `'0'`
2. **Foreign Key Management:** Disable FK constraints when installing process definitions that reference data not yet created
3. **Column Completeness:** Always include ALL columns in INSERT statements, even optional ones with NULL values
4. **Sample Data Placement:** Keep sample data separate from schema migrations to avoid FK issues
5. **Standard Fields:** Filter entity attributes to avoid conflicts with standard table columns

---

**All migration issues resolved! System ready for use.** ✅
