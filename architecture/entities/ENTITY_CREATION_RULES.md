# Entity Creation Rules - Metadata Folder

> **📚 Note:** This is a rules reference document. For detailed guides, tutorials, and complete documentation, see the `/guides` folder.

## Overview

This document defines the complete set of rules and conventions for creating entity metadata files in the `/metadata` folder. All entity definitions, attributes, relationships, functions, and validation rules follow these standards.

---

## File Naming Convention

### Pattern: `NNN-domain_name.sql`

- **NNN**: Sequential 3-digit number (001, 002, 003, etc.)
- **domain_name**: Descriptive name in lowercase with underscores
- **Extension**: Always `.sql`

### Examples:
```
001-initial.sql                    # Core system entities (geography)
002-person.sql                     # Person and credential entities
003-person_education_and_skills.sql
004-popular_organization.sql
005-organization_positions_and_requirements.sql
006-entity_permissions.sql
007-organization.sql
008-organization_infrastructure.sql
009-hiring_domain.sql
010-process_flow_system.sql
```

### Ordering Rules:
1. **001-initial.sql** - Always contains core DDL (entity_definition, entity_attribute, etc.)
2. **002-0xx** - Foundation entities (person, organization, geography)
3. **0xx+** - Domain-specific entities (hiring, workflow, etc.)
4. Dependencies must be created before dependents

---

## File Structure

Every metadata file MUST follow this structure:

```sql
-- =====================================================================
-- TITLE: Brief description
-- Generated: YYYY-MM-DD (Timezone)
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- DDL (if creating new tables)
-- =========================================
CREATE TABLE IF NOT EXISTS table_name (...);

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (...);

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (...);

-- =========================================
-- 3. Entity Functions (optional)
-- =========================================
INSERT OR IGNORE INTO entity_function (...);

-- =========================================
-- 4. Function Handlers (optional)
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (...);

-- =========================================
-- 5. Entity Relationships (optional)
-- =========================================
INSERT OR IGNORE INTO entity_relationship (...);

-- =========================================
-- 6. Validation Rules (optional)
-- =========================================
INSERT OR IGNORE INTO entity_validation_rule (...);

-- =========================================
-- 7. Sample/Seed Data (optional)
-- =========================================
INSERT OR IGNORE INTO table_name (...);
```

---

## 1. Entity Definition Rules

### Table: `entity_definition`

```sql
INSERT OR IGNORE INTO entity_definition (
    id,              -- UUID (proper UUID format) (required)
    code,            -- UPPERCASE_SNAKE_CASE (required, unique)
    name,            -- Human-readable name (required)
    description,     -- Full description (optional)
    domain,          -- Domain category (optional)
    table_name,      -- Actual database table name (lowercase_snake_case)
    is_active        -- 1 or 0 (default: 1)
) VALUES (
    'uuid-here',
    'ENTITY_CODE',
    'Entity Display Name',
    'Detailed description of what this entity represents',
    'DOMAIN_NAME',
    'table_name',
    1
);
```

### Rules:

#### `id` (UUID)
- **Format**: Standard UUID v4
- **Pattern**: `xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx`
- **Generation**: Must be unique per entity
- **Recommendation**: Use sequential UUIDs for related entities


#### `code` (Entity Code)
- **Format**: UPPERCASE_SNAKE_CASE
- **Rules**:
  - Use nouns only
  - Be descriptive but concise
  - Singular form (PERSON, not PERSONS)
  - Use underscores to separate words
  - Maximum 50 characters recommended

**Examples:**
```
✓ PERSON
✓ ORGANIZATION_VACANCY
✓ PROCESS_GRAPH
✓ VACANCY_APPLICATION
✓ ENTITY_PERMISSION_DEFINITION

✗ person (not uppercase)
✗ persons (plural)
✗ OrganizationVacancy (not snake_case)
```

#### `name` (Display Name)
- **Format**: Title Case
- **Rules**:
  - Human-readable
  - Use spaces
  - Singular form
  - 3-50 characters

**Examples:**
```
✓ Person
✓ Organization Vacancy
✓ Process Graph
✓ Vacancy Application
```

#### `description` (Description)
- **Format**: Full sentence
- **Rules**:
  - Start with a verb (Stores, Manages, Tracks, etc.)
  - End with period
  - Be specific about what the entity represents
  - 10-200 characters

