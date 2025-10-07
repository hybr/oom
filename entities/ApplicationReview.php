<?php

namespace Entities;

/**
 * ApplicationReview Entity
 * Represents a review of a job application by hiring personnel
 */
class ApplicationReview extends BaseEntity
{
    protected ?int $application_id = null;
    protected ?int $reviewed_by = null;
    protected ?string $review_date = null;
    protected ?string $review_notes = null;
    protected ?string $status = null; // approved, rejected, needs_more_info

    public static function getTableName(): string
    {
        return 'application_review';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'application_id',
            'reviewed_by',
            'review_date',
            'review_notes',
            'status'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'application_id' => ['required', 'numeric'],
            'reviewed_by' => ['required', 'numeric'],
            'review_date' => ['required'],
            'status' => ['required']
        ];
    }

    /**
     * Get the application being reviewed
     */
    public function getApplication(): ?VacancyApplication
    {
        return VacancyApplication::find($this->application_id);
    }

    /**
     * Get the reviewer (person)
     */
    public function getReviewer(): ?Person
    {
        return Person::find($this->reviewed_by);
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
     * Approve the application
     */
    public function approve(?int $userId = null): bool
    {
        $this->status = 'approved';
        $saved = $this->save($userId);

        if ($saved) {
            $application = $this->getApplication();
            if ($application) {
                $application->markUnderReview($userId);
            }
        }

        return $saved;
    }

    /**
     * Reject the application
     */
    public function reject(?int $userId = null): bool
    {
        $this->status = 'rejected';
        $saved = $this->save($userId);

        if ($saved) {
            $application = $this->getApplication();
            if ($application) {
                $application->reject($userId);
            }
        }

        return $saved;
    }

    /**
     * Request more information
     */
    public function requestMoreInfo(?int $userId = null): bool
    {
        $this->status = 'needs_more_info';
        return $this->save($userId);
    }

    /**
     * Check if review is positive
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if review is negative
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get days since review
     */
    public function getDaysSinceReview(): int
    {
        $reviewDate = new \DateTime($this->review_date);
        $now = new \DateTime();
        return $now->diff($reviewDate)->days;
    }

    /**
     * Get review summary
     */
    public function getSummary(): array
    {
        return [
            'reviewer' => $this->getReviewer()?->toArray(),
            'review_date' => $this->review_date,
            'status' => $this->status,
            'notes' => $this->review_notes
        ];
    }

    /**
     * Get reviews by application
     */
    public static function getByApplication(int $applicationId): array
    {
        return static::where('application_id = :application_id', ['application_id' => $applicationId]);
    }

    /**
     * Get reviews by reviewer
     */
    public static function getByReviewer(int $reviewerId): array
    {
        return static::where('reviewed_by = :reviewer_id', ['reviewer_id' => $reviewerId]);
    }

    /**
     * Get reviews by status
     */
    public static function getByStatus(string $status): array
    {
        return static::where('status = :status', ['status' => $status]);
    }

    /**
     * Count reviews for an application
     */
    public static function countByApplication(int $applicationId): int
    {
        return static::count('application_id = :application_id', ['application_id' => $applicationId]);
    }

    /**
     * Check if application has been reviewed
     */
    public static function hasBeenReviewed(int $applicationId): bool
    {
        return static::countByApplication($applicationId) > 0;
    }

    /**
     * Get latest review for an application
     */
    public static function getLatestForApplication(int $applicationId): ?self
    {
        $reviews = static::where('application_id = :application_id', ['application_id' => $applicationId], 1);
        return !empty($reviews) ? $reviews[0] : null;
    }
}
