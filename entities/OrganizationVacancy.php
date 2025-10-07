<?php

namespace Entities;

/**
 * OrganizationVacancy Entity
 * Represents job openings within an organization
 */
class OrganizationVacancy extends BaseEntity
{
    protected ?int $organization_id = null;
    protected ?int $popular_position_id = null;
    protected ?string $opening_date = null;
    protected ?string $closing_date = null;
    protected ?string $status = null; // open, closed, filled, cancelled
    protected ?int $created_by = null;

    public static function getTableName(): string
    {
        return 'organization_vacancy';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'organization_id',
            'popular_position_id',
            'opening_date',
            'closing_date',
            'status',
            'created_by'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_id' => ['required', 'numeric'],
            'popular_position_id' => ['required', 'numeric'],
            'opening_date' => ['required'],
            'status' => ['required']
        ];
    }

    /**
     * Get the organization this vacancy belongs to
     */
    public function getOrganization(): ?Organization
    {
        return Organization::find($this->organization_id);
    }

    /**
     * Get the position for this vacancy
     */
    public function getPosition(): ?PopularOrganizationPosition
    {
        return PopularOrganizationPosition::find($this->popular_position_id);
    }

    /**
     * Get the user who created this vacancy
     */
    public function getCreator(): ?Person
    {
        return Person::find($this->created_by);
    }

    /**
     * Get all applications for this vacancy
     */
    public function getApplications(): array
    {
        return VacancyApplication::where('vacancy_id = :vacancy_id', ['vacancy_id' => $this->id]);
    }

    /**
     * Get applications by status
     */
    public function getApplicationsByStatus(string $status): array
    {
        return VacancyApplication::where(
            'vacancy_id = :vacancy_id AND status = :status',
            ['vacancy_id' => $this->id, 'status' => $status]
        );
    }

    /**
     * Get workstations associated with this vacancy
     */
    public function getWorkstations(): array
    {
        return OrganizationVacancyWorkstation::where('organization_vacancy_id = :vacancy_id', ['vacancy_id' => $this->id]);
    }

    /**
     * Check if vacancy is currently open
     */
    public function isOpen(): bool
    {
        if ($this->status !== 'open') {
            return false;
        }

        $now = new \DateTime();
        $opening = new \DateTime($this->opening_date);
        $closing = $this->closing_date ? new \DateTime($this->closing_date) : null;

        if ($now < $opening) {
            return false;
        }

        if ($closing && $now > $closing) {
            return false;
        }

        return true;
    }

    /**
     * Check if vacancy has expired
     */
    public function hasExpired(): bool
    {
        if (!$this->closing_date) {
            return false;
        }

        $now = new \DateTime();
        $closing = new \DateTime($this->closing_date);

        return $now > $closing;
    }

    /**
     * Close the vacancy
     */
    public function close(?int $userId = null): bool
    {
        $this->status = 'closed';
        return $this->save($userId);
    }

    /**
     * Reopen the vacancy
     */
    public function reopen(?int $userId = null): bool
    {
        $this->status = 'open';
        return $this->save($userId);
    }

    /**
     * Cancel the vacancy
     */
    public function cancel(?int $userId = null): bool
    {
        $this->status = 'cancelled';
        return $this->save($userId);
    }

    /**
     * Mark vacancy as filled
     */
    public function markAsFilled(?int $userId = null): bool
    {
        $this->status = 'filled';
        return $this->save($userId);
    }

    /**
     * Get count of applications
     */
    public function getApplicationCount(): int
    {
        return VacancyApplication::count('vacancy_id = :vacancy_id', ['vacancy_id' => $this->id]);
    }

    /**
     * Get count of shortlisted applications
     */
    public function getShortlistedCount(): int
    {
        return VacancyApplication::count(
            'vacancy_id = :vacancy_id AND status = :status',
            ['vacancy_id' => $this->id, 'status' => 'shortlisted']
        );
    }

    /**
     * Get active vacancies for an organization
     */
    public static function getActiveByOrganization(int $organizationId): array
    {
        return static::where(
            'organization_id = :org_id AND status = :status',
            ['org_id' => $organizationId, 'status' => 'open']
        );
    }

    /**
     * Get vacancies by position
     */
    public static function getByPosition(int $positionId): array
    {
        return static::where('popular_position_id = :position_id', ['position_id' => $positionId]);
    }
}
