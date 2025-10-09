<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * CatalogCategory Entity
 * Hierarchical product/service categories managed by V4L
 */
class CatalogCategory extends BaseEntity {
    protected $table = 'catalog_categories';
    protected $fillable = ['name', 'description', 'parent_category_id', 'is_active'];

    /**
     * Get parent category
     */
    public function getParentCategory($categoryId) {
        $sql = "SELECT * FROM catalog_categories
                WHERE id = (SELECT parent_category_id FROM catalog_categories WHERE id = ?)
                AND deleted_at IS NULL";
        return $this->queryOne($sql, [$categoryId]);
    }

    /**
     * Get child categories
     */
    public function getChildCategories($categoryId) {
        return $this->all(['parent_category_id' => $categoryId, 'is_active' => 1], 'name ASC');
    }

    /**
     * Get root categories
     */
    public function getRootCategories() {
        $sql = "SELECT * FROM catalog_categories
                WHERE parent_category_id IS NULL AND is_active = 1 AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql);
    }

    /**
     * Get full category path
     */
    public function getCategoryPath($categoryId) {
        $path = [];
        $currentId = $categoryId;

        while ($currentId) {
            $category = $this->find($currentId);
            if (!$category) break;

            array_unshift($path, $category['name']);
            $currentId = $category['parent_category_id'];
        }

        return implode(' > ', $path);
    }

    /**
     * Get category tree
     */
    public function getCategoryTree($parentId = null, $level = 0) {
        $categories = $parentId === null
            ? $this->getRootCategories()
            : $this->getChildCategories($parentId);

        $tree = [];
        foreach ($categories as $category) {
            $category['level'] = $level;
            $category['children'] = $this->getCategoryTree($category['id'], $level + 1);
            $tree[] = $category;
        }

        return $tree;
    }

    /**
     * Get catalog items in category
     */
    public function getCatalogItems($categoryId, $includeSubcategories = false) {
        if ($includeSubcategories) {
            $categoryIds = $this->getAllDescendantIds($categoryId);
            $categoryIds[] = $categoryId;
            $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

            $sql = "SELECT * FROM catalog_items
                    WHERE category_id IN ($placeholders) AND status = 'Active' AND deleted_at IS NULL
                    ORDER BY name ASC";
            return $this->query($sql, $categoryIds);
        }

        $sql = "SELECT * FROM catalog_items
                WHERE category_id = ? AND status = 'Active' AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$categoryId]);
    }

    /**
     * Get all descendant category IDs
     */
    public function getAllDescendantIds($categoryId) {
        $descendants = [];
        $children = $this->getChildCategories($categoryId);

        foreach ($children as $child) {
            $descendants[] = $child['id'];
            $descendants = array_merge($descendants, $this->getAllDescendantIds($child['id']));
        }

        return $descendants;
    }

    /**
     * Get item count
     */
    public function getItemCount($categoryId, $includeSubcategories = false) {
        if ($includeSubcategories) {
            $categoryIds = $this->getAllDescendantIds($categoryId);
            $categoryIds[] = $categoryId;
            $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

            $sql = "SELECT COUNT(*) as count FROM catalog_items
                    WHERE category_id IN ($placeholders) AND status = 'Active' AND deleted_at IS NULL";
            $result = $this->queryOne($sql, $categoryIds);
        } else {
            $sql = "SELECT COUNT(*) as count FROM catalog_items
                    WHERE category_id = ? AND status = 'Active' AND deleted_at IS NULL";
            $result = $this->queryOne($sql, [$categoryId]);
        }

        return $result['count'] ?? 0;
    }

    /**
     * Get most popular categories
     */
    public function getMostPopular($limit = 10) {
        $sql = "SELECT cc.*, COUNT(ci.id) as item_count
                FROM catalog_categories cc
                LEFT JOIN catalog_items ci ON cc.id = ci.category_id AND ci.status = 'Active' AND ci.deleted_at IS NULL
                WHERE cc.is_active = 1 AND cc.deleted_at IS NULL
                GROUP BY cc.id
                ORDER BY item_count DESC, cc.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Search categories
     */
    public function searchCategories($term, $limit = 50) {
        $sql = "SELECT * FROM catalog_categories
                WHERE (name LIKE ? OR description LIKE ?)
                AND is_active = 1 AND deleted_at IS NULL
                ORDER BY name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", $limit]);
    }

    /**
     * Get breadcrumb trail
     */
    public function getBreadcrumb($categoryId) {
        $breadcrumb = [];
        $currentId = $categoryId;

        while ($currentId) {
            $category = $this->find($currentId);
            if (!$category) break;

            array_unshift($breadcrumb, [
                'id' => $category['id'],
                'name' => $category['name']
            ]);
            $currentId = $category['parent_category_id'];
        }

        return $breadcrumb;
    }

    /**
     * Activate category
     */
    public function activate($categoryId) {
        return $this->update($categoryId, ['is_active' => 1]);
    }

    /**
     * Deactivate category
     */
    public function deactivate($categoryId) {
        return $this->update($categoryId, ['is_active' => 0]);
    }

    /**
     * Get inactive categories
     */
    public function getInactive() {
        $sql = "SELECT * FROM catalog_categories
                WHERE is_active = 0 AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql);
    }

    /**
     * Check if has children
     */
    public function hasChildren($categoryId) {
        $children = $this->getChildCategories($categoryId);
        return count($children) > 0;
    }

    /**
     * Get flat list with indentation
     */
    public function getFlatList($parentId = null, $prefix = '') {
        $categories = $parentId === null
            ? $this->getRootCategories()
            : $this->getChildCategories($parentId);

        $list = [];
        foreach ($categories as $category) {
            $category['display_name'] = $prefix . $category['name'];
            $list[] = $category;

            $children = $this->getFlatList($category['id'], $prefix . '  ');
            $list = array_merge($list, $children);
        }

        return $list;
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_categories,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_count,
                    COUNT(CASE WHEN parent_category_id IS NULL THEN 1 END) as root_categories,
                    COUNT(DISTINCT ci.id) as total_items
                FROM catalog_categories cc
                LEFT JOIN catalog_items ci ON cc.id = ci.category_id AND ci.deleted_at IS NULL
                WHERE cc.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200',
            'description' => 'max:1000',
            'parent_category_id' => 'integer',
            'is_active' => 'boolean',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        return $this->getCategoryPath($id);
    }
}
