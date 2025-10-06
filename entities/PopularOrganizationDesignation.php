<?php

namespace Entities;

class PopularOrganizationDesignation extends BaseEntity
{
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'popular_organization_designation';
    }

    protected function getFillableAttributes(): array
    {
        return ['name'];
    }

    protected function getValidationRules(): array
    {
        return ['name' => ['required', 'min:2', 'max:200']];
    }
}
