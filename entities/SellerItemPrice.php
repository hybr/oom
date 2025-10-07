<?php

namespace Entities;

/**
 * SellerItemPrice Entity
 * Pricing information for seller items with date ranges
 */
class SellerItemPrice extends BaseEntity
{
    protected ?int $seller_item_id = null;
    protected ?string $currency_code = null;
    protected ?float $base_price = null;
    protected float $discount_percent = 0.0;
    protected ?float $final_price = null;
    protected ?string $effective_from = null;
    protected ?string $effective_to = null;
    protected bool $is_active = true;

    public static function getTableName(): string
    {
        return 'seller_item_price';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'seller_item_id', 'currency_code', 'base_price', 'discount_percent',
            'final_price', 'effective_from', 'effective_to', 'is_active'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'seller_item_id' => ['required', 'numeric'],
            'currency_code' => ['required', 'min:3', 'max:3'],
            'base_price' => ['required', 'numeric'],
            'effective_from' => ['required'],
        ];
    }

    /**
     * Get seller item
     */
    public function getSellerItem(): ?SellerItem
    {
        return SellerItem::find($this->seller_item_id);
    }

    /**
     * Calculate and set final price
     */
    public function calculateFinalPrice(): void
    {
        $discount = $this->base_price * ($this->discount_percent / 100);
        $this->final_price = $this->base_price - $discount;
    }

    /**
     * Override save to auto-calculate final price
     */
    public function save(?int $userId = null): bool
    {
        $this->calculateFinalPrice();
        return parent::save($userId);
    }

    /**
     * Check if price is currently effective
     */
    public function isEffective(): bool
    {
        $now = date('Y-m-d');

        $afterStart = empty($this->effective_from) || $this->effective_from <= $now;
        $beforeEnd = empty($this->effective_to) || $this->effective_to >= $now;

        return $this->is_active && $afterStart && $beforeEnd;
    }

    /**
     * Activate price
     */
    public function activate(?int $userId = null): bool
    {
        $this->is_active = true;
        return $this->save($userId);
    }

    /**
     * Deactivate price
     */
    public function deactivate(?int $userId = null): bool
    {
        $this->is_active = false;
        return $this->save($userId);
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPrice(): string
    {
        return $this->currency_code . ' ' . number_format($this->final_price, 2);
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmount(): float
    {
        return $this->base_price - $this->final_price;
    }

    /**
     * Check if price has discount
     */
    public function hasDiscount(): bool
    {
        return $this->discount_percent > 0;
    }

    /**
     * Get prices by seller item
     */
    public static function getBySellerItem(int $sellerItemId): array
    {
        return static::where('seller_item_id = :seller_item_id', ['seller_item_id' => $sellerItemId]);
    }

    /**
     * Get active prices
     */
    public static function getActivePrices(): array
    {
        return static::where('is_active = 1');
    }

    /**
     * Get current effective price for a seller item
     */
    public static function getCurrentPrice(int $sellerItemId): ?SellerItemPrice
    {
        $prices = static::where(
            'seller_item_id = :seller_item_id AND is_active = 1 AND
             effective_from <= :now AND (effective_to IS NULL OR effective_to >= :now)',
            [
                'seller_item_id' => $sellerItemId,
                'now' => date('Y-m-d')
            ],
            1
        );

        return $prices[0] ?? null;
    }

    /**
     * Get prices by currency
     */
    public static function getByCurrency(string $currencyCode): array
    {
        return static::where('currency_code = :currency', ['currency' => $currencyCode]);
    }

    /**
     * Get prices with discounts
     */
    public static function getPricesWithDiscount(): array
    {
        return static::where('discount_percent > 0 AND is_active = 1');
    }

    /**
     * Get price history for a seller item
     */
    public static function getPriceHistory(int $sellerItemId): array
    {
        $sql = "SELECT * FROM " . static::getTableName() . "
                WHERE seller_item_id = :seller_item_id AND deleted_at IS NULL
                ORDER BY effective_from DESC";
        $data = \App\Database::fetchAll($sql, ['seller_item_id' => $sellerItemId]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Expire old prices (set effective_to to today for active prices without end date)
     */
    public static function expireOldPrices(int $sellerItemId, ?int $userId = null): void
    {
        $sql = "UPDATE " . static::getTableName() . "
                SET effective_to = :today, updated_at = :updated_at, updated_by = :updated_by
                WHERE seller_item_id = :seller_item_id AND is_active = 1
                AND (effective_to IS NULL OR effective_to > :today)
                AND deleted_at IS NULL";

        \App\Database::execute($sql, [
            'seller_item_id' => $sellerItemId,
            'today' => date('Y-m-d'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        ]);
    }
}
