<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * SellerItemReview Entity
 * Reviews and ratings for seller performance (service quality, delivery, etc.)
 */
class SellerItemReview extends BaseEntity {
    protected $table = 'seller_item_reviews';
    protected $fillable = [
        'seller_item_id', 'reviewed_by', 'rating', 'review_text',
        'review_date', 'status'
    ];

    /**
     * Get seller item
     */
    public function getSellerItem($reviewId) {
        $sql = "SELECT si.* FROM seller_items si
                JOIN seller_item_reviews sir ON sir.seller_item_id = si.id
                WHERE sir.id = ? AND si.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get reviewer
     */
    public function getReviewer($reviewId) {
        $sql = "SELECT p.* FROM persons p
                JOIN seller_item_reviews sir ON sir.reviewed_by = p.id
                WHERE sir.id = ? AND p.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($reviewId) {
        $sql = "SELECT sir.*,
                si.type as seller_item_type,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.id = ? AND sir.deleted_at IS NULL";
        return $this->queryOne($sql, [$reviewId]);
    }

    /**
     * Get reviews by seller item
     */
    public function getBySellerItem($sellerItemId, $status = 'Visible') {
        $sql = "SELECT sir.*, p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.seller_item_id = ? AND sir.status = ? AND sir.deleted_at IS NULL
                ORDER BY sir.review_date DESC";
        return $this->query($sql, [$sellerItemId, $status]);
    }

    /**
     * Get reviews by reviewer
     */
    public function getByReviewer($reviewerId, $status = null) {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sir.reviewed_by = ? AND sir.deleted_at IS NULL";

        $params = [$reviewerId];

        if ($status) {
            $sql .= " AND sir.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY sir.review_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get reviews by organization
     */
    public function getByOrganization($organizationId, $status = 'Visible') {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE si.organization_id = ? AND sir.status = ? AND sir.deleted_at IS NULL
                ORDER BY sir.review_date DESC";
        return $this->query($sql, [$organizationId, $status]);
    }

    /**
     * Get by status
     */
    public function getByStatus($status) {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.status = ? AND sir.deleted_at IS NULL
                ORDER BY sir.review_date DESC";
        return $this->query($sql, [$status]);
    }

    /**
     * Get by rating
     */
    public function getByRating($rating, $sellerItemId = null, $organizationId = null) {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.rating = ? AND sir.status = 'Visible' AND sir.deleted_at IS NULL";

        $params = [$rating];

        if ($sellerItemId) {
            $sql .= " AND sir.seller_item_id = ?";
            $params[] = $sellerItemId;
        }

        if ($organizationId) {
            $sql .= " AND si.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sir.review_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get average rating for seller item
     */
    public function getAverageRating($sellerItemId) {
        $sql = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count
                FROM seller_item_reviews
                WHERE seller_item_id = ? AND status = 'Visible' AND deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get rating distribution for seller item
     */
    public function getRatingDistribution($sellerItemId) {
        $sql = "SELECT rating, COUNT(*) as count
                FROM seller_item_reviews
                WHERE seller_item_id = ? AND status = 'Visible' AND deleted_at IS NULL
                GROUP BY rating
                ORDER BY rating DESC";
        return $this->query($sql, [$sellerItemId]);
    }

    /**
     * Get organization average rating
     */
    public function getOrganizationAverageRating($organizationId) {
        $sql = "SELECT AVG(sir.rating) as average_rating, COUNT(sir.id) as review_count
                FROM seller_item_reviews sir
                JOIN seller_items si ON sir.seller_item_id = si.id
                WHERE si.organization_id = ? AND sir.status = 'Visible' AND sir.deleted_at IS NULL";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get organization rating distribution
     */
    public function getOrganizationRatingDistribution($organizationId) {
        $sql = "SELECT sir.rating, COUNT(*) as count
                FROM seller_item_reviews sir
                JOIN seller_items si ON sir.seller_item_id = si.id
                WHERE si.organization_id = ? AND sir.status = 'Visible' AND sir.deleted_at IS NULL
                GROUP BY sir.rating
                ORDER BY sir.rating DESC";
        return $this->query($sql, [$organizationId]);
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
     * Mark as pending
     */
    public function markPending($reviewId) {
        return $this->update($reviewId, ['status' => 'Pending']);
    }

    /**
     * Check if person has reviewed seller item
     */
    public function hasReviewed($sellerItemId, $reviewerId) {
        $sql = "SELECT COUNT(*) as count
                FROM seller_item_reviews
                WHERE seller_item_id = ? AND reviewed_by = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$sellerItemId, $reviewerId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get recent reviews
     */
    public function getRecentReviews($days = 7, $organizationId = null, $limit = 10) {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.review_date >= date('now', '-' || ? || ' days')
                AND sir.status = 'Visible' AND sir.deleted_at IS NULL";

        $params = [$days];

        if ($organizationId) {
            $sql .= " AND si.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sir.review_date DESC LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get top rated sellers
     */
    public function getTopRatedSellers($limit = 10) {
        $sql = "SELECT o.*, AVG(sir.rating) as avg_rating, COUNT(sir.id) as review_count
                FROM organizations o
                JOIN seller_items si ON o.id = si.organization_id
                JOIN seller_item_reviews sir ON si.id = sir.seller_item_id
                WHERE sir.status = 'Visible' AND o.deleted_at IS NULL AND sir.deleted_at IS NULL
                GROUP BY o.id
                HAVING review_count >= 3
                ORDER BY avg_rating DESC, review_count DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get top rated seller items
     */
    public function getTopRatedItems($limit = 10, $organizationId = null) {
        $sql = "SELECT si.*, ci.name as catalog_item_name,
                o.short_name as organization_name,
                AVG(sir.rating) as avg_rating, COUNT(sir.id) as review_count
                FROM seller_items si
                JOIN seller_item_reviews sir ON si.id = sir.seller_item_id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sir.status = 'Visible' AND si.availability_status = 'Available'
                AND si.deleted_at IS NULL AND sir.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND si.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " GROUP BY si.id
                  HAVING review_count >= 3
                  ORDER BY avg_rating DESC, review_count DESC
                  LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get pending reviews (for moderation)
     */
    public function getPendingReviews($organizationId = null, $limit = 50) {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.status = 'Pending' AND sir.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND si.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sir.review_date ASC LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get reviews in date range
     */
    public function getInDateRange($startDate, $endDate, $organizationId = null) {
        $sql = "SELECT sir.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                p.first_name, p.last_name
                FROM seller_item_reviews sir
                LEFT JOIN seller_items si ON sir.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.review_date BETWEEN ? AND ?
                AND sir.deleted_at IS NULL";

        $params = [$startDate, $endDate];

        if ($organizationId) {
            $sql .= " AND si.organization_id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sir.review_date DESC";
        return $this->query($sql, $params);
    }

    /**
     * Get helpful reviews (highest rated with detailed text)
     */
    public function getHelpfulReviews($sellerItemId, $limit = 5) {
        $sql = "SELECT sir.*, p.first_name, p.last_name,
                LENGTH(sir.review_text) as text_length
                FROM seller_item_reviews sir
                LEFT JOIN persons p ON sir.reviewed_by = p.id
                WHERE sir.seller_item_id = ? AND sir.status = 'Visible'
                AND sir.review_text IS NOT NULL
                AND sir.deleted_at IS NULL
                ORDER BY text_length DESC, sir.rating DESC
                LIMIT ?";
        return $this->query($sql, [$sellerItemId, $limit]);
    }

    /**
     * Get statistics
     */
    public function getStatistics($sellerItemId = null, $organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_reviews,
                    COUNT(CASE WHEN status = 'Visible' THEN 1 END) as visible_count,
                    COUNT(CASE WHEN status = 'Hidden' THEN 1 END) as hidden_count,
                    COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending_count,
                    AVG(rating) as average_rating,
                    MIN(rating) as min_rating,
                    MAX(rating) as max_rating,
                    COUNT(DISTINCT seller_item_id) as unique_seller_items,
                    COUNT(DISTINCT reviewed_by) as unique_reviewers
                FROM seller_item_reviews sir";

        $params = [];
        $joins = [];

        if ($sellerItemId) {
            $sql .= " WHERE sir.seller_item_id = ? AND sir.deleted_at IS NULL";
            $params[] = $sellerItemId;
        } elseif ($organizationId) {
            $joins[] = " JOIN seller_items si ON sir.seller_item_id = si.id";
            $sql .= " WHERE si.organization_id = ? AND sir.deleted_at IS NULL";
            $params[] = $organizationId;
        } else {
            $sql .= " WHERE sir.deleted_at IS NULL";
        }

        // Add joins before WHERE clause
        if (!empty($joins)) {
            $sql = str_replace('FROM seller_item_reviews sir',
                             'FROM seller_item_reviews sir' . implode('', $joins), $sql);
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'seller_item_id' => 'required|integer',
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
        return $review['organization_name'] . ' - ' . $review['catalog_item_name'] . ' - ' . $review['rating'] . ' stars';
    }
}
