<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * SellerItemPrice Entity
 * Pricing information for seller items with time-based validity
 */
class SellerItemPrice extends BaseEntity {
    protected $table = 'seller_item_prices';
    protected $fillable = [
        'seller_item_id', 'currency_code', 'base_price', 'discount_percent',
        'final_price', 'effective_from', 'effective_to', 'is_active'
    ];

    /**
     * Get seller item
     */
    public function getSellerItem($priceId) {
        $sql = "SELECT si.* FROM seller_items si
                JOIN seller_item_prices sip ON sip.seller_item_id = si.id
                WHERE sip.id = ? AND si.deleted_at IS NULL";
        return $this->queryOne($sql, [$priceId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($priceId) {
        $sql = "SELECT sip.*,
                si.type as seller_item_type,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_prices sip
                LEFT JOIN seller_items si ON sip.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sip.id = ? AND sip.deleted_at IS NULL";
        return $this->queryOne($sql, [$priceId]);
    }

    /**
     * Get prices by seller item
     */
    public function getBySellerItem($sellerItemId) {
        return $this->all(['seller_item_id' => $sellerItemId], 'effective_from DESC');
    }

    /**
     * Get active price
     */
    public function getActivePrice($sellerItemId) {
        $today = date('Y-m-d');

        $sql = "SELECT * FROM seller_item_prices
                WHERE seller_item_id = ? AND is_active = 1
                AND (effective_from IS NULL OR effective_from <= ?)
                AND (effective_to IS NULL OR effective_to >= ?)
                AND deleted_at IS NULL
                ORDER BY effective_from DESC
                LIMIT 1";

        return $this->queryOne($sql, [$sellerItemId, $today, $today]);
    }

    /**
     * Get historical prices
     */
    public function getHistoricalPrices($sellerItemId) {
        $sql = "SELECT * FROM seller_item_prices
                WHERE seller_item_id = ? AND deleted_at IS NULL
                ORDER BY effective_from DESC";
        return $this->query($sql, [$sellerItemId]);
    }

    /**
     * Get price changes
     */
    public function getPriceChanges($sellerItemId) {
        $prices = $this->getHistoricalPrices($sellerItemId);
        $changes = [];

        for ($i = 0; $i < count($prices) - 1; $i++) {
            $current = $prices[$i];
            $previous = $prices[$i + 1];

            $change = $current['final_price'] - $previous['final_price'];
            $changePercent = ($change / $previous['final_price']) * 100;

            $changes[] = [
                'from_price' => $previous['final_price'],
                'to_price' => $current['final_price'],
                'change_amount' => $change,
                'change_percent' => round($changePercent, 2),
                'effective_from' => $current['effective_from'],
                'currency_code' => $current['currency_code']
            ];
        }

        return $changes;
    }

    /**
     * Calculate final price
     */
    public function calculateFinalPrice($basePrice, $discountPercent) {
        if ($discountPercent > 0) {
            return $basePrice - ($basePrice * ($discountPercent / 100));
        }
        return $basePrice;
    }

    /**
     * Set active price
     */
    public function setActive($priceId) {
        $price = $this->find($priceId);
        if (!$price) {
            return false;
        }

        // Deactivate other prices for same seller item
        $sql = "UPDATE seller_item_prices
                SET is_active = 0, updated_at = datetime('now')
                WHERE seller_item_id = ? AND deleted_at IS NULL";
        $this->db->execute($sql, [$price['seller_item_id']]);

        // Activate this price
        return $this->update($priceId, ['is_active' => 1]);
    }

    /**
     * Get upcoming price changes
     */
    public function getUpcomingPriceChanges($days = 30) {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+$days days"));

        $sql = "SELECT sip.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_prices sip
                LEFT JOIN seller_items si ON sip.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sip.effective_from BETWEEN ? AND ?
                AND sip.deleted_at IS NULL
                ORDER BY sip.effective_from ASC";

        return $this->query($sql, [$today, $futureDate]);
    }

    /**
     * Get items on discount
     */
    public function getDiscountedItems($minDiscountPercent = 10) {
        $today = date('Y-m-d');

        $sql = "SELECT sip.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_prices sip
                LEFT JOIN seller_items si ON sip.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sip.discount_percent >= ? AND sip.is_active = 1
                AND (sip.effective_from IS NULL OR sip.effective_from <= ?)
                AND (sip.effective_to IS NULL OR sip.effective_to >= ?)
                AND sip.deleted_at IS NULL
                ORDER BY sip.discount_percent DESC";

        return $this->query($sql, [$minDiscountPercent, $today, $today]);
    }

    /**
     * Get prices by currency
     */
    public function getByCurrency($currencyCode) {
        $today = date('Y-m-d');

        $sql = "SELECT sip.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_prices sip
                LEFT JOIN seller_items si ON sip.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sip.currency_code = ? AND sip.is_active = 1
                AND (sip.effective_from IS NULL OR sip.effective_from <= ?)
                AND (sip.effective_to IS NULL OR sip.effective_to >= ?)
                AND sip.deleted_at IS NULL
                ORDER BY sip.final_price ASC";

        return $this->query($sql, [$currencyCode, $today, $today]);
    }

    /**
     * Get price range for catalog item
     */
    public function getPriceRange($catalogItemId) {
        $today = date('Y-m-d');

        $sql = "SELECT
                    MIN(sip.final_price) as min_price,
                    MAX(sip.final_price) as max_price,
                    AVG(sip.final_price) as avg_price,
                    sip.currency_code
                FROM seller_item_prices sip
                JOIN seller_items si ON sip.seller_item_id = si.id
                WHERE si.catalog_item_id = ? AND sip.is_active = 1
                AND (sip.effective_from IS NULL OR sip.effective_from <= ?)
                AND (sip.effective_to IS NULL OR sip.effective_to >= ?)
                AND sip.deleted_at IS NULL
                GROUP BY sip.currency_code";

        return $this->query($sql, [$catalogItemId, $today, $today]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $today = date('Y-m-d');

        $sql = "SELECT
                    COUNT(*) as total_prices,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_prices,
                    AVG(final_price) as average_price,
                    MIN(final_price) as min_price,
                    MAX(final_price) as max_price,
                    AVG(discount_percent) as average_discount,
                    COUNT(DISTINCT seller_item_id) as unique_seller_items
                FROM seller_item_prices sip";

        $params = [];

        if ($organizationId) {
            $sql .= " JOIN seller_items si ON sip.seller_item_id = si.id
                      WHERE si.organization_id = ? AND sip.deleted_at IS NULL";
            $params[] = $organizationId;
        } else {
            $sql .= " WHERE sip.deleted_at IS NULL";
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'seller_item_id' => 'required|integer',
            'currency_code' => 'required|min:3|max:3',
            'base_price' => 'required|numeric',
            'discount_percent' => 'numeric|min:0|max:100',
            'final_price' => 'required|numeric',
            'effective_from' => 'date',
            'effective_to' => 'date',
            'is_active' => 'boolean',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $price = $this->getWithDetails($id);
        if (!$price) {
            return 'N/A';
        }
        return $price['currency_code'] . ' ' . $price['final_price'] . ' - ' . $price['catalog_item_name'];
    }
}
