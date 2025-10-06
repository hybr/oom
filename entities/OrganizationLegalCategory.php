<?php

namespace Entities;

class OrganizationLegalCategory extends BaseEntity
{
    protected ?string $name = null;
    protected ?int $parent_category_id = null;

    public static function getTableName(): string
    {
        return 'organization_legal_category';
    }

    protected function getFillableAttributes(): array
    {
        return ['name', 'parent_category_id'];
    }

    protected function getValidationRules(): array
    {
        return ['name' => ['required', 'min:2', 'max:200']];
    }

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

    public function getParent(): ?self
    {
        if (!$this->parent_category_id) return null;
        return self::find($this->parent_category_id);
    }

    public function getChildren(): array
    {
        return self::where('parent_category_id = :parent_id', ['parent_id' => $this->id]);
    }
}
