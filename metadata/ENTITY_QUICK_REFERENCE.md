# Entity Creation - Quick Reference

## File Template

```sql
-- =====================================================================
-- DOMAIN_NAME Metadata - Brief Description
-- Generated: YYYY-MM-DD
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- DDL: Table Creation
-- =========================================
CREATE TABLE IF NOT EXISTS table_name (
    id TEXT PRIMARY KEY,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,

    -- Your columns here
    name TEXT NOT NULL,
    foreign_key_id TEXT NOT NULL,

    FOREIGN KEY(foreign_key_id) REFERENCES other_table(id),
    FOREIGN KEY(changed_by) REFERENCES person(id)
);

CREATE INDEX IF NOT EXISTS idx_table_fk ON table_name(foreign_key_id);

-- =========================================
-- Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'xxxxxxxx-xxxx-4xxx-xxxx-xxxxxxxxxxxx',
    'ENTITY_CODE',
    'Entity Name',
    'Description starting with verb',
    'DOMAIN',
    'table_name',
    1
);

-- =========================================
-- Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_system, display_order)
VALUES
-- Foreign Keys
('attr-uuid-1', 'entity-uuid', 'foreign_key_id', 'Foreign Key', 'text', 1, 0, 0, 1),

-- Core Fields
('attr-uuid-2', 'entity-uuid', 'name', 'Name', 'text', 1, 1, 0, 2),
('attr-uuid-3', 'entity-uuid', 'description', 'Description', 'text', 0, 0, 0, 3),

-- Status/Flags
('attr-uuid-4', 'entity-uuid', 'status', 'Status', 'enum_objects', 1, 0, 0, 4),
('attr-uuid-5', 'entity-uuid', 'is_active', 'Is Active', 'boolean', 0, 0, 0, 5);

-- =========================================
-- Relationships
-- =========================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field)
VALUES (
    'rel-uuid',
    'from-entity-uuid',
    'to-entity-uuid',
    'many-to-one',
    'entity_to_parent',
    'foreign_key_id'
);
```

---

## Naming Conventions Cheat Sheet

| Element | Format | Example |
|---------|--------|---------|
| File name | `NNN-lowercase_name.sql` | `010-process_flow_system.sql` |
| Entity code | `UPPERCASE_SNAKE_CASE` | `ORGANIZATION_VACANCY` |
| Entity name | `Title Case` | `Organization Vacancy` |
| Table name | `lowercase_snake_case` | `organization_vacancy` |
| Attribute code | `lowercase_snake_case` | `organization_id` |
| Attribute name | `Title Case` | `Organization ID` |
| Function code | `lowercase_snake_case` | `create_person` |
| Relationship name | `from_to_to` | `task_instance_to_flow` |

---

## Data Types

```
text         - Strings, text fields
integer      - Whole numbers
number       - Decimals, floats
boolean      - True/false (0/1)
date         - Date only
datetime     - Date + time
time         - Time only
uuid         - UUID format
json         - JSON data
enum_strings - ["Option1", "Option2"]
enum_objects - {"options": [{"value":"V","label":"L"}]}
file         - File reference
```

---

## Standard System Fields

```sql
id TEXT PRIMARY KEY                        -- Always
created_at TEXT DEFAULT (datetime('now'))  -- Always
updated_at TEXT DEFAULT (datetime('now'))  -- Always
deleted_at TEXT                            -- Soft delete
version_no INTEGER DEFAULT 1               -- Optimistic locking
changed_by TEXT                            -- Audit (FK to person)
```

Mark as `is_system = 1` in entity_attribute

---

## Common Attribute Patterns

```sql
-- Foreign Key
('uuid', 'entity_id', 'parent_id', 'Parent', 'text', 1, 0, 0, 1)

-- Name/Title (Label)
('uuid', 'entity_id', 'name', 'Name', 'text', 1, 1, 0, 2)

-- Description
('uuid', 'entity_id', 'description', 'Description', 'text', 0, 0, 0, 3)

-- Enum Status
('uuid', 'entity_id', 'status', 'Status', 'enum_objects', 1, 0, 0,
 '{"options":[{"value":"ACTIVE","label":"Active"},{"value":"INACTIVE","label":"Inactive"}]}',
 'Current status', 4)

-- Boolean Flag
('uuid', 'entity_id', 'is_active', 'Is Active', 'boolean', 0, 0, 0, 5)

-- Date
('uuid', 'entity_id', 'due_date', 'Due Date', 'date', 0, 0, 0, 6)

-- Timestamp
('uuid', 'entity_id', 'completed_at', 'Completed At', 'datetime', 0, 0, 0, 7)

-- Number
('uuid', 'entity_id', 'amount', 'Amount', 'number', 0, 0, 0, 8)

-- JSON
('uuid', 'entity_id', 'metadata', 'Metadata', 'json', 0, 0, 0, 9)
```

---

## UUID Prefix Patterns

```
00000000-... = System/special
10000000-... = Workflow domain
20000000-... = Finance domain
30000000-... = Inventory domain
etc.

Or by entity type:
1xxxxxxx-... = Geography
2xxxxxxx-... = Person
3xxxxxxx-... = Education
4-7xxxxx-... = Organization
5-9xxxxx-... = Hiring
```

---

## Relationship Types

```
one-to-one     - 1:1 (rare, usually same table)
one-to-many    - 1:N (parent has many children)
many-to-one    - N:1 (many children to one parent)
many-to-many   - N:N (via junction table)
```

**Example Relationships:**
```
PERSON 1:N PERSON_CREDENTIAL      (one-to-many)
PERSON_CREDENTIAL N:1 PERSON      (many-to-one)
VACANCY N:N WORKSTATION           (many-to-many via junction)
```

