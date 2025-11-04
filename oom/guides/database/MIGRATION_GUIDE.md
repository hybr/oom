# Database Migration Guide

## Overview

The migration system (`scripts/migrate.php`) handles database schema evolution, data population, and process definition installation in a controlled, trackable manner.

## Migration Execution Order

The migrator runs in the following sequence:

```
1. Schema Migrations (metadata/*.sql)
   ↓
2. Entity Table Creation (from entity_definition metadata)
   ↓
3. Data Seeds (metadata/data/*.sql)
   ↓
4. Process Definitions (metadata/processes/*.sql)
```

---

## 1. Schema Migrations

**Location:** `metadata/*.sql`

**Purpose:** Create core database tables and entity metadata

**Examples:**
- `001-initial.sql` - Core system tables
- `002-person.sql` - Person entity
- `009-hiring_domain.sql` - Hiring entities
- `010-process_flow_system.sql` - Workflow engine
- `011-vacancy_process_entities.sql` - Vacancy process support entities

**Tracking:** Stored in `migrations` table

**Naming Convention:** `###-description.sql` (numbered for ordering)

---

## 2. Entity Table Creation

**Automatic:** Generated from `entity_definition` metadata

**How it works:**
1. Reads all active entities from `entity_definition`
2. Gets attributes from `entity_attribute`
3. Gets relationships from `entity_relationship`
4. Creates tables with:
   - Standard fields: `id`, `created_at`, `updated_at`, `deleted_at`, `version_no`, `changed_by`
   - Entity-specific columns based on attributes
   - Foreign key constraints based on relationships

**No manual SQL needed** - Tables created automatically from metadata!

---

## 3. Data Seeds

**Location:** `metadata/data/*.sql`

**Purpose:** Populate reference data, enums, and initial records

**Examples:**
- `014-entity_permission_definitions.sql` - Permission type data
- `015-states-provinces.sql` - Geographic data
- `022-major-universities-colleges.sql` - University data

**Tracking:** Stored in `data_seeds` table

**Naming Convention:** `###-description.sql`

---

## 4. Process Definitions (NEW!)

**Location:** `metadata/processes/*.sql`

**Purpose:** Install workflow process definitions

**Examples:**
- `vacancy_creation_process.sql` - Job vacancy creation workflow

**What it creates:**
- Process graphs (workflow templates)
- Process nodes (workflow steps)
- Process edges (transitions)
- Process edge conditions (routing logic)

**Tracking:** Stored in `data_seeds` table with `process_` prefix

**Naming Convention:** `descriptive_name_process.sql`

---

## Usage

### Run All Pending Migrations

```bash
php scripts/migrate.php migrate
```

**Output:**
```
=== Database Migration ===
Found 2 pending migration(s):
  - 010-process_flow_system.sql
  - 011-vacancy_process_entities.sql

Executing: 010-process_flow_system.sql
  ✓ Completed: 45 statements executed

Executing: 011-vacancy_process_entities.sql
  ✓ Completed: 120 statements executed

✓ All migrations completed successfully!

=== Ensuring Entity Tables ===
  ✓ VACANCY_DRAFT: created table 'vacancy_draft'
  ✓ VACANCY_APPROVAL_RECORD: created table 'vacancy_approval_record'
  Summary: Created 6, Already existed 15

=== Running Data Seeds ===
Found 3 pending data seed(s):
  - 014-entity_permission_definitions.sql
  ...
  ✓ All data seeds completed successfully!

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

### Reset and Re-run All Migrations (Development Only)

```bash
php scripts/migrate.php reset
```

**Warning:** This drops the `migrations` tracking table and re-runs everything!

---

## Migration File Structure

### Schema Migration Template

```sql
-- =====================================================================
-- MIGRATION: Description
-- =====================================================================

PRAGMA foreign_keys = ON;

