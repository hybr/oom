<?php

namespace Entities;

/**
 * SellerItemReview Entity
 * Reviews for seller performance (distinct from catalog item quality)
 */
class SellerItemReview extends BaseEntity
{
    protected ?int $seller_item_id = null;
    protected ?int $reviewed_by = null;
    protected ?int $rating = null; // 1-5
    protected ?string $review_text = null;
    protected ?string $review_date = null;
    protected string $status = 'Visible'; // Visible, Hidden

    public static function getTableName(): string
    {
        return 'seller_item_review';
    }

    protected function getFillableAttributes(): array
    {
        return ['seller_item_id', 'reviewed_by', 'rating', 'review_text', 'review_date', 'status'];
    }

    protected function getValidationRules(): array
    {
        return [
            'seller_item_id' => ['required', 'numeric'],
            'reviewed_by' => ['required', 'numeric'],
            'rating' => ['required', 'numeric'],
            'review_text' => ['max:2000'],
        ];
    }

    /**
     * Get the seller item
     */
    public function getSellerItem(): ?SellerItem
    {
        return SellerItem::find($this->seller_item_id);
    }

    /**
     * Get the reviewer
     */
    public function getReviewer(): ?Person
    {
        return Person::find($this->reviewed_by);
    }

    /**
     * Get reviews by seller item
     */
    public static function getBySellerItem(int $sellerItemId): array
    {
        return static::where('seller_item_id = :seller_item_id AND status = :status', [
            'seller_item_id' => $sellerItemId,
            'status' => 'Visible'
        ]);
    }

    /**
     * Get reviews by person
     */
    public static function getByPerson(int $personId): array
    {
        return static::where('reviewed_by = :person_id', ['person_id' => $personId]);
    }

    /**
     * Get reviews by organization (all seller items)
     */
    public static function getByOrganization(int $organizationId): array
    {
        $sql = "SELECT sr.* FROM " . static::getTableName() . " sr
                INNER JOIN seller_item si ON sr.seller_item_id = si.id
                WHERE si.organization_id = :org_id AND sr.status = 'Visible' AND sr.deleted_at IS NULL
                ORDER BY sr.review_date DESC";
        $data = \App\Database::fetchAll($sql, ['org_id' => $organizationId]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Get visible reviews
     */
    public static function getVisibleReviews(): array
    {
        return static::where('status = :status', ['status' => 'Visible']);
    }

    /**
     * Get hidden reviews
     */
    public static function getHiddenReviews(): array
    {
        return static::where('status = :status', ['status' => 'Hidden']);
    }

    /**
     * Hide review
     */
    public function hide(?int $userId = null): bool
    {
        $this->status = 'Hidden';
        return $this->save($userId);
    }

    /**
     * Show review
     */
    public function show(?int $userId = null): bool
    {
        $this->status = 'Visible';
        return $this->save($userId);
    }

    /**
     * Get average rating for a seller item
     */
    public static function getAverageRatingBySellerItem(int $sellerItemId): float
    {
        $sql = "SELECT AVG(rating) as avg_rating FROM " . static::getTableName() . "
                WHERE seller_item_id = :seller_item_id AND status = 'Visible' AND deleted_at IS NULL";
        $result = \App\Database::fetchOne($sql, ['seller_item_id' => $sellerItemId]);

        return round((float)($result['avg_rating'] ?? 0), 2);
    }

    /**
     * Get average rating for an organization (across all seller items)
     */
    public static function getAverageRatingByOrganization(int $organizationId): float
    {
        $sql = "SELECT AVG(sr.rating) as avg_rating FROM " . static::getTableName() . " sr
                INNER JOIN seller_item si ON sr.seller_item_id = si.id
                WHERE si.organization_id = :org_id AND sr.status = 'Visible' AND sr.deleted_at IS NULL";
        $result = \App\Database::fetchOne($sql, ['org_id' => $organizationId]);

        return round((float)($result['avg_rating'] ?? 0), 2);
    }

    /**
     * Get rating distribution for a seller item
     */
    public static function getRatingDistribution(int $sellerItemId): array
    {
        $sql = "SELECT rating, COUNT(*) as count FROM " . static::getTableName() . "
                WHERE seller_item_id = :seller_item_id AND status = 'Visible' AND deleted_at IS NULL
                GROUP BY rating
                ORDER BY rating DESC";

        $results = \App\Database::fetchAll($sql, ['seller_item_id' => $sellerItemId]);

        // Initialize distribution
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        foreach ($results as $result) {
            $distribution[$result['rating']] = $result['count'];
        }

        return $distribution;
    }

    /**
     * Check if person has reviewed a seller item
     */
    public static function hasPersonReviewedSellerItem(int $personId, int $sellerItemId): bool
    {
        return static::count('reviewed_by = :person_id AND seller_item_id = :seller_item_id', [
            'person_id' => $personId,
            'seller_item_id' => $sellerItemId
        ]) > 0;
    }

    /**
     * Get recent reviews
     */
    public static function getRecentReviews(int $limit = 10): array
    {
        return static::where('status = :status ORDER BY review_date DESC', ['status' => 'Visible'], $limit);
    }

    /**
     * Count reviews for a seller item
     */
    public static function countBySellerItem(int $sellerItemId): int
    {
        return static::count('seller_item_id = :seller_item_id AND status = :status', [
            'seller_item_id' => $sellerItemId,
            'status' => 'Visible'
        ]);
    }

    /**
     * Count reviews for an organization
     */
    public static function countByOrganization(int $organizationId): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::getTableName() . " sr
                INNER JOIN seller_item si ON sr.seller_item_id = si.id
                WHERE si.organization_id = :org_id AND sr.status = 'Visible' AND sr.deleted_at IS NULL";
        $result = \App\Database::fetchOne($sql, ['org_id' => $organizationId]);

        return (int)($result['count'] ?? 0);
    }

    /**
     * Get top rated sellers
     */
    public static function getTopRatedSellers(int $limit = 10): array
    {
        $sql = "SELECT si.organization_id, AVG(sr.rating) as avg_rating, COUNT(*) as review_count
                FROM " . static::getTableName() . " sr
                INNER JOIN seller_item si ON sr.seller_item_id = si.id
                WHERE sr.status = 'Visible' AND sr.deleted_at IS NULL
                GROUP BY si.organization_id
                HAVING review_count >= 3
                ORDER BY avg_rating DESC, review_count DESC
                LIMIT :limit";

        return \App\Database::fetchAll($sql, ['limit' => $limit]);
    }

    /**
     * Format review date for display
     */
    public function getFormattedDate(): string
    {
        if ($this->review_date) {
            return date('M j, Y', strtotime($this->review_date));
        }
        return '';
    }

    /**
     * Get rating stars (visual representation)
     */
    public function getRatingStars(): string
    {
        $fullStars = $this->rating;
        $emptyStars = 5 - $fullStars;

        return str_repeat('★', $fullStars) . str_repeat('☆', $emptyStars);
    }
}
