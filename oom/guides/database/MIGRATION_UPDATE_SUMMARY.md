# Migration System Update Summary

## What Changed

Updated `scripts/migrate.php` to include **Process Definitions** installation as the final step in the migration sequence.

---

## Migration Execution Order (NEW!)

The migrator now runs in **4 phases**:

```
Phase 1: Schema Migrations (metadata/*.sql)
   ↓
Phase 2: Entity Table Creation (from entity_definition metadata)
   ↓
Phase 3: Data Seeds (metadata/data/*.sql)
   ↓
Phase 4: Process Definitions (metadata/processes/*.sql) ← NEW!
```

---

## Changes Made to `scripts/migrate.php`

### 1. Added Process Definitions Phase

**Line 163-165:** Added process definitions execution
```php
// Run process definitions
echo "\n=== Running Process Definitions ===\n";
$this->runProcessDefinitions();
```

### 2. New Method: `getPendingProcessDefinitions()`

**Lines 376-408:** Finds pending process definition files
```php
private function getPendingProcessDefinitions()
{
    $processPath = $this->migrationsPath . '/processes';

    // Get all .sql files in processes directory
    $files = glob($processPath . '/*.sql');
    sort($files);

    // Get already executed process definitions
    $stmt = $this->db->query("SELECT seed_file FROM data_seeds WHERE seed_file LIKE 'process_%'");
    $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Filter out executed ones with 'process_' prefix
    // ...
}
```

**Key Features:**
- Scans `metadata/processes/` directory
- Tracks executed files in `data_seeds` table with `process_` prefix
- Returns pending process definition files sorted alphabetically

### 3. New Method: `executeProcessDefinition()`

**Lines 410-463:** Executes a single process definition file
```php
private function executeProcessDefinition($file)
{
    $basename = basename($file);
    echo "\n  Installing process: {$basename}\n";

    // Load and clean SQL
    $sql = file_get_contents($file);
    // Remove comments, split by semicolon

    foreach ($statements as $stmt) {
        // Execute each statement
        $this->db->exec($stmt);

        // Handle SELECT statements (success messages)
        if (preg_match('/^\s*SELECT/i', $stmt)) {
            $result = $this->db->query($stmt)->fetch(PDO::FETCH_ASSOC);
            // Display results
        }
    }

    // Mark as executed with 'process_' prefix
    $trackingName = 'process_' . $basename;
    $stmt = $this->db->prepare("INSERT INTO data_seeds (seed_file) VALUES (?)");
    $stmt->execute([$trackingName]);
}
```

**Key Features:**
- Executes SQL statements from process definition file
- Detects SELECT statements (used for success messages) and displays results
- Handles errors gracefully (skips UNIQUE constraints, "already exists" errors)
- Tracks execution with `process_` prefix to distinguish from data seeds

### 4. New Method: `runProcessDefinitions()`

**Lines 465-487:** Main orchestration method
```php
private function runProcessDefinitions()
{
    $pending = $this->getPendingProcessDefinitions();

    if (empty($pending)) {
        echo "  ✓ No pending process definitions\n";
        return;
    }

    echo "  Found " . count($pending) . " pending process definition(s):\n";
    foreach ($pending as $file) {
        echo "    - " . basename($file) . "\n";
    }

    foreach ($pending as $file) {
        $this->executeProcessDefinition($file);
    }

    echo "\n  ✓ All process definitions installed successfully!\n";
}
```

**Key Features:**
- Lists all pending process definitions
- Executes them in sorted order
- Provides clear feedback on progress

---

## Usage

### Run Migrations (Standard)

```bash
php scripts/migrate.php migrate
```

