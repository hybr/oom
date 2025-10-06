<?php

namespace Entities;

class Workstation extends BaseEntity
{
    protected ?int $organization_building_id = null;
    protected ?string $floor = null;
    protected ?string $room = null;
    protected ?string $workstation_number = null;

    public static function getTableName(): string
    {
        return 'workstation';
    }

    protected function getFillableAttributes(): array
    {
        return ['organization_building_id', 'floor', 'room', 'workstation_number'];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_building_id' => ['required', 'numeric'],
        ];
    }

    public function getBuilding(): ?OrganizationBuilding
    {
        return OrganizationBuilding::find($this->organization_building_id);
    }

    public function getFullLocation(): string
    {
        $parts = array_filter([
            $this->floor ? "Floor {$this->floor}" : null,
            $this->room ? "Room {$this->room}" : null,
            $this->workstation_number ? "Workstation {$this->workstation_number}" : null,
        ]);

        return implode(', ', $parts);
    }
}
