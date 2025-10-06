<?php

namespace Entities;

class PopularOrganizationTeam extends BaseEntity
{
    protected ?string $name = null;
    protected ?int $department_id = null;

    public static function getTableName(): string
    {
        return 'popular_organization_team';
    }

    protected function getFillableAttributes(): array
    {
        return ['name', 'department_id'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:200'],
        ];
    }

    public function getDepartment(): ?PopularOrganizationDepartment
    {
        return PopularOrganizationDepartment::find($this->department_id);
    }
}
