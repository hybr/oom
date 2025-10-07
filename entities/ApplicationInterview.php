<?php

namespace Entities;

/**
 * ApplicationInterview Entity
 * Represents an interview scheduled for a job application
 */
class ApplicationInterview extends BaseEntity
{
    protected ?int $application_id = null;
    protected ?int $stage_id = null;
    protected ?int $interviewer_id = null;
    protected ?string $scheduled_date = null;
    protected ?string $actual_date = null;
    protected ?string $feedback_notes = null;
    protected ?float $rating = null;
    protected ?string $status = null; // scheduled, completed, cancelled, rescheduled

    public static function getTableName(): string
    {
        return 'application_interview';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'application_id',
            'stage_id',
            'interviewer_id',
            'scheduled_date',
            'actual_date',
            'feedback_notes',
            'rating',
            'status'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'application_id' => ['required', 'numeric'],
            'stage_id' => ['required', 'numeric'],
            'interviewer_id' => ['required', 'numeric'],
            'scheduled_date' => ['required'],
            'status' => ['required']
        ];
    }

    /**
     * Get the application this interview is for
     */
    public function getApplication(): ?VacancyApplication
    {
        return VacancyApplication::find($this->application_id);
    }

    /**
     * Get the interview stage
     */
    public function getStage(): ?InterviewStage
    {
        return InterviewStage::find($this->stage_id);
    }

    /**
     * Get the interviewer (person)
     */
    public function getInterviewer(): ?Person
    {
        return Person::find($this->interviewer_id);
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
     * Schedule the interview
     */
    public function schedule(string $scheduledDate, ?int $userId = null): bool
    {
        $this->scheduled_date = $scheduledDate;
        $this->status = 'scheduled';
        return $this->save($userId);
    }

    /**
     * Reschedule the interview
     */
    public function reschedule(string $newScheduledDate, ?int $userId = null): bool
    {
        $this->scheduled_date = $newScheduledDate;
        $this->status = 'rescheduled';
        return $this->save($userId);
    }

    /**
     * Complete the interview with feedback
     */
    public function complete(string $feedbackNotes, float $rating, ?int $userId = null): bool
    {
        $this->actual_date = date('Y-m-d H:i:s');
        $this->feedback_notes = $feedbackNotes;
        $this->rating = $rating;
        $this->status = 'completed';

        $saved = $this->save($userId);

        if ($saved) {
            $application = $this->getApplication();
            if ($application) {
                $application->markInterviewed($userId);
            }
        }

        return $saved;
    }

    /**
     * Cancel the interview
     */
    public function cancel(?int $userId = null): bool
    {
        $this->status = 'cancelled';
        return $this->save($userId);
    }

    /**
     * Check if interview is upcoming
     */
    public function isUpcoming(): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        $now = new \DateTime();
        $scheduled = new \DateTime($this->scheduled_date);

        return $scheduled > $now;
    }

    /**
     * Check if interview is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        $now = new \DateTime();
        $scheduled = new \DateTime($this->scheduled_date);

        return $scheduled < $now;
    }

    /**
     * Check if interview was completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get days until scheduled interview
     */
    public function getDaysUntilInterview(): int
    {
        $now = new \DateTime();
        $scheduled = new \DateTime($this->scheduled_date);
        return $now->diff($scheduled)->days;
    }

    /**
     * Get interview summary
     */
    public function getSummary(): array
    {
        return [
            'stage' => $this->getStage()?->toArray(),
            'interviewer' => $this->getInterviewer()?->toArray(),
            'scheduled_date' => $this->scheduled_date,
            'actual_date' => $this->actual_date,
            'rating' => $this->rating,
            'status' => $this->status
        ];
    }

    /**
     * Get interviews by application
     */
    public static function getByApplication(int $applicationId): array
    {
        return static::where('application_id = :application_id', ['application_id' => $applicationId]);
    }

    /**
     * Get interviews by stage
     */
    public static function getByStage(int $stageId): array
    {
        return static::where('stage_id = :stage_id', ['stage_id' => $stageId]);
    }

    /**
     * Get interviews by interviewer
     */
    public static function getByInterviewer(int $interviewerId): array
    {
        return static::where('interviewer_id = :interviewer_id', ['interviewer_id' => $interviewerId]);
    }

    /**
     * Get upcoming interviews for an interviewer
     */
    public static function getUpcomingForInterviewer(int $interviewerId): array
    {
        return static::where(
            'interviewer_id = :interviewer_id AND status = :status AND scheduled_date > :now',
            [
                'interviewer_id' => $interviewerId,
                'status' => 'scheduled',
                'now' => date('Y-m-d H:i:s')
            ]
        );
    }
}
