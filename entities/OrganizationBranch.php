<?php

namespace Entities;

class OrganizationBranch extends BaseEntity
{
    protected ?int $organization_id = null;
    protected ?string $name = null;
    protected ?string $code = null;

    public static function getTableName(): string
    {
        return 'organization_branch';
    }

    protected function getFillableAttributes(): array
    {
        return ['organization_id', 'name', 'code'];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_id' => ['required', 'numeric'],
            'name' => ['required', 'min:2', 'max:200'],
        ];
    }

    public function getOrganization(): ?Organization
    {
        return Organization::find($this->organization_id);
    }

    public function getBuildings(): array
    {
        return OrganizationBuilding::where('organization_branch_id = :branch_id', ['branch_id' => $this->id]);
    }
}
