# Entity Relationship Rules - Unified Reference

> **📏 Note:** This is a rules reference document. For domain-specific relationships, see individual domain files in this folder.

---

## Overview

This document defines the complete set of rules and conventions for creating and managing entity relationships across the V4L system.

---

## Relationship Legend

```
→  One-to-Many relationship (A → B means "A has many B")
← Many-to-One relationship (A ← B means "B belongs to A")
↔  Many-to-Many relationship (via junction table)
*  Required field/relationship
?  Optional field/relationship
```

---

## Relationship Types

### 1. One-to-One (1:1)
One entity instance relates to exactly one instance of another entity.

**Example:**
```
ORGANIZATION_BRANCH → POSTAL_ADDRESS (1:1)
  via postal_address_id
```

**Rules:**
- Use direct foreign key
- Foreign key should be unique
- Can be optional (nullable FK) or required

---

### 2. One-to-Many (1:M)
One entity instance relates to multiple instances of another entity.

**Example:**
```
PERSON → PERSON_EDUCATION (1:Many)
  One person has many education records
```

**Rules:**
- Foreign key on the "many" side
- Points to the "one" side
- Most common relationship type

---

### 3. Many-to-One (M:1)
Inverse of One-to-Many, viewed from the other direction.

**Example:**
```
PERSON_EDUCATION ← PERSON (Many:1)
  Many education records belong to one person
  via person_id
```

**Rules:**
- Same as One-to-Many, different perspective
- Foreign key on the entity with "Many:1" relationship

---

### 4. Many-to-Many (M:N)
Multiple instances of one entity relate to multiple instances of another.

**Example:**
```
ORGANIZATION_VACANCY ↔ WORKSTATION (Many:Many)
  via ORGANIZATION_VACANCY_WORKSTATION (junction table)
```

**Rules:**
- Requires junction/pivot table
- Junction table has FKs to both entities
- Junction table can have additional attributes

**Junction Table Pattern:**
```sql
CREATE TABLE entity_a_entity_b (
    id TEXT PRIMARY KEY,
    entity_a_id TEXT NOT NULL,
    entity_b_id TEXT NOT NULL,
    -- Additional attributes
    notes TEXT,
    -- Standard audit fields
    created_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    FOREIGN KEY(entity_a_id) REFERENCES entity_a(id),
    FOREIGN KEY(entity_b_id) REFERENCES entity_b(id),
    UNIQUE(entity_a_id, entity_b_id)
);
```

---

## Foreign Key Naming Conventions

### Standard Pattern
```
{referenced_entity}_id
```

**Examples:**
- `person_id` → references PERSON
- `organization_id` → references ORGANIZATION
- `popular_position_id` → references POPULAR_ORGANIZATION_POSITION

### Role-Based Naming
When multiple FKs reference the same entity, use role-based names:

```
created_by    → PERSON (who created)
updated_by    → PERSON (who last updated)
assigned_to   → PERSON (who is assigned)
offered_to    → PERSON (who receives offer)
offered_by    → PERSON (who makes offer)
main_admin_id → PERSON (organization owner)
```

### Composite Names
For popular entities or specific references:

```
popular_position_id     → POPULAR_ORGANIZATION_POSITION
organization_vacancy_id → ORGANIZATION_VACANCY
postal_address_id       → POSTAL_ADDRESS
```

---

## Foreign Key Constraints

### Referential Integrity

**Always define foreign key constraints:**
```sql
FOREIGN KEY(column_name) REFERENCES other_table(id)
```

### Cascade Options

**ON DELETE:**
```sql
-- Restrict (default) - Prevent deletion if references exist
FOREIGN KEY(person_id) REFERENCES person(id)

-- Cascade - Delete related records
FOREIGN KEY(person_id) REFERENCES person(id) ON DELETE CASCADE

-- Set NULL - Nullify FK when parent deleted
FOREIGN KEY(manager_id) REFERENCES person(id) ON DELETE SET NULL
```

**Best Practices:**
- Use `RESTRICT` (default) for most relationships
- Use `CASCADE` only for true composition (entity owns children)
- Use `SET NULL` for optional relationships where orphans are acceptable

**Examples:**
```sql
-- Person owns credentials → CASCADE
FOREIGN KEY(person_id) REFERENCES person(id) ON DELETE CASCADE

-- Organization has employees → RESTRICT (don't allow deletion if employees exist)
FOREIGN KEY(organization_id) REFERENCES organization(id)

-- Optional manager reference → SET NULL
FOREIGN KEY(manager_id) REFERENCES person(id) ON DELETE SET NULL
```

---

## Soft Delete Relationships

### Standard Approach
All entities use soft deletes (`deleted_at` column).

