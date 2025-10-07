<?php

namespace Entities;

/**
 * CatalogItemReview Entity
 * Reviews for catalog items (product quality, not seller performance)
 */
class CatalogItemReview extends BaseEntity
{
    protected ?int $item_id = null;
    protected ?int $reviewed_by = null;
    protected ?int $rating = null; // 1-5
    protected ?string $review_text = null;
    protected ?string $review_date = null;
    protected string $status = 'Visible'; // Visible, Hidden

    public static function getTableName(): string
    {
        return 'catalog_item_review';
    }

    protected function getFillableAttributes(): array
    {
        return ['item_id', 'reviewed_by', 'rating', 'review_text', 'review_date', 'status'];
    }

    protected function getValidationRules(): array
    {
        return [
            'item_id' => ['required', 'numeric'],
            'reviewed_by' => ['required', 'numeric'],
            'rating' => ['required', 'numeric'],
            'review_text' => ['max:2000'],
        ];
    }

    /**
     * Get the catalog item
     */
    public function getCatalogItem(): ?CatalogItem
    {
        return CatalogItem::find($this->item_id);
    }

    /**
     * Get the reviewer
     */
    public function getReviewer(): ?Person
    {
        return Person::find($this->reviewed_by);
    }

    /**
     * Get reviews by item
     */
    public static function getByItem(int $itemId): array
    {
        return static::where('item_id = :item_id AND status = :status', [
            'item_id' => $itemId,
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
     * Get average rating for an item
     */
    public static function getAverageRatingByItem(int $itemId): float
    {
        $sql = "SELECT AVG(rating) as avg_rating FROM " . static::getTableName() . "
                WHERE item_id = :item_id AND status = 'Visible' AND deleted_at IS NULL";
        $result = \App\Database::fetchOne($sql, ['item_id' => $itemId]);

        return round((float)($result['avg_rating'] ?? 0), 2);
    }

    /**
     * Get rating distribution for an item
     */
    public static function getRatingDistribution(int $itemId): array
    {
        $sql = "SELECT rating, COUNT(*) as count FROM " . static::getTableName() . "
                WHERE item_id = :item_id AND status = 'Visible' AND deleted_at IS NULL
                GROUP BY rating
                ORDER BY rating DESC";

        $results = \App\Database::fetchAll($sql, ['item_id' => $itemId]);

        // Initialize distribution
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        foreach ($results as $result) {
            $distribution[$result['rating']] = $result['count'];
        }

        return $distribution;
    }

    /**
     * Check if person has reviewed an item
     */
    public static function hasPersonReviewedItem(int $personId, int $itemId): bool
    {
        return static::count('reviewed_by = :person_id AND item_id = :item_id', [
            'person_id' => $personId,
            'item_id' => $itemId
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
     * Count reviews for an item
     */
    public static function countByItem(int $itemId): int
    {
        return static::count('item_id = :item_id AND status = :status', [
            'item_id' => $itemId,
            'status' => 'Visible'
        ]);
    }

    /**
     * Get top rated items
     */
    public static function getTopRatedItems(int $limit = 10): array
    {
        $sql = "SELECT item_id, AVG(rating) as avg_rating, COUNT(*) as review_count
                FROM " . static::getTableName() . "
                WHERE status = 'Visible' AND deleted_at IS NULL
                GROUP BY item_id
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
