<?php

namespace Entities;

/**
 * SellerItem Entity
 * Represents an organization's offering of a catalog item
 */
class SellerItem extends BaseEntity
{
    protected ?int $organization_id = null;
    protected ?int $catalog_item_id = null;
    protected ?string $type = null; // Sell, Rent, Lease, Service
    protected ?int $available_from_building_id = null;
    protected string $availability_status = 'Available'; // Available, Unavailable, Seasonal
    protected ?string $remarks = null;

    // ENUM for type
    public const TYPE_SELL = 'Sell';
    public const TYPE_RENT = 'Rent';
    public const TYPE_LEASE = 'Lease';
    public const TYPE_SERVICE = 'Service';

    public static function getTableName(): string
    {
        return 'seller_item';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'organization_id', 'catalog_item_id', 'type', 'available_from_building_id',
            'availability_status', 'remarks'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_id' => ['required', 'numeric'],
            'catalog_item_id' => ['required', 'numeric'],
            'type' => ['required'],
            'available_from_building_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Get organization
     */
    public function getOrganization(): ?Organization
    {
        return Organization::find($this->organization_id);
    }

    /**
     * Get catalog item
     */
    public function getCatalogItem(): ?CatalogItem
    {
        return CatalogItem::find($this->catalog_item_id);
    }

    /**
     * Get building location
     */
    public function getBuilding(): ?OrganizationBuilding
    {
        return OrganizationBuilding::find($this->available_from_building_id);
    }

    /**
     * Get current price
     */
    public function getCurrentPrice(): ?SellerItemPrice
    {
        $prices = SellerItemPrice::where(
            'seller_item_id = :seller_item_id AND is_active = 1 AND
             (effective_to IS NULL OR effective_to >= :now)',
            [
                'seller_item_id' => $this->id,
                'now' => date('Y-m-d')
            ],
            1
        );

        return $prices[0] ?? null;
    }

    /**
     * Get all prices (history)
     */
    public function getPrices(): array
    {
        return SellerItemPrice::where('seller_item_id = :seller_item_id', ['seller_item_id' => $this->id]);
    }

    /**
     * Get inventory (for goods only)
     */
    public function getInventory(): ?SellerItemInventory
    {
        $inventory = SellerItemInventory::where('seller_item_id = :seller_item_id', ['seller_item_id' => $this->id], 1);
        return $inventory[0] ?? null;
    }

    /**
     * Get service schedule (for services/rentals only)
     */
    public function getServiceSchedule(): ?SellerServiceSchedule
    {
        $schedule = SellerServiceSchedule::where('seller_item_id = :seller_item_id', ['seller_item_id' => $this->id], 1);
        return $schedule[0] ?? null;
    }

    /**
     * Get reviews for this seller's item
     */
    public function getReviews(): array
    {
        return SellerItemReview::where('seller_item_id = :seller_item_id AND status = :status', [
            'seller_item_id' => $this->id,
            'status' => 'Visible'
        ]);
    }

    /**
     * Get average rating
     */
    public function getAverageRating(): float
    {
        return SellerItemReview::getAverageRatingBySellerItem($this->id);
    }

    /**
     * Check if item is available
     */
    public function isAvailable(): bool
    {
        return $this->availability_status === 'Available';
    }

    /**
     * Mark as available
     */
    public function markAvailable(?int $userId = null): bool
    {
        $this->availability_status = 'Available';
        return $this->save($userId);
    }

    /**
     * Mark as unavailable
     */
    public function markUnavailable(?int $userId = null): bool
    {
        $this->availability_status = 'Unavailable';
        return $this->save($userId);
    }

    /**
     * Check if item is for sale
     */
    public function isForSale(): bool
    {
        return $this->type === self::TYPE_SELL;
    }

    /**
     * Check if item is for rent
     */
    public function isForRent(): bool
    {
        return $this->type === self::TYPE_RENT;
    }

    /**
     * Check if item is a service
     */
    public function isService(): bool
    {
        return $this->type === self::TYPE_SERVICE;
    }

    /**
     * Get items by organization
     */
    public static function getByOrganization(int $organizationId): array
    {
        return static::where('organization_id = :org_id', ['org_id' => $organizationId]);
    }

    /**
     * Get items by catalog item
     */
    public static function getByCatalogItem(int $catalogItemId): array
    {
        return static::where('catalog_item_id = :item_id', ['item_id' => $catalogItemId]);
    }

    /**
     * Get available items
     */
    public static function getAvailableItems(): array
    {
        return static::where('availability_status = :status', ['status' => 'Available']);
    }

    /**
     * Get items by type
     */
    public static function getByType(string $type): array
    {
        return static::where('type = :type', ['type' => $type]);
    }

    /**
     * Get items by building
     */
    public static function getByBuilding(int $buildingId): array
    {
        return static::where('available_from_building_id = :building_id', ['building_id' => $buildingId]);
    }

    /**
     * Count reviews
     */
    public function countReviews(): int
    {
        return SellerItemReview::countBySellerItem($this->id);
    }
}