**Examples:**
```
✓ "Stores individual person profile and personal details"
✓ "Job vacancies or open positions within an organization"
✓ "Process template definitions with versioning"
```

#### `domain` (Domain Category)
- **Format**: UPPERCASE
- **Common Values**:
  - `COMMON` - Shared entities (person, geography)
  - `SECURITY` - Authentication/authorization
  - `ORGANIZATION` - Organization structure
  - `HIRING` - Recruitment and hiring
  - `WORKFLOW` - Process and workflow
  - `FINANCE` - Financial data
  - `INVENTORY` - Stock management
- **Rules**: Use existing domains when possible, create new only when necessary

#### `table_name` (Database Table)
- **Format**: lowercase_snake_case
- **Rules**:
  - Must match actual database table name exactly
  - Singular form
  - No prefixes or suffixes (unless required)

**Examples:**
```
✓ person
✓ organization_vacancy
✓ process_graph
✓ task_flow_instance
```

#### `is_active`
- **Values**: `1` (active) or `0` (inactive)
- **Default**: `1`
- **Purpose**: Soft-disable entities without deletion

---

## 2. Entity Attribute Rules

### Table: `entity_attribute`

```sql
INSERT OR IGNORE INTO entity_attribute (
    id,                -- UUID (required)
    entity_id,         -- FK to entity_definition.id (required)
    code,              -- lowercase_snake_case (required)
    name,              -- Human-readable (required)
    data_type,         -- Data type (required)
    is_required,       -- 1 or 0 (default: 0)
    is_unique,         -- 1 or 0 (default: 0)
    is_system,         -- 1 or 0 (default: 0)
    is_label,          -- 1 or 0 (default: 0)
    default_value,     -- Default value (optional)
    min_value,         -- Min validation (optional)
    max_value,         -- Max validation (optional)
    enum_values,       -- JSON for enums (optional)
    validation_regex,  -- Regex pattern (optional)
    description,       -- Description (optional)
    display_order      -- Integer (required)
) VALUES (...);
```

### Rules:

#### `id` (UUID)
- Generate unique UUID for each attribute
- Recommendation: Use sequential within entity

#### `entity_id` (Foreign Key)
- Must match parent `entity_definition.id`
- Establishes entity-attribute relationship

#### `code` (Attribute Code)
- **Format**: lowercase_snake_case
- **Rules**:
  - Must match database column name
  - No special characters except underscore
  - Descriptive and concise
  - Foreign keys end with `_id`

**Standard System Fields** (always include):
```sql
id              -- Primary key (UUID)
created_at      -- Timestamp (auto)
updated_at      -- Timestamp (auto)
deleted_at      -- Soft delete timestamp
version_no      -- Optimistic locking
created_by      -- Who created
updated_by      -- Who made last change
```

**Examples:**
```
✓ first_name
✓ organization_id
✓ popular_position_id
✓ is_urgent
✓ application_date

✗ firstName (camelCase)
✗ org_id (abbreviation, unless standard)
```

#### `name` (Display Name)
- **Format**: Title Case with spaces
- Human-readable label for UI

**Examples:**
```
code: first_name          → name: "First Name"
code: organization_id     → name: "Organization ID"
code: is_urgent          → name: "Is Urgent"
```

#### `data_type` (Data Type)
**Supported Types:**
```
text           - String/VARCHAR
integer        - Integer number
number         - Decimal/float
boolean        - True/false (stored as 0/1)
date           - Date only (YYYY-MM-DD)
datetime       - Date and time
time           - Time only
uuid           - UUID format
json           - JSON object/array
enum_strings   - List of string values
enum_objects   - List of {value, label} objects
file           - File reference
```

**Type Selection Guide:**
```
Names, titles, descriptions     → text
IDs, foreign keys              → text or uuid
Counts, quantities             → integer
Money, percentages, decimals   → number
Yes/No flags                   → boolean
Birth dates, deadlines         → date
Timestamps, event times        → datetime
Dropdown selections            → enum_strings or enum_objects
```

#### `is_required` (Required Field)
- **Values**: `1` (required) or `0` (optional)
- **Default**: `0`
- **Rules**:
  - Foreign keys usually required (except optional relationships)
  - Primary keys always required
  - System fields (id, created_at, etc.) always required

#### `is_unique` (Unique Constraint)
- **Values**: `1` (unique) or `0` (not unique)
- **Default**: `0`
- **Examples**: username, email, code fields