**Query Pattern:**
```sql
SELECT * FROM entity
WHERE foreign_key_id = ?
AND deleted_at IS NULL;
```

**Rules:**
1. **Never hard-delete** entities with relationships
2. **Always filter** `deleted_at IS NULL` in queries
3. **Cascade soft deletes** when appropriate

**Cascade Soft Delete Example:**
```sql
-- When soft-deleting person, also soft-delete their credentials
UPDATE person_credential
SET deleted_at = datetime('now')
WHERE person_id = ?;

UPDATE person
SET deleted_at = datetime('now')
WHERE id = ?;
```

---

## Optional vs Required Relationships

### Required Relationship (NOT NULL)
```sql
person_id TEXT NOT NULL
```

**When to use:**
- Relationship is essential to entity meaning
- Child cannot exist without parent
- Examples: person_credential.person_id, vacancy_application.vacancy_id

### Optional Relationship (NULL allowed)
```sql
manager_id TEXT NULL
```

**When to use:**
- Relationship is supplementary
- Entity can exist independently
- Examples: organization_branch.manager_id, job_offer.application_id

---

## Relationship Patterns

### Pattern 1: Hierarchical Relationships
Parent-child hierarchy within same entity type.

**Example:**
```
ORGANIZATION_BUILDING
  ↓ (optional parent)
ORGANIZATION_BRANCH
```

```sql
CREATE TABLE organization_building (
    id TEXT PRIMARY KEY,
    organization_id TEXT NOT NULL,
    branch_id TEXT,  -- Optional parent
    FOREIGN KEY(branch_id) REFERENCES organization_branch(id)
);
```

### Pattern 2: Exclusive Relationships
Entity belongs to ONE OF multiple parents (XOR relationship).

**Example:**
```
POSTAL_ADDRESS belongs to EITHER:
  - PERSON (via person_id)
  - ORGANIZATION (via organization_id)
BUT NOT BOTH
```

**Rules:**
- Exactly one FK must be set
- Others must be NULL
- Enforce at application level or with CHECK constraint

```sql
CREATE TABLE postal_address (
    id TEXT PRIMARY KEY,
    person_id TEXT,
    organization_id TEXT,
    FOREIGN KEY(person_id) REFERENCES person(id),
    FOREIGN KEY(organization_id) REFERENCES organization(id),
    CHECK (
        (person_id IS NOT NULL AND organization_id IS NULL) OR
        (person_id IS NULL AND organization_id IS NOT NULL)
    )
);
```

### Pattern 3: Polymorphic Relationships
Generic relationship to multiple entity types.

**Example:**
```
TASK_FLOW_INSTANCE relates to any entity via:
  entity_code + entity_record_id
```

```sql
CREATE TABLE task_flow_instance (
    id TEXT PRIMARY KEY,
    entity_code TEXT,  -- e.g., 'ORGANIZATION_VACANCY'
    entity_record_id TEXT,  -- FK to that entity
    ...
);
```

**Note:** No direct FK constraint (application-level validation).

### Pattern 4: Self-Referencing Relationships
Entity references itself.

**Example:**
```
PERSON
  ↓ (reports_to)
PERSON
```

```sql
CREATE TABLE person (
    id TEXT PRIMARY KEY,
    reports_to TEXT,
    FOREIGN KEY(reports_to) REFERENCES person(id)
);
```

---

## Cross-Domain Relationships

### Definition
Relationships that span multiple business domains.

**Example:**
```
PERSON (Person Domain)
  → ORGANIZATION_VACANCY (Hiring Domain)
  → TASK_INSTANCE (Process Flow Domain)
```

### Rules
1. **Document cross-domain links** in both domain files
2. **Use consistent FK naming** across domains
3. **Consider impact** when modifying cross-domain entities

---

## Relationship Metadata

### In entity_relationship Table

All relationships should be registered in metadata:

```sql
INSERT INTO entity_relationship (
    id,
    from_entity_id,  -- Source entity
    to_entity_id,    -- Target entity
    relation_type,   -- 'one-to-one', 'one-to-many', 'many-to-many'
    relation_name,   -- Descriptive name
    fk_field,        -- Foreign key column name
    description
) VALUES (
    ?,
    'person_id',
    'person_credential_id',
    'one-to-many',
    'person_to_credentials',
    'person_id',
    'A person can have multiple login credentials'
);
```

### Relation Naming Convention
```
{from_entity}_to_{to_entity}

Examples:
- person_to_education
- organization_to_vacancies
- vacancy_to_applications
```

---

## Indexing Relationships

### Always Index Foreign Keys

```sql
CREATE INDEX IF NOT EXISTS idx_{table}_{column}
ON table_name(column_name);
```

