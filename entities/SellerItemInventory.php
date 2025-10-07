<?php

namespace Entities;

/**
 * SellerItemInventory Entity
 * Inventory management for physical goods
 */
class SellerItemInventory extends BaseEntity
{
    protected ?int $seller_item_id = null;
    protected int $available_quantity = 0;
    protected int $reorder_level = 0;
    protected ?string $last_restock_date = null;

    public static function getTableName(): string
    {
        return 'seller_item_inventory';
    }

    protected function getFillableAttributes(): array
    {
        return ['seller_item_id', 'available_quantity', 'reorder_level', 'last_restock_date'];
    }

    protected function getValidationRules(): array
    {
        return [
            'seller_item_id' => ['required', 'numeric'],
            'available_quantity' => ['required', 'numeric'],
            'reorder_level' => ['numeric'],
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
     * Check if in stock
     */
    public function isInStock(): bool
    {
        return $this->available_quantity > 0;
    }

    /**
     * Check if stock is low (below reorder level)
     */
    public function isLowStock(): bool
    {
        return $this->available_quantity <= $this->reorder_level && $this->available_quantity > 0;
    }

    /**
     * Check if out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->available_quantity <= 0;
    }

    /**
     * Increase stock
     */
    public function increaseStock(int $quantity, ?int $userId = null): bool
    {
        $this->available_quantity += $quantity;
        $this->last_restock_date = date('Y-m-d');
        return $this->save($userId);
    }

    /**
     * Decrease stock
     */
    public function decreaseStock(int $quantity, ?int $userId = null): bool
    {
        if ($this->available_quantity < $quantity) {
            $this->errors['quantity'] = ['Insufficient stock'];
            return false;
        }

        $this->available_quantity -= $quantity;
        return $this->save($userId);
    }

    /**
     * Restock to a specific quantity
     */
    public function restock(int $quantity, ?int $userId = null): bool
    {
        $this->available_quantity = $quantity;
        $this->last_restock_date = date('Y-m-d');
        return $this->save($userId);
    }

    /**
     * Reserve stock (for orders)
     */
    public function reserve(int $quantity, ?int $userId = null): bool
    {
        return $this->decreaseStock($quantity, $userId);
    }

    /**
     * Release reserved stock (cancel order)
     */
    public function release(int $quantity, ?int $userId = null): bool
    {
        return $this->increaseStock($quantity, $userId);
    }

    /**
     * Get stock status
     */
    public function getStockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        } elseif ($this->isLowStock()) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    /**
     * Get stock level percentage
     */
    public function getStockLevelPercentage(): float
    {
        if ($this->reorder_level == 0) {
            return 100.0;
        }

        return min(($this->available_quantity / $this->reorder_level) * 100, 100);
    }

    /**
     * Get days since last restock
     */
    public function getDaysSinceRestock(): ?int
    {
        if (!$this->last_restock_date) {
            return null;
        }

        $lastRestock = strtotime($this->last_restock_date);
        $now = time();
        return (int)(($now - $lastRestock) / 86400);
    }

    /**
     * Get inventory by seller item
     */
    public static function getBySellerItem(int $sellerItemId): ?SellerItemInventory
    {
        $inventory = static::where('seller_item_id = :seller_item_id', ['seller_item_id' => $sellerItemId], 1);
        return $inventory[0] ?? null;
    }

    /**
     * Get low stock items
     */
    public static function getLowStockItems(): array
    {
        $sql = "SELECT * FROM " . static::getTableName() . "
                WHERE available_quantity <= reorder_level AND available_quantity > 0
                AND deleted_at IS NULL";
        $data = \App\Database::fetchAll($sql);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Get out of stock items
     */
    public static function getOutOfStockItems(): array
    {
        return static::where('available_quantity <= 0');
    }

    /**
     * Get items in stock
     */
    public static function getInStockItems(): array
    {
        return static::where('available_quantity > 0');
    }

    /**
     * Get items needing restock
     */
    public static function getItemsNeedingRestock(): array
    {
        $sql = "SELECT * FROM " . static::getTableName() . "
                WHERE available_quantity <= reorder_level
                AND deleted_at IS NULL
                ORDER BY available_quantity ASC";
        $data = \App\Database::fetchAll($sql);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Get inventory by organization
     */
    public static function getByOrganization(int $organizationId): array
    {
        $sql = "SELECT si.* FROM " . static::getTableName() . " si
                INNER JOIN seller_item s ON si.seller_item_id = s.id
                WHERE s.organization_id = :org_id AND si.deleted_at IS NULL";
        $data = \App\Database::fetchAll($sql, ['org_id' => $organizationId]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Get total inventory value (requires price data)
     */
    public function getInventoryValue(): float
    {
        $sellerItem = $this->getSellerItem();
        if (!$sellerItem) {
            return 0.0;
        }

        $price = $sellerItem->getCurrentPrice();
        if (!$price) {
            return 0.0;
        }

        return $this->available_quantity * $price->final_price;
    }
}
