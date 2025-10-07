<?php

namespace Entities;

/**
 * CatalogItem Entity
 * Centralized catalog of all goods and services (V4L-managed)
 */
class CatalogItem extends BaseEntity
{
    protected ?int $category_id = null;
    protected ?string $type = null; // Good, Service, Subscription, Rental
    protected ?string $name = null;
    protected ?string $brand_name = null;
    protected ?string $short_description = null;
    protected ?string $detailed_description = null;
    protected ?string $unit_of_measure = null; // pcs, kg, hr, etc.
    protected ?string $thumbnail_url = null;
    protected string $status = 'Active'; // Active, Inactive

    // ENUM for type
    public const TYPE_GOOD = 'Good';
    public const TYPE_SERVICE = 'Service';
    public const TYPE_SUBSCRIPTION = 'Subscription';
    public const TYPE_RENTAL = 'Rental';

    public static function getTableName(): string
    {
        return 'catalog_item';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'category_id', 'type', 'name', 'brand_name', 'short_description',
            'detailed_description', 'unit_of_measure', 'thumbnail_url', 'status'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'category_id' => ['required', 'numeric'],
            'type' => ['required'],
            'name' => ['required', 'min:2', 'max:200'],
            'short_description' => ['required', 'max:500'],
            'unit_of_measure' => ['required', 'max:20'],
        ];
    }

    /**
     * Get category
     */
    public function getCategory(): ?CatalogCategory
    {
        return CatalogCategory::find($this->category_id);
    }

    /**
     * Get all features for this item
     */
    public function getFeatures(): array
    {
        return CatalogItemFeature::where('item_id = :item_id', ['item_id' => $this->id]);
    }

    /**
     * Get all media for this item
     */
    public function getMedia(): array
    {
        return CatalogItemMedia::where('item_id = :item_id', ['item_id' => $this->id]);
    }

    /**
     * Get primary image
     */
    public function getPrimaryImage(): ?CatalogItemMedia
    {
        $media = CatalogItemMedia::where('item_id = :item_id AND is_primary = 1', ['item_id' => $this->id], 1);
        return $media[0] ?? null;
    }

    /**
     * Get all tags for this item
     */
    public function getTags(): array
    {
        return CatalogItemTag::where('item_id = :item_id', ['item_id' => $this->id]);
    }

    /**
     * Get all reviews for this item
     */
    public function getReviews(): array
    {
        return CatalogItemReview::where('item_id = :item_id AND status = :status', [
            'item_id' => $this->id,
            'status' => 'Visible'
        ]);
    }

    /**
     * Get all sellers offering this item
     */
    public function getSellerItems(): array
    {
        return SellerItem::where('catalog_item_id = :item_id', ['item_id' => $this->id]);
    }

    /**
     * Get average rating
     */
    public function getAverageRating(): float
    {
        $reviews = $this->getReviews();
        if (empty($reviews)) {
            return 0.0;
        }

        $sum = 0;
        foreach ($reviews as $review) {
            $sum += $review->rating;
        }

        return round($sum / count($reviews), 2);
    }

    /**
     * Count reviews
     */
    public function countReviews(): int
    {
        return CatalogItemReview::count('item_id = :item_id AND status = :status', [
            'item_id' => $this->id,
            'status' => 'Visible'
        ]);
    }

    /**
     * Get full item title (with brand)
     */
    public function getFullTitle(): string
    {
        if ($this->brand_name) {
            return "{$this->brand_name} {$this->name}";
        }
        return $this->name;
    }

    /**
     * Check if item is a good (physical product)
     */
    public function isGood(): bool
    {
        return $this->type === self::TYPE_GOOD;
    }

    /**
     * Check if item is a service
     */
    public function isService(): bool
    {
        return $this->type === self::TYPE_SERVICE;
    }

    /**
     * Activate item
     */
    public function activate(?int $userId = null): bool
    {
        $this->status = 'Active';
        return $this->save($userId);
    }

    /**
     * Deactivate item
     */
    public function deactivate(?int $userId = null): bool
    {
        $this->status = 'Inactive';
        return $this->save($userId);
    }

    /**
     * Search items
     */
    public static function searchItems(string $query): array
    {
        return static::search($query, ['name', 'brand_name', 'short_description']);
    }

    /**
     * Get items by category
     */
    public static function getByCategory(int $categoryId): array
    {
        return static::where('category_id = :category_id', ['category_id' => $categoryId]);
    }

    /**
     * Get items by type
     */
    public static function getByType(string $type): array
    {
        return static::where('type = :type', ['type' => $type]);
    }

    /**
     * Get active items
     */
    public static function getActiveItems(): array
    {
        return static::where('status = :status', ['status' => 'Active']);
    }
}