#### `is_system` (System Field)
- **Values**: `1` (system) or `0` (user)
- **Default**: `0`
- **Purpose**: Hide from user forms
- **System fields**: id, created_at, updated_at, deleted_at, version_no, created_by, updated_by

#### `is_label` (Label Field)
- **Values**: `1` (label) or `0` (not label)
- **Default**: `0`
- **Purpose**: Used in dropdowns, listings, search results
- **Typical labels**: name fields, title fields, code fields
- **Recommendation**: 2-3 label fields per entity

**Example:**
```sql
-- PERSON entity labels
first_name  → is_label = 1
last_name   → is_label = 1
email       → is_label = 0

-- Result: "John Smith" shown in dropdowns
```

#### `default_value` (Default Value)
- String representation of default
- Examples: `'PENDING'`, `'1'`, `'datetime("now")'`

#### `enum_values` (Enum Values)
**For enum_strings:**
```json
["Option 1", "Option 2", "Option 3"]
```

**For enum_objects:**
```json
{
  "options": [
    {"value": "PENDING", "label": "Pending"},
    {"value": "APPROVED", "label": "Approved"},
    {"value": "REJECTED", "label": "Rejected"}
  ]
}
```

#### `validation_regex` (Regex Pattern)
- Standard regex pattern for validation
- Examples:
  ```
  Email: ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$
  Phone: ^\+?[0-9]{10,15}$
  UUID:  ^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$
  ```

#### `display_order` (Display Order)
- **Type**: Integer
- **Purpose**: Order of fields in forms
- **Rules**:
  - Start from 1
  - Increment by 1
  - Group related fields together
  - Foreign keys typically first
  - Important fields before optional fields

---

## 3. Entity Function Rules

### Table: `entity_function`

```sql
INSERT OR IGNORE INTO entity_function (
    id,                    -- UUID (required)
    entity_id,             -- FK to entity_definition (required)
    function_code,         -- lowercase_snake_case (required)
    function_name,         -- Display name (optional)
    function_description,  -- Description (optional)
    parameters,            -- JSON array (optional)
    return_type,           -- Return type (optional)
    is_system,             -- 1 or 0 (default: 0)
    is_active              -- 1 or 0 (default: 1)
) VALUES (...);
```

### Common Functions:
```
create_[entity]      - Create new record
update_[entity]      - Update existing record
delete_[entity]      - Delete record
search_[entity]      - Search records
validate_[entity]    - Validate data
get_[computed_field] - Compute derived value
```

### Parameters Format:
```json
[
  {"name": "person_id", "type": "text"},
  {"name": "data", "type": "json"},
  {"name": "options", "type": "json"}
]
```

---

## 4. Function Handler Rules

### Table: `entity_function_handler`

```sql
INSERT OR IGNORE INTO entity_function_handler (
    id,                 -- UUID (required)
    function_id,        -- FK to entity_function (required)
    handler_type,       -- Type of handler (required)
    handler_reference,  -- Reference to implementation (required)
    is_active           -- 1 or 0 (default: 1)
) VALUES (...);
```

### Handler Types:
```
sql     - SQL stored procedure: "sp_function_name"
api     - API endpoint: "/api/path/to/endpoint"
script  - PHP script: "/scripts/path/to/script.php"
class   - PHP class method: "ClassName::methodName"
```

---

## 5. Entity Relationship Rules

### Table: `entity_relationship`

```sql
INSERT OR IGNORE INTO entity_relationship (
    id,               -- UUID (required)
    from_entity_id,   -- FK to entity_definition (required)
    to_entity_id,     -- FK to entity_definition (required)
    relation_type,    -- Type of relationship (optional)
    relation_name,    -- Name of relationship (required)
    fk_field,         -- Foreign key column (required)
    description       -- Description (optional)
) VALUES (...);
```

### Relation Types:
```
one-to-one     - 1:1 relationship
one-to-many    - 1:N relationship
many-to-one    - N:1 relationship
many-to-many   - N:N relationship (via junction table)
```

### Naming Convention for `relation_name`:
```
Pattern: from_entity_to_to_entity
Example: person_credential_to_person
         task_instance_to_flow_instance
         vacancy_application_to_vacancy
```

---

## 6. Validation Rule Rules

### Table: `entity_validation_rule`

