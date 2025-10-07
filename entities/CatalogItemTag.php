<?php

namespace Entities;

/**
 * CatalogItemTag Entity
 * Tags for catalog items (local, eco-friendly, handmade, etc.)
 */
class CatalogItemTag extends BaseEntity
{
    protected ?int $item_id = null;
    protected ?string $tag = null;

    public static function getTableName(): string
    {
        return 'catalog_item_tag';
    }

    protected function getFillableAttributes(): array
    {
        return ['item_id', 'tag'];
    }

    protected function getValidationRules(): array
    {
        return [
            'item_id' => ['required', 'numeric'],
            'tag' => ['required', 'min:2', 'max:50'],
        ];
    }

    /**
     * Get the catalog item
     */
    public function getCatalogItem(): ?CatalogItem
    {
        return CatalogItem::find($this->item_id);
    }

    /**
     * Get tags by item
     */
    public static function getByItem(int $itemId): array
    {
        return static::where('item_id = :item_id', ['item_id' => $itemId]);
    }

    /**
     * Get items by tag
     */
    public static function getItemsByTag(string $tag): array
    {
        $tagRecords = static::where('tag = :tag', ['tag' => $tag]);
        $items = [];

        foreach ($tagRecords as $tagRecord) {
            $item = $tagRecord->getCatalogItem();
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Get all unique tags
     */
    public static function getAllUniqueTags(): array
    {
        $sql = "SELECT DISTINCT tag FROM " . static::getTableName() . " WHERE deleted_at IS NULL ORDER BY tag";
        return \App\Database::fetchAll($sql);
    }

    /**
     * Get popular tags (most used)
     */
    public static function getPopularTags(int $limit = 20): array
    {
        $sql = "SELECT tag, COUNT(*) as count FROM " . static::getTableName() . "
                WHERE deleted_at IS NULL
                GROUP BY tag
                ORDER BY count DESC
                LIMIT :limit";
        return \App\Database::fetchAll($sql, ['limit' => $limit]);
    }

    /**
     * Check if item has a specific tag
     */
    public static function itemHasTag(int $itemId, string $tag): bool
    {
        return static::count('item_id = :item_id AND tag = :tag', [
            'item_id' => $itemId,
            'tag' => $tag
        ]) > 0;
    }

    /**
     * Add tag to item (if not exists)
     */
    public static function addTagToItem(int $itemId, string $tag, ?int $userId = null): ?CatalogItemTag
    {
        // Check if tag already exists
        if (static::itemHasTag($itemId, $tag)) {
            return null;
        }

        $tagEntity = new static();
        $tagEntity->fill([
            'item_id' => $itemId,
            'tag' => strtolower(trim($tag))
        ]);

        if ($tagEntity->save($userId)) {
            return $tagEntity;
        }

        return null;
    }

    /**
     * Remove tag from item
     */
    public static function removeTagFromItem(int $itemId, string $tag, ?int $userId = null): bool
    {
        $tags = static::where('item_id = :item_id AND tag = :tag', [
            'item_id' => $itemId,
            'tag' => $tag
        ], 1);

        if (!empty($tags)) {
            return $tags[0]->delete($userId);
        }

        return false;
    }

    /**
     * Count tags for an item
     */
    public static function countByItem(int $itemId): int
    {
        return static::count('item_id = :item_id', ['item_id' => $itemId]);
    }

    /**
     * Search tags
     */
    public static function searchTags(string $query): array
    {
        return static::search($query, ['tag']);
    }
}
