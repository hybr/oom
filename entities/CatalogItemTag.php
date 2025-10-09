<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * CatalogItemTag Entity
 * Tags for categorizing and searching catalog items
 */
class CatalogItemTag extends BaseEntity {
    protected $table = 'catalog_item_tags';
    protected $fillable = ['item_id', 'tag'];

    /**
     * Get catalog item
     */
    public function getCatalogItem($tagId) {
        $sql = "SELECT ci.* FROM catalog_items ci
                JOIN catalog_item_tags cit ON cit.item_id = ci.id
                WHERE cit.id = ? AND ci.deleted_at IS NULL";
        return $this->queryOne($sql, [$tagId]);
    }

    /**
     * Get with item details
     */
    public function getWithDetails($tagId) {
        $sql = "SELECT cit.*, ci.name as item_name, ci.type as item_type
                FROM catalog_item_tags cit
                LEFT JOIN catalog_items ci ON cit.item_id = ci.id
                WHERE cit.id = ? AND cit.deleted_at IS NULL";
        return $this->queryOne($sql, [$tagId]);
    }

    /**
     * Get tags by item
     */
    public function getByItem($itemId) {
        return $this->all(['item_id' => $itemId], 'tag ASC');
    }

    /**
     * Get items by tag
     */
    public function getItemsByTag($tag, $limit = 50) {
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
     * Get all unique tags
     */
    public function getAllTags() {
        $sql = "SELECT DISTINCT tag, COUNT(*) as item_count
                FROM catalog_item_tags
                WHERE deleted_at IS NULL
                GROUP BY tag
                ORDER BY tag ASC";
        return $this->query($sql);
    }

    /**
     * Get popular tags
     */
    public function getPopularTags($limit = 20) {
        $sql = "SELECT tag, COUNT(*) as item_count
                FROM catalog_item_tags
                WHERE deleted_at IS NULL
                GROUP BY tag
                ORDER BY item_count DESC, tag ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Search tags
     */
    public function searchTags($term, $limit = 50) {
        $sql = "SELECT DISTINCT tag, COUNT(*) as item_count
                FROM catalog_item_tags
                WHERE tag LIKE ? AND deleted_at IS NULL
                GROUP BY tag
                ORDER BY tag ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", $limit]);
    }

    /**
     * Check if tag exists for item
     */
    public function tagExists($itemId, $tag, $exceptId = null) {
        $sql = "SELECT id FROM catalog_item_tags
                WHERE item_id = ? AND tag = ? AND deleted_at IS NULL";
        $params = [$itemId, $tag];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Add tag to item
     */
    public function addTag($itemId, $tag) {
        // Normalize tag (lowercase, trim)
        $tag = strtolower(trim($tag));

        if ($this->tagExists($itemId, $tag)) {
            return ['success' => false, 'message' => 'Tag already exists for this item'];
        }

        $tagId = $this->create([
            'item_id' => $itemId,
            'tag' => $tag
        ]);

        if ($tagId) {
            return ['success' => true, 'tag_id' => $tagId];
        }

        return ['success' => false, 'message' => 'Failed to add tag'];
    }

    /**
     * Remove tag from item
     */
    public function removeTag($itemId, $tag) {
        $sql = "SELECT id FROM catalog_item_tags
                WHERE item_id = ? AND tag = ? AND deleted_at IS NULL";
        $tagRecord = $this->queryOne($sql, [$itemId, $tag]);

        if ($tagRecord) {
            return $this->delete($tagRecord['id']);
        }

        return false;
    }

    /**
     * Bulk add tags
     */
    public function bulkAdd($itemId, $tags) {
        $added = 0;
        foreach ($tags as $tag) {
            $tag = strtolower(trim($tag));
            if (!$this->tagExists($itemId, $tag)) {
                $this->create([
                    'item_id' => $itemId,
                    'tag' => $tag
                ]);
                $added++;
            }
        }
        return $added;
    }

    /**
     * Get tag count for item
     */
    public function getTagCount($itemId) {
        $sql = "SELECT COUNT(*) as count FROM catalog_item_tags
                WHERE item_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$itemId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get related tags (tags used together)
     */
    public function getRelatedTags($tag, $limit = 10) {
        $sql = "SELECT cit2.tag, COUNT(*) as co_occurrence
                FROM catalog_item_tags cit1
                JOIN catalog_item_tags cit2 ON cit1.item_id = cit2.item_id
                WHERE cit1.tag = ? AND cit2.tag != ?
                AND cit1.deleted_at IS NULL AND cit2.deleted_at IS NULL
                GROUP BY cit2.tag
                ORDER BY co_occurrence DESC, cit2.tag ASC
                LIMIT ?";
        return $this->query($sql, [$tag, $tag, $limit]);
    }

    /**
     * Get tag cloud data
     */
    public function getTagCloud($minCount = 1, $limit = 50) {
        $sql = "SELECT tag, COUNT(*) as count
                FROM catalog_item_tags
                WHERE deleted_at IS NULL
                GROUP BY tag
                HAVING count >= ?
                ORDER BY count DESC, tag ASC
                LIMIT ?";
        return $this->query($sql, [$minCount, $limit]);
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_tag_assignments,
                    COUNT(DISTINCT tag) as unique_tags,
                    COUNT(DISTINCT item_id) as items_with_tags,
                    AVG(tag_count) as avg_tags_per_item
                FROM (
                    SELECT item_id, COUNT(*) as tag_count
                    FROM catalog_item_tags
                    WHERE deleted_at IS NULL
                    GROUP BY item_id
                ) as subquery";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'item_id' => 'required|integer',
            'tag' => 'required|min:2|max:50',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $tag = $this->getWithDetails($id);
        if (!$tag) {
            return 'N/A';
        }
        return '#' . $tag['tag'] . ' - ' . $tag['item_name'];
    }
}
