<?php

namespace Entities;

/**
 * VacancyApplication Entity
 * Represents a job application submitted by a candidate for a vacancy
 */
class VacancyApplication extends BaseEntity
{
    protected ?int $vacancy_id = null;
    protected ?int $applicant_id = null;
    protected ?string $application_date = null;
    protected ?string $status = null; // pending, under_review, shortlisted, interviewed, rejected, withdrawn, offered, accepted
    protected ?string $resume_url = null;
    protected ?string $cover_letter = null;

    public static function getTableName(): string
    {
        return 'vacancy_application';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'vacancy_id',
            'applicant_id',
            'application_date',
            'status',
            'resume_url',
            'cover_letter'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'vacancy_id' => ['required', 'numeric'],
            'applicant_id' => ['required', 'numeric'],
            'application_date' => ['required'],
            'status' => ['required']
        ];
    }

    /**
     * Get the vacancy this application is for
     */
    public function getVacancy(): ?OrganizationVacancy
    {
        return OrganizationVacancy::find($this->vacancy_id);
    }

    /**
     * Get the applicant (person)
     */
    public function getApplicant(): ?Person
    {
        return Person::find($this->applicant_id);
    }

    /**
     * Get all reviews for this application
     */
    public function getReviews(): array
    {
        return ApplicationReview::where('application_id = :application_id', ['application_id' => $this->id]);
    }

    /**
     * Get all interviews for this application
     */
    public function getInterviews(): array
    {
        return ApplicationInterview::where('application_id = :application_id', ['application_id' => $this->id]);
    }

    /**
     * Get job offer for this application
     */
    public function getJobOffer(): ?JobOffer
    {
        $offers = JobOffer::where('application_id = :application_id', ['application_id' => $this->id], 1);
        return !empty($offers) ? $offers[0] : null;
    }

    /**
     * Get latest review
     */
    public function getLatestReview(): ?ApplicationReview
    {
        $reviews = $this->getReviews();
        return !empty($reviews) ? $reviews[0] : null;
    }

    /**
     * Get latest interview
     */
    public function getLatestInterview(): ?ApplicationInterview
    {
        $interviews = $this->getInterviews();
        return !empty($interviews) ? $interviews[0] : null;
    }

    /**
     * Shortlist the application
     */
    public function shortlist(?int $userId = null): bool
    {
        $this->status = 'shortlisted';
        return $this->save($userId);
    }

    /**
     * Reject the application
     */
    public function reject(?int $userId = null): bool
    {
        $this->status = 'rejected';
        return $this->save($userId);
    }

    /**
     * Withdraw the application
     */
    public function withdraw(?int $userId = null): bool
    {
        $this->status = 'withdrawn';
        return $this->save($userId);
    }

    /**
     * Mark application as under review
     */
    public function markUnderReview(?int $userId = null): bool
    {
        $this->status = 'under_review';
        return $this->save($userId);
    }

    /**
     * Mark application as interviewed
     */
    public function markInterviewed(?int $userId = null): bool
    {
        $this->status = 'interviewed';
        return $this->save($userId);
    }

    /**
     * Mark application as offered
     */
    public function markOffered(?int $userId = null): bool
    {
        $this->status = 'offered';
        return $this->save($userId);
    }

    /**
     * Mark application as accepted
     */
    public function markAccepted(?int $userId = null): bool
    {
        $this->status = 'accepted';
        return $this->save($userId);
    }

    /**
     * Check if application is active
     */
    public function isActive(): bool
    {
        return !in_array($this->status, ['rejected', 'withdrawn', 'accepted']);
    }

    /**
     * Get days since application
     */
    public function getDaysSinceApplication(): int
    {
        $applicationDate = new \DateTime($this->application_date);
        $now = new \DateTime();
        return $now->diff($applicationDate)->days;
    }

    /**
     * Get applications by vacancy
     */
    public static function getByVacancy(int $vacancyId): array
    {
        return static::where('vacancy_id = :vacancy_id', ['vacancy_id' => $vacancyId]);
    }

    /**
     * Get applications by applicant
     */
    public static function getByApplicant(int $applicantId): array
    {
        return static::where('applicant_id = :applicant_id', ['applicant_id' => $applicantId]);
    }

    /**
     * Check if applicant has already applied to vacancy
     */
    public static function hasApplied(int $vacancyId, int $applicantId): bool
    {
        $applications = static::where(
            'vacancy_id = :vacancy_id AND applicant_id = :applicant_id',
            ['vacancy_id' => $vacancyId, 'applicant_id' => $applicantId],
            1
        );
        return !empty($applications);
    }
}
