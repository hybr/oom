<?php

namespace Entities;

/**
 * CatalogItemFeature Entity
 * Specifications and features for catalog items
 */
class CatalogItemFeature extends BaseEntity
{
    protected ?int $item_id = null;
    protected ?string $feature_name = null;
    protected ?string $feature_value = null;

    public static function getTableName(): string
    {
        return 'catalog_item_feature';
    }

    protected function getFillableAttributes(): array
    {
        return ['item_id', 'feature_name', 'feature_value'];
    }

    protected function getValidationRules(): array
    {
        return [
            'item_id' => ['required', 'numeric'],
            'feature_name' => ['required', 'min:2', 'max:100'],
            'feature_value' => ['required', 'max:500'],
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
     * Get formatted feature (name: value)
     */
    public function getFormattedFeature(): string
    {
        return "{$this->feature_name}: {$this->feature_value}";
    }

    /**
     * Get features by item
     */
    public static function getByItem(int $itemId): array
    {
        return static::where('item_id = :item_id', ['item_id' => $itemId]);
    }

    /**
     * Get feature by name for an item
     */
    public static function getByItemAndName(int $itemId, string $featureName): ?CatalogItemFeature
    {
        $features = static::where('item_id = :item_id AND feature_name = :name', [
            'item_id' => $itemId,
            'name' => $featureName
        ], 1);

        return $features[0] ?? null;
    }

    /**
     * Search features by name or value
     */
    public static function searchFeatures(string $query): array
    {
        return static::search($query, ['feature_name', 'feature_value']);
    }

    /**
     * Get distinct feature names (for filtering)
     */
    public static function getDistinctFeatureNames(): array
    {
        $sql = "SELECT DISTINCT feature_name FROM " . static::getTableName() . " WHERE deleted_at IS NULL ORDER BY feature_name";
        return \App\Database::fetchAll($sql);
    }

    /**
     * Count features for an item
     */
    public static function countByItem(int $itemId): int
    {
        return static::count('item_id = :item_id', ['item_id' => $itemId]);
    }
}