**Expected Output:**
```
=== Database Migration ===
✓ No pending migrations

=== Ensuring Entity Tables ===
  ✓ ORGANIZATION_VACANCY: table 'organization_vacancy' exists
  ✓ VACANCY_DRAFT: created table 'vacancy_draft'
  ✓ VACANCY_APPROVAL_RECORD: created table 'vacancy_approval_record'
  ✓ VACANCY_REJECTION_REASON: created table 'vacancy_rejection_reason'
  ✓ VACANCY_REVISION_HISTORY: created table 'vacancy_revision_history'
  ✓ VACANCY_TASK_DATA: created table 'vacancy_task_data'
  ✓ VACANCY_PUBLICATION_RECORD: created table 'vacancy_publication_record'
  Summary: Created 6, Already existed 15

=== Running Data Seeds ===
  ✓ No pending data seeds

=== Running Process Definitions ===
  Found 1 pending process definition(s):
    - vacancy_creation_process.sql

  Installing process: vacancy_creation_process.sql
    ➜ message: Vacancy Creation Process has been successfully created!
    ➜ graph_id: VC000000-0000-4000-8000-000000000001
    ➜ process_code: VACANCY_CREATION
    ➜ total_nodes: 8
    ➜ total_edges: 11
    ➜ total_conditions: 7
    ✓ Completed: 95 statements executed

  ✓ All process definitions installed successfully!
```

### Reset and Re-run All (Development)

```bash
php scripts/migrate.php reset
```

---

## Tracking

### How Process Definitions Are Tracked

Process definitions are tracked in the `data_seeds` table with a `process_` prefix:

| id | seed_file | executed_at |
|----|-----------|-------------|
| 15 | 014-entity_permission_definitions.sql | 2025-10-23 09:30:00 |
| 16 | 015-states-provinces.sql | 2025-10-23 09:30:15 |
| 17 | **process_vacancy_creation_process.sql** | 2025-10-23 09:45:30 |

**Why the prefix?**
- Distinguishes process definitions from data seeds
- Uses same tracking table (no new table needed)
- Easy to query: `WHERE seed_file LIKE 'process_%'`

### Query Executed Process Definitions

```sql
SELECT seed_file, executed_at
FROM data_seeds
WHERE seed_file LIKE 'process_%'
ORDER BY executed_at DESC;
```

---

## Directory Structure

```
oom/
├── metadata/
│   ├── 001-initial.sql
│   ├── 002-person.sql
│   ├── ...
│   ├── 010-process_flow_system.sql
│   ├── 011-vacancy_process_entities.sql      ← NEW!
│   ├── data/
│   │   ├── 014-entity_permission_definitions.sql
│   │   └── ...
│   └── processes/                             ← NEW!
│       └── vacancy_creation_process.sql       ← NEW!
└── scripts/
    └── migrate.php                            ← UPDATED!
```

---

## Benefits

### ✅ Automated Process Installation
- No manual SQL execution needed
- Process definitions installed automatically with `php scripts/migrate.php`
- Version controlled like any other migration

### ✅ Tracking & Idempotency
- Executed process definitions tracked
- Won't re-run already installed processes
- Safe to run multiple times

### ✅ Clear Feedback
- Shows which processes are being installed
- Displays success messages from process definitions
- Reports errors clearly

### ✅ Separation of Concerns
- Schema migrations: `metadata/*.sql`
- Data seeds: `metadata/data/*.sql`
- Process definitions: `metadata/processes/*.sql`

---

## Adding New Process Definitions

### Step 1: Create Process Definition File

Create `metadata/processes/my_new_process.sql`:

```sql
-- =====================================================================
-- MY NEW PROCESS DEFINITION
-- =====================================================================

PRAGMA foreign_keys = ON;

-- Create process graph
INSERT INTO process_graph (id, code, name, description, ...)
VALUES ('uuid', 'MY_PROCESS', 'My Process', '...', ...);

-- Create nodes
INSERT INTO process_node (id, graph_id, node_code, node_name, node_type, ...)
VALUES
    ('node1', 'graph-id', 'START', 'Start', 'START', ...),
    ('node2', 'graph-id', 'TASK1', 'First Task', 'TASK', ...),
    ('node3', 'graph-id', 'END', 'End', 'END', ...);

-- Create edges
INSERT INTO process_edge (id, graph_id, from_node_id, to_node_id, edge_label, ...)
VALUES
    ('edge1', 'graph-id', 'node1', 'node2', 'Begin', ...),
    ('edge2', 'graph-id', 'node2', 'node3', 'Complete', ...);

-- Optional: Success message
SELECT 'My Process installed successfully!' as message,
       'MY_PROCESS' as process_code,
       3 as total_nodes;
```

