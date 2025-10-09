<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * CatalogItemFeature Entity
 * Technical specifications and features of catalog items
 */
class CatalogItemFeature extends BaseEntity {
    protected $table = 'catalog_item_features';
    protected $fillable = ['item_id', 'feature_name', 'feature_value'];

    /**
     * Get catalog item
     */
    public function getCatalogItem($featureId) {
        $sql = "SELECT ci.* FROM catalog_items ci
                JOIN catalog_item_features cif ON cif.item_id = ci.id
                WHERE cif.id = ? AND ci.deleted_at IS NULL";
        return $this->queryOne($sql, [$featureId]);
    }

    /**
     * Get with item details
     */
    public function getWithDetails($featureId) {
        $sql = "SELECT cif.*, ci.name as item_name, ci.type as item_type
                FROM catalog_item_features cif
                LEFT JOIN catalog_items ci ON cif.item_id = ci.id
                WHERE cif.id = ? AND cif.deleted_at IS NULL";
        return $this->queryOne($sql, [$featureId]);
    }

    /**
     * Get features by item
     */
    public function getByItem($itemId) {
        return $this->all(['item_id' => $itemId], 'feature_name ASC');
    }

    /**
     * Get by feature name
     */
    public function getByFeatureName($featureName) {
        $sql = "SELECT cif.*, ci.name as item_name
                FROM catalog_item_features cif
                LEFT JOIN catalog_items ci ON cif.item_id = ci.id
                WHERE cif.feature_name = ? AND cif.deleted_at IS NULL
                ORDER BY ci.name ASC";
        return $this->query($sql, [$featureName]);
    }

    /**
     * Search by feature value
     */
    public function searchByValue($featureName, $value, $limit = 50) {
        $sql = "SELECT cif.*, ci.name as item_name
                FROM catalog_item_features cif
                LEFT JOIN catalog_items ci ON cif.item_id = ci.id
                WHERE cif.feature_name = ? AND cif.feature_value LIKE ?
                AND cif.deleted_at IS NULL
                ORDER BY ci.name ASC
                LIMIT ?";
        return $this->query($sql, [$featureName, "%$value%", $limit]);
    }

    /**
     * Get unique feature names
     */
    public function getUniqueFeatureNames() {
        $sql = "SELECT DISTINCT feature_name
                FROM catalog_item_features
                WHERE deleted_at IS NULL
                ORDER BY feature_name ASC";
        return $this->query($sql);
    }

    /**
     * Get unique values for feature
     */
    public function getUniqueValuesForFeature($featureName) {
        $sql = "SELECT DISTINCT feature_value
                FROM catalog_item_features
                WHERE feature_name = ? AND deleted_at IS NULL
                ORDER BY feature_value ASC";
        return $this->query($sql, [$featureName]);
    }

    /**
     * Get feature count by item
     */
    public function getFeatureCount($itemId) {
        $sql = "SELECT COUNT(*) as count
                FROM catalog_item_features
                WHERE item_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$itemId]);
        return $result['count'] ?? 0;
    }

    /**
     * Check if feature exists for item
     */
    public function featureExists($itemId, $featureName, $exceptId = null) {
        $sql = "SELECT id FROM catalog_item_features
                WHERE item_id = ? AND feature_name = ? AND deleted_at IS NULL";
        $params = [$itemId, $featureName];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Bulk add features
     */
    public function bulkAdd($itemId, $features) {
        $added = 0;
        foreach ($features as $feature) {
            if (!$this->featureExists($itemId, $feature['feature_name'])) {
                $this->create([
                    'item_id' => $itemId,
                    'feature_name' => $feature['feature_name'],
                    'feature_value' => $feature['feature_value']
                ]);
                $added++;
            }
        }
        return $added;
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_features,
                    COUNT(DISTINCT item_id) as items_with_features,
                    COUNT(DISTINCT feature_name) as unique_feature_names
                FROM catalog_item_features
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'item_id' => 'required|integer',
            'feature_name' => 'required|min:2|max:200',
            'feature_value' => 'required|max:500',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $feature = $this->find($id);
        if (!$feature) {
            return 'N/A';
        }
        return $feature['feature_name'] . ': ' . $feature['feature_value'];
    }
}
