<?php

namespace Entities;

/**
 * PopularOrganizationPosition Entity
 * Standardized global position templates usable across organizations
 */
class PopularOrganizationPosition extends BaseEntity
{
    protected ?string $name = null;
    protected ?int $department_id = null;
    protected ?int $team_id = null;
    protected ?int $designation_id = null;
    protected ?string $minimum_education_level = null;
    protected ?int $minimum_subject_id = null;
    protected ?string $description = null;

    public static function getTableName(): string
    {
        return 'popular_organization_position';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'name',
            'department_id',
            'team_id',
            'designation_id',
            'minimum_education_level',
            'minimum_subject_id',
            'description'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:200'],
        ];
    }

    /**
     * Get the department this position belongs to
     */
    public function getDepartment(): ?PopularOrganizationDepartment
    {
        return PopularOrganizationDepartment::find($this->department_id);
    }

    /**
     * Get the team this position belongs to
     */
    public function getTeam(): ?PopularOrganizationTeam
    {
        return PopularOrganizationTeam::find($this->team_id);
    }

    /**
     * Get the designation for this position
     */
    public function getDesignation(): ?PopularOrganizationDesignation
    {
        return PopularOrganizationDesignation::find($this->designation_id);
    }

    /**
     * Get the minimum education subject required
     */
    public function getMinimumSubject(): ?PopularEducationSubject
    {
        return PopularEducationSubject::find($this->minimum_subject_id);
    }

    /**
     * Get full position name with department and designation
     */
    public function getFullName(): string
    {
        $department = $this->getDepartment();
        $designation = $this->getDesignation();

        $parts = [$this->name];

        if ($designation) {
            $parts[] = "({$designation->name})";
        }

        if ($department) {
            $parts[] = "- {$department->name}";
        }

        return implode(' ', $parts);
    }

    /**
     * Search positions by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name', 'description']);
    }

    /**
     * Get positions by department
     */
    public static function getByDepartment(int $departmentId): array
    {
        return static::where('department_id = :dept_id', ['dept_id' => $departmentId]);
    }

    /**
     * Get positions by team
     */
    public static function getByTeam(int $teamId): array
    {
        return static::where('team_id = :team_id', ['team_id' => $teamId]);
    }

    /**
     * Get positions by designation
     */
    public static function getByDesignation(int $designationId): array
    {
        return static::where('designation_id = :designation_id', ['designation_id' => $designationId]);
    }

    /**
     * Get positions by minimum education level
     */
    public static function getByEducationLevel(string $level): array
    {
        return static::where('minimum_education_level = :level', ['level' => $level]);
    }

    /**
     * Check if a person meets the minimum qualifications
     */
    public function meetsQualifications(Person $person): bool
    {
        // If no education requirements, anyone qualifies
        if (!$this->minimum_education_level && !$this->minimum_subject_id) {
            return true;
        }

        $educations = $person->getEducation();

        // Check if person has the required education level
        if ($this->minimum_education_level) {
            $hasLevel = false;
            foreach ($educations as $edu) {
                if ($edu->education_level === $this->minimum_education_level) {
                    $hasLevel = true;
                    break;
                }
            }
            if (!$hasLevel) {
                return false;
            }
        }

        // Check if person has studied the required subject
        if ($this->minimum_subject_id) {
            $hasSubject = false;
            foreach ($educations as $edu) {
                $subjects = $edu->getSubjects();
                foreach ($subjects as $subject) {
                    if ($subject->subject_id === $this->minimum_subject_id) {
                        $hasSubject = true;
                        break 2;
                    }
                }
            }
            if (!$hasSubject) {
                return false;
            }
        }

        return true;
    }
}
