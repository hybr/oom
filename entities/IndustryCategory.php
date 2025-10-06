<?php

namespace Entities;

/**
 * Industry Category Entity
 * Hierarchical categories for industries
 */
class IndustryCategory extends BaseEntity
{
    protected ?string $name = null;
    protected ?int $parent_category_id = null;

    public static function getTableName(): string
    {
        return 'industry_category';
    }

    protected function getFillableAttributes(): array
    {
        return ['name', 'parent_category_id'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:200'],
        ];
    }

    /**
     * Get parent category
     */
    public function getParent(): ?self
    {
        if (!$this->parent_category_id) {
            return null;
        }
        return self::find($this->parent_category_id);
    }

    /**
     * Get child categories
     */
    public function getChildren(): array
    {
        return self::where('parent_category_id = :parent_id', ['parent_id' => $this->id]);
    }

    /**
     * Get full category path
     */
    public function getFullCategoryName(): string
    {
        $names = [$this->name];
        $parent = $this->getParent();

        while ($parent) {
            array_unshift($names, $parent->name);
            $parent = $parent->getParent();
        }

        return implode(' > ', $names);
    }

    /**
     * Check if has children
     */
    public function hasChildren(): bool
    {
        return count($this->getChildren()) > 0;
    }

    /**
     * Get root categories (no parent)
     */
    public static function getRootCategories(): array
    {
        return self::where('parent_category_id IS NULL');
    }
}
