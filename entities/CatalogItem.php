<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * CatalogItem Entity
 * Centralized catalog of goods and services (managed by V4L)
 */
class CatalogItem extends BaseEntity {
    protected $table = 'catalog_items';
    protected $fillable = [
        'category_id', 'type', 'name', 'brand_name', 'short_description',
        'detailed_description', 'unit_of_measure', 'thumbnail_url', 'status', 'created_by'
    ];

    /**
     * Get category
     */
    public function getCategory($itemId) {
        $sql = "SELECT cc.* FROM catalog_categories cc
                JOIN catalog_items ci ON ci.category_id = cc.id
                WHERE ci.id = ? AND cc.deleted_at IS NULL";
        return $this->queryOne($sql, [$itemId]);
    }

    /**
     * Get creator
     */
    public function getCreator($itemId) {
        $sql = "SELECT p.* FROM persons p
                JOIN catalog_items ci ON ci.created_by = p.id
                WHERE ci.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$itemId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($itemId) {
        $sql = "SELECT ci.*, cc.name as category_name,
                p.first_name as creator_first_name, p.last_name as creator_last_name
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                LEFT JOIN persons p ON ci.created_by = p.id
                WHERE ci.id = ? AND ci.deleted_at IS NULL";
        return $this->queryOne($sql, [$itemId]);
    }

    /**
     * Get features for item
     */
    public function getFeatures($itemId) {
        $sql = "SELECT * FROM catalog_item_features
                WHERE item_id = ? AND deleted_at IS NULL
                ORDER BY feature_name ASC";
        return $this->query($sql, [$itemId]);
    }

    /**
     * Get media for item
     */
    public function getMedia($itemId) {
        $sql = "SELECT * FROM catalog_item_media
                WHERE item_id = ? AND deleted_at IS NULL
                ORDER BY is_primary DESC, id ASC";
        return $this->query($sql, [$itemId]);
    }

    /**
     * Get tags for item
     */
    public function getTags($itemId) {
        $sql = "SELECT * FROM catalog_item_tags
                WHERE item_id = ? AND deleted_at IS NULL
                ORDER BY tag ASC";
        return $this->query($sql, [$itemId]);
    }

    /**
     * Get reviews for item
     */
    public function getReviews($itemId, $status = 'Visible') {
        $sql = "SELECT cir.*, p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.item_id = ? AND cir.status = ? AND cir.deleted_at IS NULL
                ORDER BY cir.review_date DESC";
        return $this->query($sql, [$itemId, $status]);
    }

    /**
     * Get average rating
     */
    public function getAverageRating($itemId) {
        $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count
                FROM catalog_item_reviews
                WHERE item_id = ? AND status = 'Visible' AND deleted_at IS NULL";
        return $this->queryOne($sql, [$itemId]);
    }

    /**
     * Get seller items (who sells this)
     */
    public function getSellerItems($itemId) {
        $sql = "SELECT si.*, o.short_name as organization_name,
                sip.final_price, sip.currency_code
                FROM seller_items si
                JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN seller_item_prices sip ON si.id = sip.seller_item_id
                    AND sip.is_active = 1 AND sip.deleted_at IS NULL
                WHERE si.catalog_item_id = ? AND si.availability_status = 'Available'
                AND si.deleted_at IS NULL
                ORDER BY sip.final_price ASC";
        return $this->query($sql, [$itemId]);
    }

    /**
     * Get items by category
     */
    public function getByCategory($categoryId, $type = null, $status = 'Active') {
        $sql = "SELECT * FROM catalog_items
                WHERE category_id = ? AND status = ? AND deleted_at IS NULL";

        $params = [$categoryId, $status];

        if ($type) {
            $sql .= " AND type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get by type
     */
    public function getByType($type, $status = 'Active') {
        $sql = "SELECT ci.*, cc.name as category_name
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                WHERE ci.type = ? AND ci.status = ? AND ci.deleted_at IS NULL
                ORDER BY ci.name ASC";
        return $this->query($sql, [$type, $status]);
    }

    /**
     * Search items
     */
    public function searchItems($term, $categoryId = null, $type = null, $limit = 50) {
        $sql = "SELECT ci.*, cc.name as category_name
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                WHERE (ci.name LIKE ? OR ci.brand_name LIKE ? OR ci.short_description LIKE ?)
                AND ci.status = 'Active' AND ci.deleted_at IS NULL";

        $params = ["%$term%", "%$term%", "%$term%"];

        if ($categoryId) {
            $sql .= " AND ci.category_id = ?";
            $params[] = $categoryId;
        }

        if ($type) {
            $sql .= " AND ci.type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY ci.name ASC LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get featured items
     */
    public function getFeaturedItems($limit = 10) {
        // This would need a featured flag or logic
        // For now, return top rated items
        $sql = "SELECT ci.*, cc.name as category_name,
                AVG(cir.rating) as avg_rating, COUNT(cir.id) as review_count
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                LEFT JOIN catalog_item_reviews cir ON ci.id = cir.item_id
                    AND cir.status = 'Visible' AND cir.deleted_at IS NULL
                WHERE ci.status = 'Active' AND ci.deleted_at IS NULL
                GROUP BY ci.id
                ORDER BY avg_rating DESC, review_count DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get items by brand
     */
    public function getByBrand($brandName, $limit = 50) {
        $sql = "SELECT ci.*, cc.name as category_name
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                WHERE ci.brand_name LIKE ? AND ci.status = 'Active' AND ci.deleted_at IS NULL
                ORDER BY ci.name ASC
                LIMIT ?";
        return $this->query($sql, ["%$brandName%", $limit]);
    }

    /**
     * Get items by tag
     */
    public function getByTag($tag, $limit = 50) {
        $sql = "SELECT DISTINCT ci.*, cc.name as category_name
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                JOIN catalog_item_tags cit ON ci.id = cit.item_id
                WHERE cit.tag = ? AND ci.status = 'Active'
                AND ci.deleted_at IS NULL AND cit.deleted_at IS NULL
                ORDER BY ci.name ASC
                LIMIT ?";
        return $this->query($sql, [$tag, $limit]);
    }

    /**
     * Activate item
     */
    public function activate($itemId) {
        return $this->update($itemId, ['status' => 'Active']);
    }

    /**
     * Deactivate item
     */
    public function deactivate($itemId) {
        return $this->update($itemId, ['status' => 'Inactive']);
    }

    /**
     * Get popular items (most sellers)
     */
    public function getPopularItems($limit = 10) {
        $sql = "SELECT ci.*, cc.name as category_name,
                COUNT(DISTINCT si.id) as seller_count
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                LEFT JOIN seller_items si ON ci.id = si.catalog_item_id AND si.deleted_at IS NULL
                WHERE ci.status = 'Active' AND ci.deleted_at IS NULL
                GROUP BY ci.id
                ORDER BY seller_count DESC, ci.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get new items (recently added)
     */
    public function getNewItems($days = 30, $limit = 10) {
        $sql = "SELECT ci.*, cc.name as category_name
                FROM catalog_items ci
                LEFT JOIN catalog_categories cc ON ci.category_id = cc.id
                WHERE ci.status = 'Active'
                AND ci.created_at >= date('now', '-' || ? || ' days')
                AND ci.deleted_at IS NULL
                ORDER BY ci.created_at DESC
                LIMIT ?";
        return $this->query($sql, [$days, $limit]);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_items,
                    COUNT(CASE WHEN status = 'Active' THEN 1 END) as active_items,
                    COUNT(CASE WHEN type = 'Good' THEN 1 END) as goods_count,
                    COUNT(CASE WHEN type = 'Service' THEN 1 END) as services_count,
                    COUNT(DISTINCT category_id) as unique_categories,
                    COUNT(DISTINCT brand_name) as unique_brands
                FROM catalog_items
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'category_id' => 'required|integer',
            'type' => 'required',
            'name' => 'required|min:2|max:200',
            'brand_name' => 'max:100',
            'short_description' => 'required|max:500',
            'detailed_description' => 'max:5000',
            'unit_of_measure' => 'max:50',
            'thumbnail_url' => 'url',
            'status' => 'required',
            'created_by' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $item = $this->find($id);
        if (!$item) {
            return 'N/A';
        }
        $label = $item['name'];
        if (!empty($item['brand_name'])) {
            $label .= ' (' . $item['brand_name'] . ')';
        }
        return $label;
    }
}
