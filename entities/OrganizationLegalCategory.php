<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * OrganizationLegalCategory Entity
 * Legal structure categories (LLC, Corp, Partnership, etc.)
 */
class OrganizationLegalCategory extends BaseEntity {
    protected $table = 'organization_legal_categories';
    protected $fillable = ['name', 'parent_category_id'];

    /**
     * Get full category name with parent hierarchy
     */
    public function getFullCategoryName($categoryId) {
        $path = [];
        $currentId = $categoryId;

        while ($currentId) {
            $category = $this->find($currentId);
            if (!$category) break;

            array_unshift($path, $category['name']);
            $currentId = $category['parent_category_id'];
        }

        return implode(' - ', $path);
    }

    /**
     * Get parent category
     */
    public function getParentCategory($categoryId) {
        $sql = "SELECT * FROM organization_legal_categories
                WHERE id = (SELECT parent_category_id FROM organization_legal_categories WHERE id = ?)
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
     * Get root categories
     */
    public function getRootCategories() {
        $sql = "SELECT * FROM organization_legal_categories
                WHERE parent_category_id IS NULL AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql);
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
    public function getOrganizations($categoryId) {
        $sql = "SELECT * FROM organizations
                WHERE legal_category_id = ? AND deleted_at IS NULL
                ORDER BY short_name ASC";
        return $this->query($sql, [$categoryId]);
    }

    /**
     * Get organization count
     */
    public function getOrganizationCount($categoryId) {
        $sql = "SELECT COUNT(*) as count FROM organizations
                WHERE legal_category_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$categoryId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get most popular legal categories
     */
    public function getMostPopular($limit = 10) {
        $sql = "SELECT olc.*, COUNT(o.id) as organization_count
                FROM organization_legal_categories olc
                LEFT JOIN organizations o ON olc.id = o.legal_category_id AND o.deleted_at IS NULL
                WHERE olc.deleted_at IS NULL
                GROUP BY olc.id
                ORDER BY organization_count DESC, olc.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Search by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Check if has children
     */
    public function hasChildren($categoryId) {
        $children = $this->getChildCategories($categoryId);
        return count($children) > 0;
    }

    /**
     * Check if can be deleted
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
     * Get all descendant IDs
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
     * Get siblings
     */
    public function getSiblings($categoryId) {
        $category = $this->find($categoryId);
        if (!$category) return [];

        $sql = "SELECT * FROM organization_legal_categories
                WHERE parent_category_id " . ($category['parent_category_id'] ? "= ?" : "IS NULL") . "
                AND id != ? AND deleted_at IS NULL
                ORDER BY name ASC";

        $params = $category['parent_category_id']
            ? [$category['parent_category_id'], $categoryId]
            : [$categoryId];

        return $this->query($sql, $params);
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
                    COUNT(CASE WHEN parent_category_id IS NULL THEN 1 END) as root_categories,
                    COUNT(DISTINCT o.id) as total_organizations
                FROM organization_legal_categories olc
                LEFT JOIN organizations o ON olc.id = o.legal_category_id AND o.deleted_at IS NULL
                WHERE olc.deleted_at IS NULL";
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
        return $this->getFullCategoryName($id);
    }
}
