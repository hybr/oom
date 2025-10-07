<?php

namespace Entities;

/**
 * CatalogCategory Entity
 * Hierarchical catalog categories for organizing items
 */
class CatalogCategory extends BaseEntity
{
    protected ?string $name = null;
    protected ?string $description = null;
    protected ?int $parent_category_id = null;
    protected bool $is_active = true;
    protected bool $managed_by_system = true;

    public static function getTableName(): string
    {
        return 'catalog_category';
    }

    protected function getFillableAttributes(): array
    {
        return ['name', 'description', 'parent_category_id', 'is_active', 'managed_by_system'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:100'],
            'description' => ['max:500'],
        ];
    }

    /**
     * Get parent category
     */
    public function getParentCategory(): ?CatalogCategory
    {
        if ($this->parent_category_id) {
            return self::find($this->parent_category_id);
        }
        return null;
    }

    /**
     * Get child categories
     */
    public function getChildCategories(): array
    {
        return self::where('parent_category_id = :parent_id', ['parent_id' => $this->id]);
    }

    /**
     * Get all catalog items in this category
     */
    public function getCatalogItems(): array
    {
        return CatalogItem::where('category_id = :category_id', ['category_id' => $this->id]);
    }

    /**
     * Get full category path (e.g., "Electronics → Mobile → Accessories")
     */
    public function getFullCategoryPath(): string
    {
        $path = [$this->name];
        $parent = $this->getParentCategory();

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->getParentCategory();
        }

        return implode(' → ', $path);
    }

    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return self::count('parent_category_id = :parent_id', ['parent_id' => $this->id]) > 0;
    }

    /**
     * Get all root categories (no parent)
     */
    public static function getRootCategories(): array
    {
        return self::where('parent_category_id IS NULL');
    }

    /**
     * Get active categories only
     */
    public static function getActiveCategories(): array
    {
        return self::where('is_active = 1');
    }

    /**
     * Count items in this category (including subcategories)
     */
    public function countItemsRecursive(): int
    {
        $count = CatalogItem::count('category_id = :category_id', ['category_id' => $this->id]);

        foreach ($this->getChildCategories() as $child) {
            $count += $child->countItemsRecursive();
        }

        return $count;
    }

    /**
     * Deactivate category and all subcategories
     */
    public function deactivateRecursive(?int $userId = null): bool
    {
        $this->is_active = false;
        $this->save($userId);

        foreach ($this->getChildCategories() as $child) {
            $child->deactivateRecursive($userId);
        }

        return true;
    }

    /**
     * Search categories by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name', 'description']);
    }
}
