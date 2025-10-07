<?php

namespace Entities;

/**
 * EmploymentContract Entity
 * Represents the formal employment contract between an organization and an employee
 */
class EmploymentContract extends BaseEntity
{
    protected ?int $job_offer_id = null;
    protected ?int $organization_id = null;
    protected ?int $employee_id = null;
    protected ?string $start_date = null;
    protected ?string $end_date = null;
    protected ?string $contract_terms = null;
    protected ?string $status = null; // draft, active, completed, terminated, expired

    public static function getTableName(): string
    {
        return 'employment_contract';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'job_offer_id',
            'organization_id',
            'employee_id',
            'start_date',
            'end_date',
            'contract_terms',
            'status'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'job_offer_id' => ['required', 'numeric'],
            'organization_id' => ['required', 'numeric'],
            'employee_id' => ['required', 'numeric'],
            'start_date' => ['required'],
            'status' => ['required']
        ];
    }

    /**
     * Get the job offer this contract is based on
     */
    public function getJobOffer(): ?JobOffer
    {
        return JobOffer::find($this->job_offer_id);
    }

    /**
     * Get the organization
     */
    public function getOrganization(): ?Organization
    {
        return Organization::find($this->organization_id);
    }

    /**
     * Get the employee
     */
    public function getEmployee(): ?Person
    {
        return Person::find($this->employee_id);
    }

    /**
     * Get the application through the job offer
     */
    public function getApplication(): ?VacancyApplication
    {
        $offer = $this->getJobOffer();
        return $offer ? $offer->getApplication() : null;
    }

    /**
     * Activate the contract
     */
    public function activate(?int $userId = null): bool
    {
        $this->status = 'active';
        return $this->save($userId);
    }

    /**
     * Complete the contract (natural end)
     */
    public function complete(?int $userId = null): bool
    {
        $this->status = 'completed';
        return $this->save($userId);
    }

    /**
     * Terminate the contract (early termination)
     */
    public function terminate(?int $userId = null): bool
    {
        $this->status = 'terminated';
        return $this->save($userId);
    }

    /**
     * Expire the contract
     */
    public function expire(?int $userId = null): bool
    {
        $this->status = 'expired';
        return $this->save($userId);
    }

    /**
     * Check if contract is active
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = new \DateTime();
        $start = new \DateTime($this->start_date);

        if ($now < $start) {
            return false;
        }

        if ($this->end_date) {
            $end = new \DateTime($this->end_date);
            if ($now > $end) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if contract is permanent (no end date)
     */
    public function isPermanent(): bool
    {
        return $this->end_date === null;
    }

    /**
     * Check if contract has expired
     */
    public function hasExpired(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        $now = new \DateTime();
        $end = new \DateTime($this->end_date);

        return $now > $end;
    }

    /**
     * Get contract duration in days
     */
    public function getDurationInDays(): ?int
    {
        if (!$this->end_date) {
            return null; // Permanent contract
        }

        $start = new \DateTime($this->start_date);
        $end = new \DateTime($this->end_date);

        return $start->diff($end)->days;
    }

    /**
     * Get days remaining in contract
     */
    public function getDaysRemaining(): ?int
    {
        if (!$this->end_date) {
            return null; // Permanent contract
        }

        $now = new \DateTime();
        $end = new \DateTime($this->end_date);

        if ($now > $end) {
            return 0;
        }

        return $now->diff($end)->days;
    }

    /**
     * Get days since contract start
     */
    public function getDaysSinceStart(): int
    {
        $start = new \DateTime($this->start_date);
        $now = new \DateTime();
        return $start->diff($now)->days;
    }

    /**
     * Get contract summary
     */
    public function getSummary(): array
    {
        return [
            'employee' => $this->getEmployee()?->toArray(),
            'organization' => $this->getOrganization()?->toArray(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'is_active' => $this->isActive(),
            'is_permanent' => $this->isPermanent(),
            'days_remaining' => $this->getDaysRemaining()
        ];
    }

    /**
     * Get contracts by organization
     */
    public static function getByOrganization(int $organizationId): array
    {
        return static::where('organization_id = :org_id', ['org_id' => $organizationId]);
    }

    /**
     * Get contracts by employee
     */
    public static function getByEmployee(int $employeeId): array
    {
        return static::where('employee_id = :employee_id', ['employee_id' => $employeeId]);
    }

    /**
     * Get active contracts for an organization
     */
    public static function getActiveByOrganization(int $organizationId): array
    {
        return static::where(
            'organization_id = :org_id AND status = :status',
            ['org_id' => $organizationId, 'status' => 'active']
        );
    }

    /**
     * Get active contract for an employee
     */
    public static function getActiveForEmployee(int $employeeId): ?self
    {
        $contracts = static::where(
            'employee_id = :employee_id AND status = :status',
            ['employee_id' => $employeeId, 'status' => 'active'],
            1
        );
        return !empty($contracts) ? $contracts[0] : null;
    }
}
