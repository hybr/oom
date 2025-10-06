<?php

namespace Entities;

class PopularOrganizationDepartment extends BaseEntity
{
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'popular_organization_department';
    }

    protected function getFillableAttributes(): array
    {
        return ['name'];
    }

    protected function getValidationRules(): array
    {
        return ['name' => ['required', 'min:2', 'max:200']];
    }

    public static function searchByName(string $query): array
    {
        return static::search($query, ['name']);
    }
}