### Step 2: Run Migration

```bash
php scripts/migrate.php
```

### Step 3: Verify Installation

```bash
# Check tracking
sqlite3 database/v4l.sqlite "SELECT * FROM data_seeds WHERE seed_file LIKE 'process_%';"

# Check process graph
sqlite3 database/v4l.sqlite "SELECT * FROM process_graph WHERE code = 'MY_PROCESS';"

# Check nodes
sqlite3 database/v4l.sqlite "SELECT node_code, node_name FROM process_node WHERE graph_id = 'graph-id';"
```

---

## Example: Vacancy Creation Process

**File:** `metadata/processes/vacancy_creation_process.sql`

**What it creates:**
- 1 Process Graph: `VACANCY_CREATION`
- 8 Process Nodes: START, DRAFT_VACANCY, HR_REVIEW, BUDGET_CHECK, FINANCE_APPROVAL, DEPT_HEAD_APPROVAL, PUBLISH_VACANCY, END
- 11 Process Edges: Transitions with approval/rejection loops
- 7 Edge Conditions: Budget threshold checks, approval action checks

**Installation:**
```bash
php scripts/migrate.php
```

**Result:**
```
=== Running Process Definitions ===
  Found 1 pending process definition(s):
    - vacancy_creation_process.sql

  Installing process: vacancy_creation_process.sql
    ➜ message: Vacancy Creation Process has been successfully created!
    ➜ graph_id: VC000000-0000-4000-8000-000000000001
    ➜ process_code: VACANCY_CREATION
    ➜ total_nodes: 8
    ➜ total_edges: 11
    ➜ total_conditions: 7
    ✓ Completed: 95 statements executed

  ✓ All process definitions installed successfully!
```

---

## Troubleshooting

### Process Definition Not Running

**Check 1:** Directory exists
```bash
ls metadata/processes/
```

**Check 2:** File has `.sql` extension
```bash
ls metadata/processes/*.sql
```

**Check 3:** Already executed?
```sql
SELECT * FROM data_seeds WHERE seed_file LIKE 'process_%';
```

**Solution:** Delete from tracking to re-run
```sql
DELETE FROM data_seeds WHERE seed_file = 'process_vacancy_creation_process.sql';
```

### Error During Process Installation

**Common Errors:**

1. **Foreign Key Constraint**
   - Cause: Referenced entity/position/permission doesn't exist
   - Fix: Ensure `metadata/011-vacancy_process_entities.sql` ran first

2. **UNIQUE Constraint**
   - Cause: Process already exists
   - Fix: Process definitions should use `INSERT OR IGNORE` or check if not exists

3. **Table Doesn't Exist**
   - Cause: Process Flow System not installed
   - Fix: Ensure `metadata/010-process_flow_system.sql` ran first

---

## Migration History

### v1.0 (Original)
- Schema migrations
- Entity table creation
- Data seeds

### v2.0 (This Update)
- ✅ **Added Process Definitions phase**
- ✅ **New directory: `metadata/processes/`**
- ✅ **Process tracking with `process_` prefix**
- ✅ **Success message display from SELECT statements**
- ✅ **Automatic installation of workflow definitions**

---

## Files Modified

1. **`scripts/migrate.php`**
   - Added `runProcessDefinitions()` method
   - Added `getPendingProcessDefinitions()` method
   - Added `executeProcessDefinition()` method
   - Called `runProcessDefinitions()` at end of `migrate()`

## Files Created

1. **`MIGRATION_GUIDE.md`** - Complete migration system documentation
2. **`MIGRATION_UPDATE_SUMMARY.md`** - This file

## Files Already Present

1. **`metadata/processes/vacancy_creation_process.sql`** - Example process definition
2. **`metadata/011-vacancy_process_entities.sql`** - Supporting entities for process

---

## Summary

The migration system now provides **end-to-end automation** for:

✅ Database schema evolution
✅ Entity table creation
✅ Reference data population
✅ **Workflow process installation** ← NEW!

All tracked, version-controlled, and executable with a single command:

```bash
php scripts/migrate.php
```

🎉 **Migration system upgrade complete!**