```sql
INSERT OR IGNORE INTO entity_validation_rule (
    id,               -- UUID (required)
    entity_id,        -- FK to entity_definition (required)
    attribute_id,     -- FK to entity_attribute (required)
    rule_name,        -- Name of rule (required)
    rule_expression,  -- Validation expression (required)
    error_message,    -- Error message (required)
    severity          -- error, warning, info (required)
) VALUES (...);
```

### Common Validations:
```
required        : field_name != ""
email_format    : REGEXP(field, email_pattern)
min_length      : LENGTH(field) >= 5
max_length      : LENGTH(field) <= 100
numeric_range   : field >= 0 AND field <= 100
date_future     : field > datetime('now')
conditional     : IF condition THEN validation
```

---

## 7. DDL (Table Creation) Rules

### When to Include DDL:

1. **First file (001-initial.sql)** - Always includes core metadata tables
2. **New domain files** - Include CREATE TABLE for domain entities
3. **Use `CREATE TABLE IF NOT EXISTS`** - Prevents errors on re-run

### Table Naming:
- **Format**: lowercase_snake_case
- **Match entity `table_name` exactly**

### Standard Columns (ALL tables must have):
```sql
CREATE TABLE table_name (
    id TEXT PRIMARY KEY,                         -- UUID
    created_at TEXT DEFAULT (datetime('now')),   -- Auto timestamp
    updated_at TEXT DEFAULT (datetime('now')),   -- Auto timestamp
    deleted_at TEXT,                             -- Soft delete
    version_no INTEGER DEFAULT 1,                -- Optimistic locking
    created_by TEXT,                             -- Audit trail
    updated_by TEXT,                             -- Audit trail

    -- Your custom columns here

    FOREIGN KEY(created_by) REFERENCES person(id)
    FOREIGN KEY(updated_by) REFERENCES person(id)
);
```

### Index Creation:
```sql
CREATE INDEX IF NOT EXISTS idx_table_column ON table_name(column_name);
CREATE INDEX IF NOT EXISTS idx_table_fk ON table_name(foreign_key_id);
CREATE INDEX IF NOT EXISTS idx_table_status ON table_name(status);
CREATE INDEX IF NOT EXISTS idx_table_dates ON table_name(created_at);
```

### Foreign Key Constraints:
```sql
FOREIGN KEY(column_name) REFERENCES other_table(id)
FOREIGN KEY(column_name) REFERENCES other_table(id) ON DELETE CASCADE
FOREIGN KEY(column_name) REFERENCES other_table(id) ON DELETE SET NULL
```

---

## 8. UUID Generation Rules


#### Format:
```
Standard UUID v4:
xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
```

---

## 9. INSERT OR IGNORE Usage

### Always use `INSERT OR IGNORE`:
```sql
INSERT OR IGNORE INTO entity_definition (...)
VALUES (...);
```

### Why:
- Allows re-running migration scripts
- Prevents duplicate key errors
- Safe for production deployments
- Idempotent operations

### When NOT to use:
- When you need to UPDATE existing records
- Use `INSERT OR REPLACE` for upsert behavior

---

## 10. Comments and Documentation

### Header Comment (Required):
```sql
-- =====================================================================
-- DOMAIN_NAME Metadata
-- Description of what this file contains
-- Generated: 2025-10-22
-- =====================================================================
```

### Section Comments (Required):
```sql
-- =========================================
-- 1. Entity Definition: ENTITY_NAME
-- =========================================
```

### Inline Comments (Optional but Recommended):
```sql
-- Foreign Keys
('uuid','entity_id','organization_id','Organization ID','text',1,0,0,NULL,'Reference to ORGANIZATION entity',1),

-- Core Fields
('uuid','entity_id','title','Title','text',1,1,0,NULL,'Title of the record',2),

-- Status and Flags
('uuid','entity_id','is_active','Is Active','boolean',0,0,0,NULL,'Whether record is active',3),
```

---

## 11. Common Patterns

### Pattern 1: Simple Entity with Foreign Keys
```sql
-- Entity Definition
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES ('uuid', 'ENTITY_NAME', 'Entity Name', 'Description', 'DOMAIN', 'table_name');

-- Attributes
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, display_order)
VALUES
-- Foreign Keys
('uuid', 'entity_id', 'parent_id', 'Parent ID', 'text', 1, 0, 1),
('uuid', 'entity_id', 'created_by', 'Created By', 'text', 1, 0, 2),
-- Core Fields
('uuid', 'entity_id', 'name', 'Name', 'text', 1, 1, 3),
('uuid', 'entity_id', 'description', 'Description', 'text', 0, 0, 4),
-- Status
('uuid', 'entity_id', 'status', 'Status', 'enum_objects', 1, 0, 5);

-- Relationship
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field)
VALUES ('uuid', 'this_entity_id', 'parent_entity_id', 'many-to-one', 'entity_to_parent', 'parent_id');
```

