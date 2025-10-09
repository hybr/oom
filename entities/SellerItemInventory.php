<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * SellerItemInventory Entity
 * Stock tracking for goods (not applicable to services)
 */
class SellerItemInventory extends BaseEntity {
    protected $table = 'seller_item_inventories';
    protected $fillable = ['seller_item_id', 'available_quantity', 'reorder_level', 'last_restock_date'];

    /**
     * Get seller item
     */
    public function getSellerItem($inventoryId) {
        $sql = "SELECT si.* FROM seller_items si
                JOIN seller_item_inventories sii ON sii.seller_item_id = si.id
                WHERE sii.id = ? AND si.deleted_at IS NULL";
        return $this->queryOne($sql, [$inventoryId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($inventoryId) {
        $sql = "SELECT sii.*,
                ci.name as catalog_item_name, ci.unit_of_measure,
                o.short_name as organization_name
                FROM seller_item_inventories sii
                LEFT JOIN seller_items si ON sii.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sii.id = ? AND sii.deleted_at IS NULL";
        return $this->queryOne($sql, [$inventoryId]);
    }

    /**
     * Get inventory by seller item
     */
    public function getBySellerItem($sellerItemId) {
        $sql = "SELECT * FROM seller_item_inventories
                WHERE seller_item_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get by organization
     */
    public function getByOrganization($organizationId) {
        $sql = "SELECT sii.*,
                ci.name as catalog_item_name, ci.unit_of_measure
                FROM seller_item_inventories sii
                LEFT JOIN seller_items si ON sii.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                WHERE si.organization_id = ? AND sii.deleted_at IS NULL
                ORDER BY ci.name ASC";
        return $this->query($sql, [$organizationId]);
    }

    /**
     * Get low stock items
     */
    public function getLowStock($organizationId = null) {
        $sql = "SELECT sii.*,
                ci.name as catalog_item_name, ci.unit_of_measure,
                o.short_name as organization_name
                FROM seller_item_inventories sii
                LEFT JOIN seller_items si ON sii.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sii.available_quantity <= sii.reorder_level
                AND sii.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sii.available_quantity ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get out of stock items
     */
    public function getOutOfStock($organizationId = null) {
        $sql = "SELECT sii.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_inventories sii
                LEFT JOIN seller_items si ON sii.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sii.available_quantity = 0
                AND sii.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Update quantity
     */
    public function updateQuantity($inventoryId, $newQuantity, $updateRestockDate = false) {
        $data = ['available_quantity' => $newQuantity];

        if ($updateRestockDate) {
            $data['last_restock_date'] = date('Y-m-d');
        }

        return $this->update($inventoryId, $data);
    }

    /**
     * Increase quantity (restock)
     */
    public function increaseQuantity($inventoryId, $amount) {
        $inventory = $this->find($inventoryId);
        if (!$inventory) {
            return false;
        }

        $newQuantity = $inventory['available_quantity'] + $amount;
        return $this->updateQuantity($inventoryId, $newQuantity, true);
    }

    /**
     * Decrease quantity (sale/use)
     */
    public function decreaseQuantity($inventoryId, $amount) {
        $inventory = $this->find($inventoryId);
        if (!$inventory) {
            return false;
        }

        $newQuantity = max(0, $inventory['available_quantity'] - $amount);
        return $this->updateQuantity($inventoryId, $newQuantity, false);
    }

    /**
     * Check if in stock
     */
    public function isInStock($inventoryId, $requiredQuantity = 1) {
        $inventory = $this->find($inventoryId);
        if (!$inventory) {
            return false;
        }

        return $inventory['available_quantity'] >= $requiredQuantity;
    }

    /**
     * Check if needs reorder
     */
    public function needsReorder($inventoryId) {
        $inventory = $this->find($inventoryId);
        if (!$inventory) {
            return false;
        }

        return $inventory['available_quantity'] <= $inventory['reorder_level'];
    }

    /**
     * Get recently restocked
     */
    public function getRecentlyRestocked($days = 7, $organizationId = null) {
        $sql = "SELECT sii.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_inventories sii
                LEFT JOIN seller_items si ON sii.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sii.last_restock_date >= date('now', '-' || ? || ' days')
                AND sii.deleted_at IS NULL";

        $params = [$days];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sii.last_restock_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get inventory value (quantity * price)
     */
    public function getInventoryValue($inventoryId) {
        $inventory = $this->getWithDetails($inventoryId);
        if (!$inventory) {
            return 0;
        }

        // Get active price
        require_once __DIR__ . '/SellerItemPrice.php';
        $priceModel = new SellerItemPrice();
        $price = $priceModel->getActivePrice($inventory['seller_item_id']);

        if (!$price) {
            return 0;
        }

        return $inventory['available_quantity'] * $price['final_price'];
    }

    /**
     * Get total inventory value by organization
     */
    public function getTotalInventoryValue($organizationId) {
        $inventories = $this->getByOrganization($organizationId);
        $totalValue = 0;

        foreach ($inventories as $inventory) {
            $totalValue += $this->getInventoryValue($inventory['id']);
        }

        return $totalValue;
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_items,
                    SUM(available_quantity) as total_quantity,
                    COUNT(CASE WHEN available_quantity = 0 THEN 1 END) as out_of_stock_count,
                    COUNT(CASE WHEN available_quantity <= reorder_level THEN 1 END) as low_stock_count,
                    AVG(available_quantity) as average_quantity
                FROM seller_item_inventories sii";

        $params = [];

        if ($organizationId) {
            $sql .= " JOIN seller_items si ON sii.seller_item_id = si.id
                      WHERE si.organization_id = ? AND sii.deleted_at IS NULL";
            $params[] = $organizationId;
        } else {
            $sql .= " WHERE sii.deleted_at IS NULL";
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'seller_item_id' => 'required|integer',
            'available_quantity' => 'required|integer|min:0',
            'reorder_level' => 'integer|min:0',
            'last_restock_date' => 'date',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $inventory = $this->getWithDetails($id);
        if (!$inventory) {
            return 'N/A';
        }
        return $inventory['catalog_item_name'] . ' (Qty: ' . $inventory['available_quantity'] . ')';
    }
}
