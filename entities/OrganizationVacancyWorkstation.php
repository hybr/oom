<?php

namespace Entities;

/**
 * OrganizationVacancyWorkstation Entity
 * Links vacancies to specific workstations where the position will be located
 */
class OrganizationVacancyWorkstation extends BaseEntity
{
    protected ?int $organization_vacancy_id = null;
    protected ?int $organization_workstation_id = null;

    public static function getTableName(): string
    {
        return 'organization_vacancy_workstation';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'organization_vacancy_id',
            'organization_workstation_id'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_vacancy_id' => ['required', 'numeric'],
            'organization_workstation_id' => ['required', 'numeric']
        ];
    }

    /**
     * Get the vacancy this workstation is associated with
     */
    public function getVacancy(): ?OrganizationVacancy
    {
        return OrganizationVacancy::find($this->organization_vacancy_id);
    }

    /**
     * Get the workstation details
     */
    public function getWorkstation(): ?Workstation
    {
        return Workstation::find($this->organization_workstation_id);
    }

    /**
     * Get the building where this workstation is located
     */
    public function getBuilding(): ?OrganizationBuilding
    {
        $workstation = $this->getWorkstation();
        return $workstation ? $workstation->getBuilding() : null;
    }

    /**
     * Get the branch where this workstation is located
     */
    public function getBranch(): ?OrganizationBranch
    {
        $building = $this->getBuilding();
        return $building ? $building->getBranch() : null;
    }

    /**
     * Check if workstation is available
     */
    public function isWorkstationAvailable(): bool
    {
        $workstation = $this->getWorkstation();
        return $workstation ? $workstation->is_available : false;
    }

    /**
     * Get full location details
     */
    public function getLocationDetails(): array
    {
        $workstation = $this->getWorkstation();
        $building = $this->getBuilding();
        $branch = $this->getBranch();

        return [
            'workstation' => $workstation ? $workstation->toArray() : null,
            'building' => $building ? $building->toArray() : null,
            'branch' => $branch ? $branch->toArray() : null
        ];
    }

    /**
     * Get all workstations for a vacancy
     */
    public static function getByVacancy(int $vacancyId): array
    {
        return static::where('organization_vacancy_id = :vacancy_id', ['vacancy_id' => $vacancyId]);
    }

    /**
     * Get all vacancies for a workstation
     */
    public static function getByWorkstation(int $workstationId): array
    {
        return static::where('organization_workstation_id = :workstation_id', ['workstation_id' => $workstationId]);
    }

    /**
     * Check if a vacancy-workstation association exists
     */
    public static function exists(int $vacancyId, int $workstationId): bool
    {
        $results = static::where(
            'organization_vacancy_id = :vacancy_id AND organization_workstation_id = :workstation_id',
            ['vacancy_id' => $vacancyId, 'workstation_id' => $workstationId],
            1
        );
        return !empty($results);
    }

    /**
     * Create new vacancy-workstation association
     */
    public static function createAssociation(int $vacancyId, int $workstationId, ?int $userId = null): ?self
    {
        if (static::exists($vacancyId, $workstationId)) {
            return null; // Already exists
        }

        $association = new static();
        $association->organization_vacancy_id = $vacancyId;
        $association->organization_workstation_id = $workstationId;

        return $association->save($userId) ? $association : null;
    }

    /**
     * Remove association
     */
    public static function removeAssociation(int $vacancyId, int $workstationId): bool
    {
        $associations = static::where(
            'organization_vacancy_id = :vacancy_id AND organization_workstation_id = :workstation_id',
            ['vacancy_id' => $vacancyId, 'workstation_id' => $workstationId],
            1
        );

        if (!empty($associations)) {
            return $associations[0]->delete();
        }

        return false;
    }

    /**
     * Get count of workstations for a vacancy
     */
    public static function countByVacancy(int $vacancyId): int
    {
        return static::count('organization_vacancy_id = :vacancy_id', ['vacancy_id' => $vacancyId]);
    }

    /**
     * Get count of vacancies for a workstation
     */
    public static function countByWorkstation(int $workstationId): int
    {
        return static::count('organization_workstation_id = :workstation_id', ['workstation_id' => $workstationId]);
    }
}