### Pattern 2: Junction Table (Many-to-Many)
```sql
-- Example: ORGANIZATION_VACANCY_WORKSTATION
INSERT OR IGNORE INTO entity_definition (id, code, name, ...)
VALUES ('uuid', 'ENTITY_A_ENTITY_B', 'Entity A Entity B', ...);

-- Attributes (just foreign keys)
INSERT OR IGNORE INTO entity_attribute (...)
VALUES
('uuid', 'entity_id', 'entity_a_id', 'Entity A ID', 'text', 1, 0, 1),
('uuid', 'entity_id', 'entity_b_id', 'Entity B ID', 'text', 1, 0, 2),
('uuid', 'entity_id', 'notes', 'Notes', 'text', 0, 0, 3);
```

### Pattern 3: Enum Entity
```sql
-- Attributes with enum_objects
('uuid', 'entity_id', 'status', 'Status', 'enum_objects', 1, 0, 0,
 '{"options":[{"value":"DRAFT","label":"Draft"},{"value":"PUBLISHED","label":"Published"}]}',
 'Current status', 5);
```

---

## 12. Data Folder (`metadata/data/`)

### Purpose:
- Seed/reference data
- Lookup tables
- Enumerations
- Master data

### Naming: `NNN-description.sql`
```
001-continents.sql
002-enum_education_levels.sql
014-entity_permission_definitions.sql
```

### Content:
```sql
-- Direct inserts to application tables (not metadata tables)
INSERT INTO continent (id, code, name) VALUES (...);
INSERT INTO enum_education_level (id, code, name) VALUES (...);
```

---

## 13. Foreign Key Rendering Rules

### Overview

The system uses **adaptive UI rendering** for foreign key fields based on the number of available options in the related entity. This provides an optimal user experience by automatically selecting the most appropriate input component.

### Rendering Thresholds

| Number of Options | UI Component | Method | Single / Multiple Selection | Use Case |
|-------------------|--------------|---------|------------------------------|----------|
| **< 8** | Radio Buttons / Checkboxes | `generateRadioField()` / `generateCheckboxField()` | Single / Multiple | Few options – all visible at once |
| **8 – 49** | HTML Select Dropdown | `generateSelectField()` | Single / Multiple (`multiple=true`) | Medium number – dropdown list, optionally multi-select |
| **50+** | Autocomplete / Typeahead | `generateAutocompleteField()` | Single / Multiple (`multi=true`) | Many options – searchable input with optional multi-select |



---

### 1. Radio Buttons / Check boxes (< 8 options)

**When Used:** Entities with fewer than 8 records

**Characteristics:**
- All options displayed as vertical list
- User can see all choices without interaction
- Best for: Status fields, categories, small lookup tables
- No scrolling required
- Immediate visual feedback

**Typical Entities:**
```
Gender (2-3 options)
Account Status (3-5 options)
Priority Level (3-4 options)
Document Type (5-7 options)
```

---

### 2. HTML Select Dropdown (8-49 options)

**When Used:** Entities with 8 to 49 records

**Characteristics:**
- Standard HTML `<select>` dropdown
- Placeholder: "-- Select [Entity Name] --"
- Loads up to 1,000 records maximum
- User must click to reveal options
- Native browser dropdown behavior
- Works on all devices without JavaScript

**Typical Entities:**
```
Department (10-30 options)
Document Category (15-25 options)
State/Province (20-50 options)
Job Title (25-40 options)
Product Category (30-45 options)
```

---

### 3. Autocomplete/Typeahead (50+ options)

**When Used:** Entities with 50 or more records

**Characteristics:**
- Text input with dynamic search suggestions
- Uses AJAX to fetch matching results as user types
- Two fields: visible text input + hidden field storing UUID
- Placeholder: "Type to search [Entity Name]..."
- Prevents overwhelming dropdowns with hundreds of options
- Provides instant search filtering

**Typical Entities:**
```
Person (hundreds/thousands)
Organization (large list)
University (500+ options)
City (thousands)
Product (extensive catalog)
Customer (large database)
```


