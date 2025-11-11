-- =====================================================================
-- ITEM_IMAGE Entity Metadata
-- Product and variant images
-- Generated: 2025-11-09
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (
    id, code, name, description, domain, table_name, is_active
) VALUES (
    'i1i1m1g1-e000-4111-a111-111111111111', 'ITEM_IMAGE', 'Item Image',
    'Images for items and item variants',
    'MARKETPLACE_COMMERCE', 'item_image', 1
);

-- Full implementation: item_id/item_variant_id, media_file_id,
-- image_type (MAIN, GALLERY, THUMBNAIL, ZOOM), display_order, alt_text, is_primary
