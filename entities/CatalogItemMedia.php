<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * CatalogItemMedia Entity
 * Images, videos, and other media for catalog items
 */
class CatalogItemMedia extends BaseEntity {
    protected $table = 'catalog_item_media';
    protected $fillable = ['item_id', 'media_type', 'media_url', 'caption', 'is_primary'];

    /**
     * Get catalog item
     */
    public function getCatalogItem($mediaId) {
        $sql = "SELECT ci.* FROM catalog_items ci
                JOIN catalog_item_media cim ON cim.item_id = ci.id
                WHERE cim.id = ? AND ci.deleted_at IS NULL";
        return $this->queryOne($sql, [$mediaId]);
    }

    /**
     * Get with item details
     */
    public function getWithDetails($mediaId) {
        $sql = "SELECT cim.*, ci.name as item_name, ci.type as item_type
                FROM catalog_item_media cim
                LEFT JOIN catalog_items ci ON cim.item_id = ci.id
                WHERE cim.id = ? AND cim.deleted_at IS NULL";
        return $this->queryOne($sql, [$mediaId]);
    }

    /**
     * Get media by item
     */
    public function getByItem($itemId, $mediaType = null) {
        $sql = "SELECT * FROM catalog_item_media
                WHERE item_id = ? AND deleted_at IS NULL";

        $params = [$itemId];

        if ($mediaType) {
            $sql .= " AND media_type = ?";
            $params[] = $mediaType;
        }

        $sql .= " ORDER BY is_primary DESC, id ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get primary media
     */
    public function getPrimaryMedia($itemId, $mediaType = null) {
        $sql = "SELECT * FROM catalog_item_media
                WHERE item_id = ? AND is_primary = 1 AND deleted_at IS NULL";

        $params = [$itemId];

        if ($mediaType) {
            $sql .= " AND media_type = ?";
            $params[] = $mediaType;
        }

        $sql .= " LIMIT 1";
        return $this->queryOne($sql, $params);
    }

    /**
     * Get media by type
     */
    public function getByType($mediaType) {
        $sql = "SELECT cim.*, ci.name as item_name
                FROM catalog_item_media cim
                LEFT JOIN catalog_items ci ON cim.item_id = ci.id
                WHERE cim.media_type = ? AND cim.deleted_at IS NULL
                ORDER BY ci.name ASC";
        return $this->query($sql, [$mediaType]);
    }

    /**
     * Get images for item
     */
    public function getImages($itemId) {
        return $this->getByItem($itemId, 'Image');
    }

    /**
     * Get videos for item
     */
    public function getVideos($itemId) {
        return $this->getByItem($itemId, 'Video');
    }

    /**
     * Set as primary
     */
    public function setAsPrimary($mediaId) {
        $media = $this->find($mediaId);
        if (!$media) {
            return false;
        }

        // Unset other primary media for this item and type
        $sql = "UPDATE catalog_item_media
                SET is_primary = 0, updated_at = datetime('now')
                WHERE item_id = ? AND media_type = ? AND deleted_at IS NULL";
        $this->db->execute($sql, [$media['item_id'], $media['media_type']]);

        // Set this as primary
        return $this->update($mediaId, ['is_primary' => 1]);
    }

    /**
     * Get media count
     */
    public function getMediaCount($itemId, $mediaType = null) {
        $sql = "SELECT COUNT(*) as count FROM catalog_item_media
                WHERE item_id = ? AND deleted_at IS NULL";

        $params = [$itemId];

        if ($mediaType) {
            $sql .= " AND media_type = ?";
            $params[] = $mediaType;
        }

        $result = $this->queryOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Get items without media
     */
    public function getItemsWithoutMedia($mediaType = null) {
        $sql = "SELECT ci.*
                FROM catalog_items ci
                LEFT JOIN catalog_item_media cim ON ci.id = cim.item_id";

        if ($mediaType) {
            $sql .= " AND cim.media_type = ?";
        }

        $sql .= " WHERE ci.status = 'Active' AND ci.deleted_at IS NULL
                  GROUP BY ci.id
                  HAVING COUNT(cim.id) = 0
                  ORDER BY ci.name ASC";

        return $mediaType ? $this->query($sql, [$mediaType]) : $this->query($sql);
    }

    /**
     * Get media types
     */
    public function getMediaTypes() {
        return ['Image', 'Video', 'PDF', 'SpecSheet', '3D'];
    }

    /**
     * Bulk add media
     */
    public function bulkAdd($itemId, $mediaList) {
        $added = 0;
        foreach ($mediaList as $media) {
            $this->create([
                'item_id' => $itemId,
                'media_type' => $media['media_type'],
                'media_url' => $media['media_url'],
                'caption' => $media['caption'] ?? '',
                'is_primary' => $media['is_primary'] ?? 0
            ]);
            $added++;
        }
        return $added;
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_media,
                    COUNT(DISTINCT item_id) as items_with_media,
                    COUNT(CASE WHEN media_type = 'Image' THEN 1 END) as image_count,
                    COUNT(CASE WHEN media_type = 'Video' THEN 1 END) as video_count,
                    COUNT(CASE WHEN is_primary = 1 THEN 1 END) as primary_media_count
                FROM catalog_item_media
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'item_id' => 'required|integer',
            'media_type' => 'required',
            'media_url' => 'required|url',
            'caption' => 'max:500',
            'is_primary' => 'boolean',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $media = $this->getWithDetails($id);
        if (!$media) {
            return 'N/A';
        }
        return $media['media_type'] . ' - ' . $media['item_name'];
    }
}