**Examples:**
```sql
CREATE INDEX IF NOT EXISTS idx_person_education_person_id
ON person_education(person_id);

CREATE INDEX IF NOT EXISTS idx_vacancy_application_vacancy_id
ON vacancy_application(vacancy_id);

CREATE INDEX IF NOT EXISTS idx_vacancy_application_applicant_id
ON vacancy_application(applicant_id);
```

### Composite Indexes
For junction tables and common query patterns:

```sql
CREATE INDEX IF NOT EXISTS idx_org_vacancy_ws_both
ON organization_vacancy_workstation(organization_vacancy_id, organization_workstation_id);
```

---

## Relationship Validation

### Application-Level Checks

1. **Existence Validation:**
   ```sql
   -- Verify parent exists before creating child
   SELECT id FROM parent_table WHERE id = ? AND deleted_at IS NULL;
   ```

2. **Uniqueness Validation:**
   ```sql
   -- Ensure unique relationship (e.g., no duplicate applications)
   SELECT COUNT(*) FROM vacancy_application
   WHERE vacancy_id = ? AND applicant_id = ?;
   ```

3. **Exclusive Relationship Validation:**
   ```sql
   -- Ensure exactly one FK is set
   IF (person_id IS NULL AND organization_id IS NULL) OR
      (person_id IS NOT NULL AND organization_id IS NOT NULL)
   THEN RAISE ERROR
   ```

---

## Common Relationship Queries

### Get Related Entities (One-to-Many)
```sql
SELECT child.*
FROM child_table child
WHERE child.parent_id = ?
AND child.deleted_at IS NULL
ORDER BY child.created_at DESC;
```

### Get Parent Entity (Many-to-One)
```sql
SELECT parent.*
FROM parent_table parent
JOIN child_table child ON child.parent_id = parent.id
WHERE child.id = ?
AND parent.deleted_at IS NULL;
```

### Get Related via Junction (Many-to-Many)
```sql
SELECT entity_b.*
FROM entity_b
JOIN junction_table j ON j.entity_b_id = entity_b.id
WHERE j.entity_a_id = ?
AND entity_b.deleted_at IS NULL
AND j.deleted_at IS NULL;
```

### Count Related Entities
```sql
SELECT parent.id, COUNT(child.id) as child_count
FROM parent_table parent
LEFT JOIN child_table child ON child.parent_id = parent.id
    AND child.deleted_at IS NULL
WHERE parent.deleted_at IS NULL
GROUP BY parent.id;
```

---

## Best Practices

### ✅ DO:
1. **Always use foreign key constraints** for referential integrity
2. **Index all foreign keys** for performance
3. **Use soft deletes** for entities with relationships
4. **Document cross-domain relationships** in both domain files
5. **Use consistent naming conventions** for FKs
6. **Register relationships** in entity_relationship metadata
7. **Filter deleted_at IS NULL** in all queries
8. **Use role-based names** for multiple FKs to same entity

### ❌ DON'T:
1. **Don't hard-delete** entities with relationships
2. **Don't omit FK constraints** (breaks referential integrity)
3. **Don't forget to index FKs** (performance issues)
4. **Don't use abbreviations** in FK names (be explicit)
5. **Don't create circular dependencies** (A → B → A)
6. **Don't skip soft-delete filters** in queries
7. **Don't use CASCADE DELETE** unless truly needed
8. **Don't create orphaned records** (validate parent exists)

---

## Checklist for New Relationships

```
□ Define relationship type (1:1, 1:M, M:N)
□ Choose foreign key name (follow naming conventions)
□ Determine if required or optional
□ Add FK constraint to DDL
□ Choose cascade behavior (RESTRICT, CASCADE, SET NULL)
□ Create index on FK column
□ Register in entity_relationship metadata
□ Add relationship to entity_attribute metadata
□ Document in domain-specific relationship file
□ Test referential integrity
□ Test soft-delete behavior
□ Add application-level validation if needed
```

---

## Domain-Specific Documentation

For detailed relationships within each domain, see:

- [Person & Identity Domain](PERSON_IDENTITY_DOMAIN.md)
- [Geographic & Address Domain](GEOGRAPHIC_DOMAIN.md)
- [Organization Domain](ORGANIZATION_DOMAIN.md)
- [Popular Organization Structure](POPULAR_ORGANIZATION_STRUCTURE.md)
- [Hiring & Vacancy Domain](HIRING_VACANCY_DOMAIN.md)
- [Process Flow System](PROCESS_FLOW_DOMAIN.md)
- [Permissions & Security](PERMISSIONS_SECURITY_DOMAIN.md)

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **All Domain Relationships:** [README.md](README.md)
- **Migration Guide:** [/guides/database/MIGRATION_GUIDE.md](../../guides/database/MIGRATION_GUIDE.md)

---

**Last Updated:** 2025-10-31
**Version:** 1.0
