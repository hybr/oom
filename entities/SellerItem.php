<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * SellerItem Entity
 * Organizations' offerings of catalog items with pricing and availability
 */
class SellerItem extends BaseEntity {
    protected $table = 'seller_items';
    protected $fillable = [
        'organization_id', 'catalog_item_id', 'type', 'available_from_building_id',
        'availability_status', 'remarks', 'created_by'
    ];

    /**
     * Get organization
     */
    public function getOrganization($sellerItemId) {
        $sql = "SELECT o.* FROM organizations o
                JOIN seller_items si ON si.organization_id = o.id
                WHERE si.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get catalog item
     */
    public function getCatalogItem($sellerItemId) {
        $sql = "SELECT ci.* FROM catalog_items ci
                JOIN seller_items si ON si.catalog_item_id = ci.id
                WHERE si.id = ? AND ci.deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get building location
     */
    public function getBuilding($sellerItemId) {
        $sql = "SELECT ob.* FROM organization_buildings ob
                JOIN seller_items si ON si.available_from_building_id = ob.id
                WHERE si.id = ? AND ob.deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get creator
     */
    public function getCreator($sellerItemId) {
        $sql = "SELECT p.* FROM persons p
                JOIN seller_items si ON si.created_by = p.id
                WHERE si.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($sellerItemId) {
        $sql = "SELECT si.*,
                o.short_name as organization_name,
                ci.name as catalog_item_name, ci.type as catalog_item_type,
                ob.name as building_name,
                pa.city, pa.state,
                p.first_name as creator_first_name, p.last_name as creator_last_name
                FROM seller_items si
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organization_buildings ob ON si.available_from_building_id = ob.id
                LEFT JOIN postal_addresses pa ON ob.postal_address_id = pa.id
                LEFT JOIN persons p ON si.created_by = p.id
                WHERE si.id = ? AND si.deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get active price
     */
    public function getActivePrice($sellerItemId) {
        $sql = "SELECT * FROM seller_item_prices
                WHERE seller_item_id = ? AND is_active = 1
                AND (effective_from IS NULL OR effective_from <= date('now'))
                AND (effective_to IS NULL OR effective_to >= date('now'))
                AND deleted_at IS NULL
                ORDER BY effective_from DESC
                LIMIT 1";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get inventory
     */
    public function getInventory($sellerItemId) {
        $sql = "SELECT * FROM seller_item_inventories
                WHERE seller_item_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get service schedule
     */
    public function getServiceSchedule($sellerItemId) {
        $sql = "SELECT * FROM seller_service_schedules
                WHERE seller_item_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get reviews
     */
    public function getReviews($sellerItemId, $status = 'Visible') {
        $sql = "SELECT sir.*, p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.seller_item_id = ? AND sir.status = ? AND sir.deleted_at IS NULL
                ORDER BY sir.review_date DESC";
        return $this->query($sql, [$sellerItemId, $status]);
    }

    /**
     * Get average rating
     */
    public function getAverageRating($sellerItemId) {
        $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count
                FROM seller_item_reviews
                WHERE seller_item_id = ? AND status = 'Visible' AND deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get items by organization
     */
    public function getByOrganization($organizationId, $type = null, $status = 'Available') {
        $sql = "SELECT si.*, ci.name as catalog_item_name,
                sip.final_price, sip.currency_code
                FROM seller_items si
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN seller_item_prices sip ON si.id = sip.seller_item_id
                    AND sip.is_active = 1 AND sip.deleted_at IS NULL
                WHERE si.organization_id = ? AND si.availability_status = ?
                AND si.deleted_at IS NULL";

        $params = [$organizationId, $status];

        if ($type) {
            $sql .= " AND si.type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get items by catalog item
     */
    public function getByCatalogItem($catalogItemId, $status = 'Available') {
        $sql = "SELECT si.*, o.short_name as organization_name,
                ob.name as building_name, pa.city,
                sip.final_price, sip.currency_code
                FROM seller_items si
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN organization_buildings ob ON si.available_from_building_id = ob.id
                LEFT JOIN postal_addresses pa ON ob.postal_address_id = pa.id
                LEFT JOIN seller_item_prices sip ON si.id = sip.seller_item_id
                    AND sip.is_active = 1 AND sip.deleted_at IS NULL
                WHERE si.catalog_item_id = ? AND si.availability_status = ?
                AND si.deleted_at IS NULL
                ORDER BY sip.final_price ASC";
        return $this->query($sql, [$catalogItemId, $status]);
    }

    /**
     * Get by type
     */
    public function getByType($type, $status = 'Available') {
        $sql = "SELECT si.*, o.short_name as organization_name,
                ci.name as catalog_item_name
                FROM seller_items si
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                WHERE si.type = ? AND si.availability_status = ?
                AND si.deleted_at IS NULL
                ORDER BY o.short_name ASC, ci.name ASC";
        return $this->query($sql, [$type, $status]);
    }

    /**
     * Search seller items
     */
    public function searchItems($term, $city = null, $limit = 50) {
        $sql = "SELECT si.*, o.short_name as organization_name,
                ci.name as catalog_item_name, pa.city,
                sip.final_price, sip.currency_code
                FROM seller_items si
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organization_buildings ob ON si.available_from_building_id = ob.id
                LEFT JOIN postal_addresses pa ON ob.postal_address_id = pa.id
                LEFT JOIN seller_item_prices sip ON si.id = sip.seller_item_id
                    AND sip.is_active = 1 AND sip.deleted_at IS NULL
                WHERE (ci.name LIKE ? OR o.short_name LIKE ?)
                AND si.availability_status = 'Available'
                AND si.deleted_at IS NULL";

        $params = ["%$term%", "%$term%"];

        if ($city) {
            $sql .= " AND pa.city = ?";
            $params[] = $city;
        }

        $sql .= " ORDER BY ci.name ASC LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get items near location
     */
    public function getItemsNearLocation($latitude, $longitude, $radiusKm = 10, $limit = 50) {
        $sql = "SELECT si.*, o.short_name as organization_name,
                ci.name as catalog_item_name, pa.city,
                sip.final_price, sip.currency_code,
                (6371 * acos(cos(radians(?)) * cos(radians(pa.latitude)) *
                cos(radians(pa.longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(pa.latitude)))) AS distance
                FROM seller_items si
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organization_buildings ob ON si.available_from_building_id = ob.id
                LEFT JOIN postal_addresses pa ON ob.postal_address_id = pa.id
                LEFT JOIN seller_item_prices sip ON si.id = sip.seller_item_id
                    AND sip.is_active = 1 AND sip.deleted_at IS NULL
                WHERE pa.latitude IS NOT NULL AND pa.longitude IS NOT NULL
                AND si.availability_status = 'Available'
                AND si.deleted_at IS NULL
                HAVING distance <= ?
                ORDER BY distance ASC
                LIMIT ?";

        return $this->query($sql, [$latitude, $longitude, $latitude, $radiusKm, $limit]);
    }

    /**
     * Mark as available
     */
    public function markAvailable($sellerItemId) {
        return $this->update($sellerItemId, ['availability_status' => 'Available']);
    }

    /**
     * Mark as unavailable
     */
    public function markUnavailable($sellerItemId) {
        return $this->update($sellerItemId, ['availability_status' => 'Unavailable']);
    }

    /**
     * Mark as seasonal
     */
    public function markSeasonal($sellerItemId) {
        return $this->update($sellerItemId, ['availability_status' => 'Seasonal']);
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_items,
                    COUNT(CASE WHEN availability_status = 'Available' THEN 1 END) as available_count,
                    COUNT(CASE WHEN type = 'Sell' THEN 1 END) as sell_count,
                    COUNT(CASE WHEN type = 'Rent' THEN 1 END) as rent_count,
                    COUNT(CASE WHEN type = 'Service' THEN 1 END) as service_count,
                    COUNT(DISTINCT organization_id) as unique_sellers,
                    COUNT(DISTINCT catalog_item_id) as unique_catalog_items
                FROM seller_items
                WHERE deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND organization_id = ?";
            $params[] = $organizationId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'organization_id' => 'required|integer',
            'catalog_item_id' => 'required|integer',
            'type' => 'required',
            'available_from_building_id' => 'required|integer',
            'availability_status' => 'required',
            'remarks' => 'max:1000',
            'created_by' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $item = $this->getWithDetails($id);
        if (!$item) {
            return 'N/A';
        }
        return $item['catalog_item_name'] . ' - ' . $item['organization_name'];
    }
}
