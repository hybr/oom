-- =====================================================================
-- ITEM_ATTRIBUTE_VALUE Entity Metadata
-- Attribute values for items and variants
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'i1a1t1v1-a1l1-4u1e-a111-111111111111', 'ITEM_ATTRIBUTE_VALUE',
    'Item Attribute Value',
    'Attribute values for items or item variants',
    'MARKETPLACE_COMMERCE', 'item_attribute_value', 1
);

-- Full implementation: item_id or item_variant_id, attribute_definition_id,
-- value_text, value_number, value_boolean, value_date, value_enum
