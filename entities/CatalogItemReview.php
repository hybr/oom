<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * CatalogItemReview Entity
 * Reviews and ratings for catalog items (product quality reviews)
 */
class CatalogItemReview extends BaseEntity {
    protected $table = 'catalog_item_reviews';
    protected $fillable = ['item_id', 'reviewed_by', 'rating', 'review_text', 'review_date', 'status'];

    /**
     * Get catalog item
     */
    public function getCatalogItem($reviewId) {
        $sql = "SELECT ci.* FROM catalog_items ci
                JOIN catalog_item_reviews cir ON cir.item_id = ci.id
                WHERE cir.id = ? AND ci.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get reviewer
     */
    public function getReviewer($reviewId) {
        $sql = "SELECT p.* FROM persons p
                JOIN catalog_item_reviews cir ON cir.reviewed_by = p.id
                WHERE cir.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($reviewId) {
        $sql = "SELECT cir.*,
                ci.name as item_name, ci.type as item_type,
                p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.id = ? AND cir.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get reviews by item
     */
    public function getByItem($itemId, $status = 'Visible') {
        $sql = "SELECT cir.*, p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.item_id = ? AND cir.status = ? AND cir.deleted_at IS NULL
                ORDER BY cir.review_date DESC";
        return $this->query($sql, [$itemId, $status]);
    }

    /**
     * Get reviews by reviewer
     */
    public function getByReviewer($reviewerId, $status = null) {
        $sql = "SELECT cir.*, ci.name as item_name, ci.type as item_type
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                WHERE cir.reviewed_by = ? AND cir.deleted_at IS NULL";

        $params = [$reviewerId];

        if ($status) {
            $sql .= " AND cir.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY cir.review_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get by status
     */
    public function getByStatus($status) {
        $sql = "SELECT cir.*,
                ci.name as item_name,
                p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.status = ? AND cir.deleted_at IS NULL
                ORDER BY cir.review_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get by rating
     */
    public function getByRating($rating, $itemId = null) {
        $sql = "SELECT cir.*,
                ci.name as item_name,
                p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.rating = ? AND cir.status = 'Visible' AND cir.deleted_at IS NULL";

        $params = [$rating];

        if ($itemId) {
            $sql .= " AND cir.item_id = ?";
            $params[] = $itemId;
        }

        $sql .= " ORDER BY cir.review_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get average rating for item
     */
    public function getAverageRating($itemId) {
        $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count
                FROM catalog_item_reviews
                WHERE item_id = ? AND status = 'Visible' AND deleted_at IS NULL";
        return $this->queryOne($sql, [$itemId]);
    }

    /**
     * Get rating distribution for item
     */
    public function getRatingDistribution($itemId) {
        $sql = "SELECT rating, COUNT(*) as count
                FROM catalog_item_reviews
                WHERE item_id = ? AND status = 'Visible' AND deleted_at IS NULL
                GROUP BY rating
                ORDER BY rating DESC";
        return $this->query($sql, [$itemId]);
    }

    /**
     * Show review (make visible)
     */
    public function show($reviewId) {
        return $this->update($reviewId, ['status' => 'Visible']);
    }

    /**
     * Hide review
     */
    public function hide($reviewId) {
        return $this->update($reviewId, ['status' => 'Hidden']);
    }

    /**
     * Check if person has reviewed item
     */
    public function hasReviewed($itemId, $reviewerId) {
        $sql = "SELECT COUNT(*) as count
                FROM catalog_item_reviews
                WHERE item_id = ? AND reviewed_by = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$itemId, $reviewerId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get recent reviews
     */
    public function getRecentReviews($days = 7, $limit = 10) {
        $sql = "SELECT cir.*,
                ci.name as item_name,
                p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.review_date >= date('now', '-' || ? || ' days')
                AND cir.status = 'Visible' AND cir.deleted_at IS NULL
                ORDER BY cir.review_date DESC
                LIMIT ?";
        return $this->query($sql, [$days, $limit]);
    }

    /**
     * Get top rated items
     */
    public function getTopRatedItems($limit = 10) {
        $sql = "SELECT ci.*, AVG(cir.rating) as avg_rating, COUNT(cir.id) as review_count
                FROM catalog_items ci
                JOIN catalog_item_reviews cir ON ci.id = cir.item_id
                WHERE cir.status = 'Visible' AND ci.status = 'Active'
                AND ci.deleted_at IS NULL AND cir.deleted_at IS NULL
                GROUP BY ci.id
                HAVING review_count >= 3
                ORDER BY avg_rating DESC, review_count DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get pending reviews (for moderation)
     */
    public function getPendingReviews($limit = 50) {
        $sql = "SELECT cir.*,
                ci.name as item_name,
                p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.status = 'Pending' AND cir.deleted_at IS NULL
                ORDER BY cir.review_date ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get reviews in date range
     */
    public function getInDateRange($startDate, $endDate) {
        $sql = "SELECT cir.*,
                ci.name as item_name,
                p.first_name, p.last_name
                FROM catalog_item_reviews cir
                LEFT JOIN catalog_items ci ON cir.item_id = ci.id
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.review_date BETWEEN ? AND ?
                AND cir.deleted_at IS NULL
                ORDER BY cir.review_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    /**
     * Get helpful reviews (highest rated)
     */
    public function getHelpfulReviews($itemId, $limit = 5) {
        // This would need a helpfulness rating system
        // For now, return reviews with most detail (longer text)
        $sql = "SELECT cir.*, p.first_name, p.last_name,
                LENGTH(cir.review_text) as text_length
                FROM catalog_item_reviews cir
                LEFT JOIN persons p ON cir.reviewed_by = p.id
                WHERE cir.item_id = ? AND cir.status = 'Visible'
                AND cir.review_text IS NOT NULL
                AND cir.deleted_at IS NULL
                ORDER BY text_length DESC, cir.rating DESC
                LIMIT ?";
        return $this->query($sql, [$itemId, $limit]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($itemId = null) {
        $sql = "SELECT
                    COUNT(*) as total_reviews,
                    COUNT(CASE WHEN status = 'Visible' THEN 1 END) as visible_count,
                    COUNT(CASE WHEN status = 'Hidden' THEN 1 END) as hidden_count,
                    AVG(rating) as average_rating,
                    MIN(rating) as min_rating,
                    MAX(rating) as max_rating,
                    COUNT(DISTINCT item_id) as unique_items,
                    COUNT(DISTINCT reviewed_by) as unique_reviewers
                FROM catalog_item_reviews
                WHERE deleted_at IS NULL";

        $params = [];

        if ($itemId) {
            $sql .= " AND item_id = ?";
            $params[] = $itemId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'item_id' => 'required|integer',
            'reviewed_by' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'max:2000',
            'review_date' => 'required|date',
            'status' => 'required',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $review = $this->getWithDetails($id);
        if (!$review) {
            return 'N/A';
        }
        return $review['item_name'] . ' - ' . $review['rating'] . ' stars by ' . $review['first_name'];
    }
}
