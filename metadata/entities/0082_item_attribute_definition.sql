-- =====================================================================
-- ITEM_ATTRIBUTE_DEFINITION Entity Metadata
-- Category-specific attribute definitions
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'i1a1t1d1-e1f1-4111-a111-111111111111', 'ITEM_ATTRIBUTE_DEFINITION',
    'Item Attribute Definition',
    'Category-specific attribute definitions for items',
    'MARKETPLACE_COMMERCE', 'item_attribute_definition', 1
);

-- Full implementation: category_id, attribute_code, attribute_name, data_type,
-- enum_values, is_required, is_filterable, is_searchable, validation_regex