-- Create tables
CREATE TABLE IF NOT EXISTS my_table (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    -- ... your columns
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_my_table_field ON my_table(field);

-- Insert entity metadata
INSERT OR IGNORE INTO entity_definition (id, code, name, ...)
VALUES (...);

-- =====================================================================
-- End of migration
-- =====================================================================
```

### Data Seed Template

```sql
-- =====================================================================
-- DATA SEED: Description
-- =====================================================================

-- Insert reference data
INSERT OR IGNORE INTO my_table (id, field1, field2)
VALUES
    ('uuid1', 'value1', 'value2'),
    ('uuid2', 'value3', 'value4');

-- =====================================================================
-- End of seed
-- =====================================================================
```

### Process Definition Template

```sql
-- =====================================================================
-- PROCESS DEFINITION: Description
-- =====================================================================

PRAGMA foreign_keys = ON;

-- Create process graph
INSERT INTO process_graph (id, code, name, ...)
VALUES (...);

-- Create nodes
INSERT INTO process_node (id, graph_id, node_code, node_type, ...)
VALUES (...);

-- Create edges
INSERT INTO process_edge (id, graph_id, from_node_id, to_node_id, ...)
VALUES (...);

-- Create conditions
INSERT INTO process_edge_condition (id, edge_id, field_source, operator, ...)
VALUES (...);

-- Success message (optional)
SELECT 'Process installed successfully!' as message,
       'PROCESS_CODE' as process_code,
       8 as total_nodes,
       11 as total_edges;
```

---

## Tracking Tables

### migrations
Tracks executed schema migrations

| Field | Type | Description |
|-------|------|-------------|
| id | INTEGER | Auto-increment ID |
| migration | TEXT | Migration filename |
| executed_at | DATETIME | When executed |

### data_seeds
Tracks executed data seeds AND process definitions

| Field | Type | Description |
|-------|------|-------------|
| id | INTEGER | Auto-increment ID |
| seed_file | TEXT | Seed filename or `process_` + filename |
| executed_at | DATETIME | When executed |

**Note:** Process definitions are tracked with `process_` prefix to distinguish them from data seeds.

---

## Best Practices

### 1. Migration Numbering
- Use sequential numbers: `001-`, `002-`, `003-`
- Leave gaps for future insertions: `010-`, `020-`, `030-`
- Core system: `001-099`
- Domain entities: `100+`
- Process definitions: Use descriptive names, no numbers

### 2. Idempotent Migrations
Always use:
- `CREATE TABLE IF NOT EXISTS`
- `INSERT OR IGNORE`
- `CREATE INDEX IF NOT EXISTS`

This allows re-running migrations safely.

### 3. Foreign Keys
Always enable foreign keys:
```sql
PRAGMA foreign_keys = ON;
```

### 4. Transaction Safety
Large migrations should use transactions:
```sql
BEGIN TRANSACTION;
-- ... your changes
COMMIT;
```

### 5. Rollback Strategy
- Schema migrations: Keep old migrations, create new ones for changes
- Data seeds: Use `UPDATE OR INSERT` patterns
- Process definitions: Version process graphs, don't modify deployed ones

---

## Directory Structure

```
oom/
├── metadata/
│   ├── 001-initial.sql                    # Schema migration
│   ├── 002-person.sql                     # Schema migration
│   ├── 009-hiring_domain.sql              # Schema migration
│   ├── 010-process_flow_system.sql        # Schema migration
│   ├── 011-vacancy_process_entities.sql   # Schema migration (NEW!)
│   ├── data/
│   │   ├── 014-entity_permission_definitions.sql
│   │   ├── 015-states-provinces.sql
│   │   └── ...
│   └── processes/                         # NEW!
│       └── vacancy_creation_process.sql
└── scripts/
    └── migrate.php
```

---

## Common Tasks

### Add New Entity

**Step 1:** Create migration file `metadata/012-my_entity.sql`
```sql
INSERT OR IGNORE INTO entity_definition (id, code, name, table_name, ...)
VALUES ('uuid', 'MY_ENTITY', 'My Entity', 'my_entity', ...);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, ...)
VALUES
    ('uuid1', 'entity-uuid', 'field1', 'Field 1', ...),
    ('uuid2', 'entity-uuid', 'field2', 'Field 2', ...);
```

**Step 2:** Run migration
```bash
php scripts/migrate.php
```

**Result:** Table `my_entity` created automatically!

### Add Process Definition

**Step 1:** Create process file `metadata/processes/my_process.sql`
```sql
INSERT INTO process_graph (id, code, name, ...)
VALUES ('uuid', 'MY_PROCESS', 'My Process', ...);
-- ... nodes, edges, conditions
```

**Step 2:** Run migration
```bash
php scripts/migrate.php
```

**Result:** Process definition installed and tracked!

### Add Reference Data

**Step 1:** Create seed file `metadata/data/050-my_reference_data.sql`
```sql
INSERT OR IGNORE INTO my_lookup_table (id, code, name)
VALUES
    ('uuid1', 'CODE1', 'Name 1'),
    ('uuid2', 'CODE2', 'Name 2');
```

**Step 2:** Run migration
```bash
php scripts/migrate.php
```

**Result:** Data inserted and tracked!

---

## Troubleshooting

### Issue: Migration already executed but needs to run again

**Solution 1:** Remove from tracking table
```sql
DELETE FROM migrations WHERE migration = '010-process_flow_system.sql';
```

**Solution 2:** Reset all migrations (CAREFUL!)
```bash
php scripts/migrate.php reset
```

### Issue: Process definition not found

**Check:**
1. File exists in `metadata/processes/`
2. File has `.sql` extension
3. Directory permissions allow reading

**Debug:**
```bash
ls metadata/processes/*.sql
```

### Issue: Foreign key constraint failed

**Cause:** Referenced entity doesn't exist yet

**Solution:** Check migration order:
1. Create parent entity first
2. Create child entity second
3. Or disable FK temporarily (not recommended)

### Issue: Process definition throws error

**Check:**
1. All referenced entity IDs exist (`entity_id` in `process_graph`)
2. All position IDs exist (if specified)
3. All permission type IDs exist (if specified)
4. Node IDs match in edges

**Debug:**
```sql
-- Check if entity exists
SELECT * FROM entity_definition WHERE code = 'ORGANIZATION_VACANCY';

-- Check if position exists
SELECT * FROM popular_organization_position WHERE id = 'position-id';
```

---

## Migration Workflow

### Development Cycle

```
1. Create migration file
   ↓
2. Run: php scripts/migrate.php
   ↓
3. Test changes
   ↓
4. If issues found:
   - Fix migration file
   - Remove from tracking: DELETE FROM migrations WHERE...
   - Re-run migration
   ↓
5. Commit migration file to git
```

### Production Deployment

```
1. Pull latest code (includes migration files)
   ↓
2. Backup database
   ↓
3. Run: php scripts/migrate.php
   ↓
4. Verify migrations executed
   ↓
5. Test application
```

---

## Summary

The enhanced migration system now supports:

✅ **Schema Migrations** - Database structure changes
✅ **Entity Table Creation** - Automatic from metadata
✅ **Data Seeds** - Reference data population
✅ **Process Definitions** - Workflow installation

All tracked, idempotent, and version-controlled!

**Key Files:**
- `scripts/migrate.php` - Migration runner
- `metadata/*.sql` - Schema migrations
- `metadata/data/*.sql` - Data seeds
- `metadata/processes/*.sql` - Process definitions (NEW!)

Run migrations with:
```bash
php scripts/migrate.php migrate
```