---

## Indexes Quick Reference

```sql
-- Foreign Key Index
CREATE INDEX IF NOT EXISTS idx_table_fk
ON table_name(foreign_key_id);

-- Status Index
CREATE INDEX IF NOT EXISTS idx_table_status
ON table_name(status);

-- Composite Index
CREATE INDEX IF NOT EXISTS idx_table_org_status
ON table_name(organization_id, status);

-- Date Index
CREATE INDEX IF NOT EXISTS idx_table_created
ON table_name(created_at);

-- Unique Index
CREATE UNIQUE INDEX IF NOT EXISTS idx_table_unique
ON table_name(unique_column);
```

---

## Common Validation Patterns

```sql
-- Required Field
INSERT INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES ('uuid', 'entity_id', 'attr_id', 'field_required', 'field_name != ""', 'Field is required', 'error');

-- Email Format
VALUES ('uuid', 'entity_id', 'attr_id', 'valid_email', 'REGEXP(email, "^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$")', 'Invalid email', 'error');

-- Minimum Length
VALUES ('uuid', 'entity_id', 'attr_id', 'min_length', 'LENGTH(field) >= 3', 'Minimum 3 characters', 'error');

-- Numeric Range
VALUES ('uuid', 'entity_id', 'attr_id', 'valid_range', 'field >= 0 AND field <= 100', 'Must be 0-100', 'error');

-- Date in Future
VALUES ('uuid', 'entity_id', 'attr_id', 'future_date', 'field > date("now")', 'Must be future date', 'warning');
```

---

## Function Handlers

```sql
-- API Handler
INSERT INTO entity_function_handler (id, function_id, handler_type, handler_reference)
VALUES ('uuid', 'func_id', 'api', '/api/entities/create');

-- Script Handler
VALUES ('uuid', 'func_id', 'script', '/scripts/entity/process.php');

-- SQL Handler
VALUES ('uuid', 'func_id', 'sql', 'sp_entity_function');

-- Class Method Handler
VALUES ('uuid', 'func_id', 'class', 'EntityManager::create');
```

---

## Domain Categories

```
COMMON        - Person, geography, shared entities
SECURITY      - Auth, permissions, credentials
ORGANIZATION  - Org structure, departments
HIRING        - Recruitment, vacancies, applications
WORKFLOW      - Processes, tasks, flows
FINANCE       - Money, transactions, invoices
INVENTORY     - Stock, products, warehouses
CRM           - Customers, leads, opportunities
HR            - Employees, payroll, benefits
EDUCATION     - Courses, students, grades
```

---

## Migration Checklist

```bash
# 1. Create file
touch metadata/NNN-domain_name.sql

# 2. Write metadata following template

# 3. Test migration
sqlite3 database/v4l.sqlite < metadata/NNN-domain_name.sql

# 4. Verify entities
sqlite3 database/v4l.sqlite "SELECT code, name FROM entity_definition WHERE domain='YOUR_DOMAIN'"

# 5. Verify tables
sqlite3 database/v4l.sqlite ".tables" | grep your_table

# 6. Check attributes
sqlite3 database/v4l.sqlite "SELECT code, name FROM entity_attribute WHERE entity_id='YOUR_ENTITY_UUID'"

# 7. Verify relationships
sqlite3 database/v4l.sqlite "SELECT relation_name FROM entity_relationship WHERE from_entity_id='YOUR_UUID'"
```

---

## Common Mistakes to Avoid

```
❌ entity_code in lowercase         → ✓ UPPERCASE_SNAKE_CASE
❌ Pluralizing entity names         → ✓ Use singular (PERSON, not PERSONS)
❌ Forgetting is_system for std fields → ✓ Mark id, created_at, etc.
❌ No is_label fields               → ✓ Set 2-3 label fields
❌ Missing foreign key indexes      → ✓ Index all FKs
❌ Not using INSERT OR IGNORE       → ✓ Always use OR IGNORE
❌ Skipping display_order           → ✓ Sequential from 1
❌ Missing relationship definitions → ✓ Define all FK relationships
❌ Wrong relation_type in relationships → ✓ Use relation_type, not relationship_type
❌ Inconsistent UUID patterns       → ✓ Use domain prefixes
```

---

## Testing New Entity

```sql
-- 1. Check entity exists
SELECT * FROM entity_definition WHERE code = 'YOUR_ENTITY_CODE';

-- 2. Check attributes
SELECT code, name, data_type, is_required, display_order
FROM entity_attribute
WHERE entity_id = 'YOUR_ENTITY_UUID'
ORDER BY display_order;

-- 3. Check table exists
SELECT name FROM sqlite_master WHERE type='table' AND name='your_table';

-- 4. Check relationships
SELECT er.relation_name, ed_from.code as from_entity, ed_to.code as to_entity
FROM entity_relationship er
JOIN entity_definition ed_from ON er.from_entity_id = ed_from.id
JOIN entity_definition ed_to ON er.to_entity_id = ed_to.id
WHERE er.from_entity_id = 'YOUR_UUID' OR er.to_entity_id = 'YOUR_UUID';

-- 5. Test insert
INSERT INTO your_table (id, name, created_at)
VALUES ('test-uuid', 'Test Record', datetime('now'));

-- 6. Clean up test
DELETE FROM your_table WHERE id = 'test-uuid';
```

---

## Reference Files

- **Full Rules**: `metadata/ENTITY_CREATION_RULES.md`
- **Example**: `metadata/010-process_flow_system.sql`
- **Existing Entities**: `metadata/001-initial.sql` through `metadata/009-hiring_domain.sql`

---

**Need More Help?** See `ENTITY_CREATION_RULES.md` for comprehensive documentation.
