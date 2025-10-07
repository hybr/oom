<?php

namespace Entities;

/**
 * InterviewStage Entity
 * Represents different stages in an organization's interview process
 */
class InterviewStage extends BaseEntity
{
    protected ?int $organization_id = null;
    protected ?string $name = null;
    protected ?int $order_number = null;

    public static function getTableName(): string
    {
        return 'interview_stage';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'organization_id',
            'name',
            'order_number'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'organization_id' => ['required', 'numeric'],
            'name' => ['required', 'min:2', 'max:200'],
            'order_number' => ['required', 'numeric']
        ];
    }

    /**
     * Get the organization this stage belongs to
     */
    public function getOrganization(): ?Organization
    {
        return Organization::find($this->organization_id);
    }

    /**
     * Get all interviews for this stage
     */
    public function getInterviews(): array
    {
        return ApplicationInterview::where('stage_id = :stage_id', ['stage_id' => $this->id]);
    }

    /**
     * Get completed interviews for this stage
     */
    public function getCompletedInterviews(): array
    {
        return ApplicationInterview::where(
            'stage_id = :stage_id AND status = :status',
            ['stage_id' => $this->id, 'status' => 'completed']
        );
    }

    /**
     * Get scheduled interviews for this stage
     */
    public function getScheduledInterviews(): array
    {
        return ApplicationInterview::where(
            'stage_id = :stage_id AND status = :status',
            ['stage_id' => $this->id, 'status' => 'scheduled']
        );
    }

    /**
     * Get next stage in sequence
     */
    public function getNextStage(): ?self
    {
        $stages = static::where(
            'organization_id = :org_id AND order_number > :order',
            ['org_id' => $this->organization_id, 'order' => $this->order_number],
            1
        );
        return !empty($stages) ? $stages[0] : null;
    }

    /**
     * Get previous stage in sequence
     */
    public function getPreviousStage(): ?self
    {
        $stages = static::where(
            'organization_id = :org_id AND order_number < :order',
            ['org_id' => $this->organization_id, 'order' => $this->order_number],
            1
        );
        return !empty($stages) ? $stages[0] : null;
    }

    /**
     * Check if this is the first stage
     */
    public function isFirstStage(): bool
    {
        return $this->getPreviousStage() === null;
    }

    /**
     * Check if this is the last stage
     */
    public function isLastStage(): bool
    {
        return $this->getNextStage() === null;
    }

    /**
     * Get count of interviews in this stage
     */
    public function getInterviewCount(): int
    {
        return ApplicationInterview::count('stage_id = :stage_id', ['stage_id' => $this->id]);
    }

    /**
     * Get average rating for this stage
     */
    public function getAverageRating(): float
    {
        $interviews = $this->getCompletedInterviews();
        if (empty($interviews)) {
            return 0.0;
        }

        $totalRating = 0;
        $count = 0;

        foreach ($interviews as $interview) {
            if ($interview->rating !== null) {
                $totalRating += $interview->rating;
                $count++;
            }
        }

        return $count > 0 ? $totalRating / $count : 0.0;
    }

    /**
     * Reorder this stage
     */
    public function reorder(int $newOrder, ?int $userId = null): bool
    {
        $this->order_number = $newOrder;
        return $this->save($userId);
    }

    /**
     * Get all stages for an organization ordered by sequence
     */
    public static function getByOrganization(int $organizationId): array
    {
        return static::where('organization_id = :org_id ORDER BY order_number ASC', ['org_id' => $organizationId]);
    }

    /**
     * Get stage by organization and order number
     */
    public static function getByOrder(int $organizationId, int $orderNumber): ?self
    {
        $stages = static::where(
            'organization_id = :org_id AND order_number = :order',
            ['org_id' => $organizationId, 'order' => $orderNumber],
            1
        );
        return !empty($stages) ? $stages[0] : null;
    }

    /**
     * Count stages for an organization
     */
    public static function countByOrganization(int $organizationId): int
    {
        return static::count('organization_id = :org_id', ['org_id' => $organizationId]);
    }

    /**
     * Get the highest order number for an organization
     */
    public static function getMaxOrder(int $organizationId): int
    {
        $stages = static::getByOrganization($organizationId);
        $maxOrder = 0;

        foreach ($stages as $stage) {
            if ($stage->order_number > $maxOrder) {
                $maxOrder = $stage->order_number;
            }
        }

        return $maxOrder;
    }
}
