# Data Seeds

This directory contains SQL files for seeding initial data into entity tables.

## Naming Convention

Data seed files should follow this pattern:
- `NNN-entity-name.sql` (e.g., `001-continents.sql`, `002-countries.sql`)
- Files are executed in alphabetical order
- Each file is tracked and will only run once

## File Structure

Each data seed file should:
1. Start with a header comment describing the data
2. Contain INSERT statements for the entity data
3. Use proper field names matching the entity table schema
4. Include all required fields (id, created_at, updated_at, etc.)

## Example

```sql
-- ============================================
-- DATA SEED: Entity Name
-- Description of the data
-- ============================================

INSERT INTO entity_table (id, field1, field2, created_at, updated_at)
VALUES
('id-001', 'value1', 'value2', datetime('now'), datetime('now')),
('id-002', 'value1', 'value2', datetime('now'), datetime('now'));
```

## Execution

Data seeds are automatically executed by the migration script:
```bash
php scripts/migrate.php
```

Seeds are tracked in the `data_seeds` table in `v4l.sqlite` to ensure they only run once.

## Best Practices

1. **Use descriptive IDs**: Use meaningful IDs like `cont-001` instead of UUIDs for reference data
2. **Include all data at once**: Put all related data in a single file to maintain consistency
3. **Order matters**: If data has foreign key dependencies, ensure parent data is in earlier files
4. **Idempotent**: Design inserts to be safe if accidentally run multiple times (though tracking prevents this)
5. **Comments**: Add comments explaining the data source or purpose
