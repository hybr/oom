<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * IndustryCategory Entity
 * Hierarchical categorization of industries
 */
class IndustryCategory extends BaseEntity {
    protected $table = 'industry_categories';
    protected $fillable = ['name', 'parent_category_id'];

    /**
     * Get parent category
     */
    public function getParentCategory($categoryId) {
        $sql = "SELECT * FROM industry_categories
                WHERE id = (SELECT parent_category_id FROM industry_categories WHERE id = ?)
                AND deleted_at IS NULL";
        return $this->queryOne($sql, [$categoryId]);
    }

    /**
     * Get child categories
     */
    public function getChildCategories($categoryId) {
        return $this->all(['parent_category_id' => $categoryId], 'name ASC');
    }

    /**
     * Get root categories (no parent)
     */
    public function getRootCategories() {
        $sql = "SELECT * FROM industry_categories
                WHERE parent_category_id IS NULL AND deleted_at IS NULL
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
     * Get organizations in category
     */
    public function getOrganizations($categoryId, $includeSubcategories = false) {
        if ($includeSubcategories) {
            $categoryIds = $this->getAllDescendantIds($categoryId);
            $categoryIds[] = $categoryId;
            $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

            $sql = "SELECT * FROM organizations
                    WHERE industry_id IN ($placeholders) AND deleted_at IS NULL
                    ORDER BY short_name ASC";
            return $this->query($sql, $categoryIds);
        }

        $sql = "SELECT * FROM organizations
                WHERE industry_id = ? AND deleted_at IS NULL
                ORDER BY short_name ASC";
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
     * Get organization count per category
     */
    public function getOrganizationCount($categoryId, $includeSubcategories = false) {
        if ($includeSubcategories) {
            $categoryIds = $this->getAllDescendantIds($categoryId);
            $categoryIds[] = $categoryId;
            $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

            $sql = "SELECT COUNT(*) as count FROM organizations
                    WHERE industry_id IN ($placeholders) AND deleted_at IS NULL";
            $result = $this->queryOne($sql, $categoryIds);
        } else {
            $sql = "SELECT COUNT(*) as count FROM organizations
                    WHERE industry_id = ? AND deleted_at IS NULL";
            $result = $this->queryOne($sql, [$categoryId]);
        }

        return $result['count'] ?? 0;
    }

    /**
     * Get most popular categories
     */
    public function getMostPopular($limit = 10) {
        $sql = "SELECT ic.*, COUNT(o.id) as organization_count
                FROM industry_categories ic
                LEFT JOIN organizations o ON ic.id = o.industry_id AND o.deleted_at IS NULL
                WHERE ic.deleted_at IS NULL
                GROUP BY ic.id
                ORDER BY organization_count DESC, ic.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Search categories by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Check if category has children
     */
    public function hasChildren($categoryId) {
        $children = $this->getChildCategories($categoryId);
        return count($children) > 0;
    }

    /**
     * Check if can be deleted (no children, no organizations)
     */
    public function canDelete($categoryId) {
        if ($this->hasChildren($categoryId)) {
            return ['can_delete' => false, 'reason' => 'Category has child categories'];
        }

        if ($this->getOrganizationCount($categoryId) > 0) {
            return ['can_delete' => false, 'reason' => 'Category has organizations'];
        }

        return ['can_delete' => true];
    }

    /**
     * Get siblings (categories with same parent)
     */
    public function getSiblings($categoryId) {
        $category = $this->find($categoryId);
        if (!$category) return [];

        $sql = "SELECT * FROM industry_categories
                WHERE parent_category_id " . ($category['parent_category_id'] ? "= ?" : "IS NULL") . "
                AND id != ? AND deleted_at IS NULL
                ORDER BY name ASC";

        $params = $category['parent_category_id']
            ? [$category['parent_category_id'], $categoryId]
            : [$categoryId];

        return $this->query($sql, $params);
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
     * Get statistics
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_categories,
                    COUNT(CASE WHEN parent_category_id IS NULL THEN 1 END) as root_categories,
                    COUNT(DISTINCT o.id) as total_organizations
                FROM industry_categories ic
                LEFT JOIN organizations o ON ic.id = o.industry_id AND o.deleted_at IS NULL
                WHERE ic.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:200',
            'parent_category_id' => 'integer',
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
