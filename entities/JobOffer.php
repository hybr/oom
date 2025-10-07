<?php

namespace Entities;

/**
 * JobOffer Entity
 * Represents a job offer extended to a successful applicant
 */
class JobOffer extends BaseEntity
{
    protected ?int $application_id = null;
    protected ?int $offered_by = null;
    protected ?string $offer_date = null;
    protected ?string $position_title = null;
    protected ?float $salary_offered = null;
    protected ?string $joining_date = null;
    protected ?string $status = null; // pending, accepted, declined, expired, withdrawn

    public static function getTableName(): string
    {
        return 'job_offer';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'application_id',
            'offered_by',
            'offer_date',
            'position_title',
            'salary_offered',
            'joining_date',
            'status'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'application_id' => ['required', 'numeric'],
            'offered_by' => ['required', 'numeric'],
            'offer_date' => ['required'],
            'position_title' => ['required', 'min:2', 'max:200'],
            'status' => ['required']
        ];
    }

    /**
     * Get the application this offer is for
     */
    public function getApplication(): ?VacancyApplication
    {
        return VacancyApplication::find($this->application_id);
    }

    /**
     * Get the person who made the offer
     */
    public function getOfferedBy(): ?Person
    {
        return Person::find($this->offered_by);
    }

    /**
     * Get the applicant through the application
     */
    public function getApplicant(): ?Person
    {
        $application = $this->getApplication();
        return $application ? $application->getApplicant() : null;
    }

    /**
     * Get the vacancy through the application
     */
    public function getVacancy(): ?OrganizationVacancy
    {
        $application = $this->getApplication();
        return $application ? $application->getVacancy() : null;
    }

    /**
     * Get the employment contract if one exists
     */
    public function getEmploymentContract(): ?EmploymentContract
    {
        $contracts = EmploymentContract::where('job_offer_id = :offer_id', ['offer_id' => $this->id], 1);
        return !empty($contracts) ? $contracts[0] : null;
    }

    /**
     * Accept the job offer
     */
    public function accept(?int $userId = null): bool
    {
        $this->status = 'accepted';
        $saved = $this->save($userId);

        if ($saved) {
            $application = $this->getApplication();
            if ($application) {
                $application->markAccepted($userId);
            }
        }

        return $saved;
    }

    /**
     * Decline the job offer
     */
    public function decline(?int $userId = null): bool
    {
        $this->status = 'declined';
        return $this->save($userId);
    }

    /**
     * Expire the job offer
     */
    public function expire(?int $userId = null): bool
    {
        $this->status = 'expired';
        return $this->save($userId);
    }

    /**
     * Withdraw the job offer
     */
    public function withdraw(?int $userId = null): bool
    {
        $this->status = 'withdrawn';
        return $this->save($userId);
    }

    /**
     * Check if offer is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if offer was accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if offer was declined
     */
    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    /**
     * Get days since offer was made
     */
    public function getDaysSinceOffer(): int
    {
        $offerDate = new \DateTime($this->offer_date);
        $now = new \DateTime();
        return $now->diff($offerDate)->days;
    }

    /**
     * Get days until joining date
     */
    public function getDaysUntilJoining(): int
    {
        if (!$this->joining_date) {
            return 0;
        }

        $joiningDate = new \DateTime($this->joining_date);
        $now = new \DateTime();
        return $now->diff($joiningDate)->days;
    }

    /**
     * Get offer summary
     */
    public function getSummary(): array
    {
        return [
            'position_title' => $this->position_title,
            'salary_offered' => $this->salary_offered,
            'joining_date' => $this->joining_date,
            'offer_date' => $this->offer_date,
            'status' => $this->status,
            'offered_by' => $this->getOfferedBy()?->toArray(),
            'applicant' => $this->getApplicant()?->toArray()
        ];
    }

    /**
     * Get offers by application
     */
    public static function getByApplication(int $applicationId): array
    {
        return static::where('application_id = :application_id', ['application_id' => $applicationId]);
    }

    /**
     * Get offers by status
     */
    public static function getByStatus(string $status): array
    {
        return static::where('status = :status', ['status' => $status]);
    }

    /**
     * Get pending offers
     */
    public static function getPendingOffers(): array
    {
        return static::getByStatus('pending');
    }

    /**
     * Get accepted offers
     */
    public static function getAcceptedOffers(): array
    {
        return static::getByStatus('accepted');
    }

    /**
     * Check if application has an offer
     */
    public static function hasOffer(int $applicationId): bool
    {
        $offers = static::getByApplication($applicationId);
        return !empty($offers);
    }
}
