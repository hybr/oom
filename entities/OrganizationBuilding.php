<?php

namespace Entities;

class OrganizationBuilding extends BaseEntity
{
    protected ?int $organization_branch_id = null;
    protected ?int $postal_address_id = null;
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'organization_building';
    }

    protected function getFillableAttributes(): array
    {
        return ['organization_branch_id', 'postal_address_id', 'name'];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_branch_id' => ['required', 'numeric'],
            'name' => ['required', 'min:2', 'max:200'],
        ];
    }

    public function getBranch(): ?OrganizationBranch
    {
        return OrganizationBranch::find($this->organization_branch_id);
    }

    public function getAddress(): ?PostalAddress
    {
        return PostalAddress::find($this->postal_address_id);
    }

    public function getWorkstations(): array
    {
        return Workstation::where('organization_building_id = :building_id', ['building_id' => $this->id]);
    }
}