**Backend API:** `public/api/fk-autocomplete.php`
- Handles AJAX search requests
- Returns JSON array of matching records
- Supports custom formatting functions

---

### Label Building Logic

All three rendering methods use the same label construction process:

#### Step 1: Check for Special Formatting Functions

Some entities have custom formatting logic:

```php
if ($targetEntity['code'] === 'POSTAL_ADDRESS') {
    $displayLabel = $this->formatPostalAddressLabel($record);
} elseif ($targetEntity['code'] === 'VACANCY_APPLICATION') {
    $displayLabel = $this->formatVacancyApplicationLabel($record);
} else {
    $displayLabel = $this->buildDisplayLabel($record, $labelFields);
}
```

**Current Special Formats:**
- `POSTAL_ADDRESS`: "123 Main St, City, State ZIP"
- `VACANCY_APPLICATION`: "John Smith for Senior Developer at Tech Solutions Inc (Oct 15, 2025)"

#### Step 2: Use `is_label` Fields

Attributes marked with `is_label = 1` are automatically used for display:

```sql
-- Example: PERSON entity
INSERT INTO entity_attribute (id, entity_id, code, name, is_label, display_order)
VALUES
('uuid-1', 'person-id', 'first_name', 'First Name', 1, 1),
('uuid-2', 'person-id', 'last_name', 'Last Name', 1, 2),
('uuid-3', 'person-id', 'email', 'Email', 0, 3);

-- Result in dropdown: "John Smith" (from first_name + last_name)
```

**Label Construction:**
```php
$displayParts = [];
foreach ($labelFields as $field) {
    if (isset($record[$field]) && !empty($record[$field])) {
        $displayParts[] = $record[$field];
    }
}
return implode(' - ', $displayParts);
```

**Examples:**
```
Person:         "John Smith" (first_name + last_name)
Organization:   "Tech Solutions Inc" (name or short_name)
Department:     "Engineering - Software" (name + code)
Position:       "Senior Developer" (position_name)
```

#### Step 3: Fallback Logic

If no label fields have values:

```php
return $record['name'] ?? $record['code'] ?? substr($record['id'], 0, 8);
```

**Fallback order:**
1. `name` field (if exists)
2. `code` field (if exists)
3. First 8 characters of `id`

---

### UX Design Rationale

#### Why < 8 for Radio Buttons?
- **Miller's Law**: Humans can comfortably process 7±2 items at once
- All options visible without scrolling
- Faster selection (no clicking required to see options)
- Better for mobile (large touch targets)

#### Why 8-49 for Dropdowns?
- Standard HTML select remains usable without excessive scrolling
- Native browser behavior (familiar to users)
- Works without JavaScript
- Accessible for screen readers
- Reasonable scrolling distance

#### Why 50+ for Autocomplete?
- Scrolling through 50+ options is poor UX
- Search is more efficient than scanning
- Reduces cognitive load
- Faster for users who know what they want
- Essential for large datasets (hundreds/thousands)

---

### Best Practices for FK Display

#### 1. Always Set `is_label` Fields

Mark 2-3 meaningful attributes as labels:

```sql
-- Good: Person with clear labels
('uuid', 'person-id', 'first_name', 'First Name', 'text', 1, 1, 0, ..., 1),
('uuid', 'person-id', 'last_name', 'Last Name', 'text', 1, 1, 0, ..., 2),
('uuid', 'person-id', 'email', 'Email', 'text', 0, 0, 0, ..., 3);  -- Not a label

-- Bad: No labels defined (will fallback to 'name' or 'code')
```

#### 2. Label Field Order Matters

Fields are joined in the order they appear. Use `display_order` wisely:

```sql
-- Result: "John Smith" (correct)
first_name (display_order: 1, is_label: 1)
last_name (display_order: 2, is_label: 1)

-- Result: "Smith John" (incorrect)
last_name (display_order: 1, is_label: 1)
first_name (display_order: 2, is_label: 1)
```

#### 3. Consider Entity Size When Designing

If you expect an entity to grow beyond 50 records:
- Set appropriate `is_label` fields for search matching
- Ensure labels are descriptive and unique
- Test autocomplete behavior early

#### 4. Create Custom Formatters for Complex Entities

For entities with complex relationships (like `VACANCY_APPLICATION`):

```php
// lib/PageGenerator.php
private function formatComplexEntityLabel($record) {
    // Build custom display string
    // Join multiple related entities
    // Format dates, combine fields, etc.
}
```

Then reference in the rendering methods.

---

### Testing Your Entity's Display

After creating a new entity, test how it renders:

```sql
-- Check current record count
SELECT COUNT(*) FROM your_table WHERE deleted_at IS NULL;

-- If count < 8:   Will render as radio buttons
-- If count 8-49:  Will render as dropdown
-- If count 50+:   Will render as autocomplete
```

**Simulate different counts:**
1. Add 5 test records → See radio buttons
2. Add 20 test records → See dropdown
3. Add 100 test records → See autocomplete with search

---

### Code Reference Summary

| Component | File | Lines |
|-----------|------|-------|
| Main rendering logic | `lib/PageGenerator.php` | 612-622 |
| Radio button generation | `lib/PageGenerator.php` | 628-660 |
| Select dropdown generation | `lib/PageGenerator.php` | 665-693 |
| Autocomplete generation | `lib/PageGenerator.php` | 698-743 |
| Label building | `lib/PageGenerator.php` | 748-763 |
| Postal address formatter | `lib/PageGenerator.php` | ~765-800 |
| Vacancy application formatter | `lib/PageGenerator.php` | ~802-850 |
| Autocomplete API | `public/api/fk-autocomplete.php` | Full file |

---

### Summary

The foreign key rendering system automatically adapts to provide the best user experience based on dataset size:
- **Small datasets (< 8)**: Radio buttons for instant visibility
- **Medium datasets (8-49)**: Dropdown for standard selection
- **Large datasets (50+)**: Autocomplete for efficient search

**Key Takeaways:**
1. Always define `is_label` fields for foreign key entities
2. Order label fields logically (display_order matters)
3. Test FK display with realistic data volumes
4. Create custom formatters for complex entities
5. System automatically chooses optimal UI component

---

## 14. Best Practices

### ✅ DO:
1. Use consistent UUID prefixes per domain
2. Add comprehensive descriptions
3. Set appropriate `is_label` fields
4. Include validation rules
5. Document relationships
6. Use `INSERT OR IGNORE` for idempotency
7. Order attributes logically (FKs first, then important fields)
8. Set display_order sequentially
9. Include indexes for foreign keys
10. Add comments for complex logic

### ❌ DON'T:
1. Use abbreviations in entity codes
2. Mix naming conventions
3. Skip system fields (id, created_at, etc.)
4. Forget foreign key constraints
5. Use database-specific syntax (stay SQLite compatible)
6. Hard-code UUIDs without pattern
7. Skip descriptions
8. Create circular dependencies

---

## 15. Checklist for New Entity

```
□ Choose sequential file number (NNN)
□ Create file: metadata/NNN-domain_name.sql
□ Add header comment with title and date
□ Enable foreign keys: PRAGMA foreign_keys = ON;

□ CREATE TABLE with:
  □ id TEXT PRIMARY KEY
  □ created_at, updated_at, deleted_at
  □ version_no, created_by, updated_by
  □ Your custom columns
  □ Foreign key constraints
  □ Indexes

□ INSERT entity_definition:
  □ Generate UUID with appropriate prefix
  □ Set UPPERCASE_SNAKE_CASE code
  □ Write clear description
  □ Set domain
  □ Match table_name to CREATE TABLE

□ INSERT entity_attribute for each column:
  □ System fields (mark is_system = 1)
  □ Foreign keys (end with _id)
  □ Core fields
  □ Status/enum fields
  □ Set is_required appropriately
  □ Set 2-3 is_label fields
  □ Sequential display_order

□ INSERT entity_relationship (if applicable):
  □ Define relation_type
  □ Name relationship clearly
  □ Specify fk_field

□ INSERT entity_function (if needed):
  □ CRUD operations
  □ Business logic functions
  □ Include handlers

□ INSERT validation rules (if needed):
  □ Required fields
  □ Format validations
  □ Business rules

□ Test migration:
  □ Run: sqlite3 database/v4l.sqlite < metadata/NNN-domain_name.sql
  □ Verify no errors
  □ Check tables created
  □ Verify entity definitions registered
```


---

## Summary

Following these rules ensures:
- **Consistency** across all entity definitions
- **Maintainability** through clear patterns
- **Reusability** with standard structures
- **Safety** with idempotent operations
- **Documentation** built into metadata
- **Quality** through validation and constraints

All entity metadata in the `/metadata` folder must adhere to these standards.
