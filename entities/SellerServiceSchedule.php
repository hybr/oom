<?php

namespace Entities;

/**
 * SellerServiceSchedule Entity
 * Scheduling information for services and rentals
 */
class SellerServiceSchedule extends BaseEntity
{
    protected ?int $seller_item_id = null;
    protected ?string $available_from_time = null;
    protected ?string $available_to_time = null;
    protected ?string $days_available = null; // JSON: ["Mon", "Tue", "Wed", ...]
    protected ?int $duration_minutes = null;
    protected bool $requires_appointment = false;

    public static function getTableName(): string
    {
        return 'seller_service_schedule';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'seller_item_id', 'available_from_time', 'available_to_time',
            'days_available', 'duration_minutes', 'requires_appointment'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'seller_item_id' => ['required', 'numeric'],
            'available_from_time' => ['required'],
            'available_to_time' => ['required'],
            'duration_minutes' => ['required', 'numeric'],
        ];
    }

    /**
     * Get seller item
     */
    public function getSellerItem(): ?SellerItem
    {
        return SellerItem::find($this->seller_item_id);
    }

    /**
     * Get days available as array
     */
    public function getDaysArray(): array
    {
        if (empty($this->days_available)) {
            return [];
        }

        $days = json_decode($this->days_available, true);
        return is_array($days) ? $days : [];
    }

    /**
     * Set days available from array
     */
    public function setDaysArray(array $days): void
    {
        $this->days_available = json_encode($days);
    }

    /**
     * Check if available on a specific day
     */
    public function isAvailableOnDay(string $day): bool
    {
        $days = $this->getDaysArray();
        return in_array($day, $days);
    }

    /**
     * Check if available today
     */
    public function isAvailableToday(): bool
    {
        $today = date('D'); // Mon, Tue, Wed, etc.
        return $this->isAvailableOnDay($today);
    }

    /**
     * Check if available at a specific time
     */
    public function isAvailableAtTime(string $time): bool
    {
        return $time >= $this->available_from_time && $time <= $this->available_to_time;
    }

    /**
     * Get formatted time range
     */
    public function getFormattedTimeRange(): string
    {
        return date('g:i A', strtotime($this->available_from_time)) . ' - ' .
               date('g:i A', strtotime($this->available_to_time));
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    /**
     * Get available days formatted
     */
    public function getFormattedDays(): string
    {
        $days = $this->getDaysArray();

        if (count($days) == 7) {
            return 'Every day';
        } elseif (count($days) == 5 && !in_array('Sat', $days) && !in_array('Sun', $days)) {
            return 'Weekdays';
        } elseif (count($days) == 2 && in_array('Sat', $days) && in_array('Sun', $days)) {
            return 'Weekends';
        } else {
            return implode(', ', $days);
        }
    }

    /**
     * Calculate number of slots available per day
     */
    public function getSlotsPerDay(): int
    {
        if ($this->duration_minutes <= 0) {
            return 0;
        }

        $startTime = strtotime($this->available_from_time);
        $endTime = strtotime($this->available_to_time);
        $totalMinutes = ($endTime - $startTime) / 60;

        return (int)floor($totalMinutes / $this->duration_minutes);
    }

    /**
     * Get available time slots for a specific date
     */
    public function getTimeSlots(string $date): array
    {
        $dayOfWeek = date('D', strtotime($date));

        if (!$this->isAvailableOnDay($dayOfWeek)) {
            return [];
        }

        $slots = [];
        $startTime = strtotime($date . ' ' . $this->available_from_time);
        $endTime = strtotime($date . ' ' . $this->available_to_time);

        $currentSlot = $startTime;
        while ($currentSlot + ($this->duration_minutes * 60) <= $endTime) {
            $slots[] = [
                'start' => date('H:i', $currentSlot),
                'end' => date('H:i', $currentSlot + ($this->duration_minutes * 60)),
                'formatted' => date('g:i A', $currentSlot) . ' - ' . date('g:i A', $currentSlot + ($this->duration_minutes * 60))
            ];

            $currentSlot += $this->duration_minutes * 60;
        }

        return $slots;
    }

    /**
     * Add a day to availability
     */
    public function addDay(string $day, ?int $userId = null): bool
    {
        $days = $this->getDaysArray();

        if (!in_array($day, $days)) {
            $days[] = $day;
            $this->setDaysArray($days);
            return $this->save($userId);
        }

        return false;
    }

    /**
     * Remove a day from availability
     */
    public function removeDay(string $day, ?int $userId = null): bool
    {
        $days = $this->getDaysArray();
        $key = array_search($day, $days);

        if ($key !== false) {
            unset($days[$key]);
            $this->setDaysArray(array_values($days));
            return $this->save($userId);
        }

        return false;
    }

    /**
     * Get schedule by seller item
     */
    public static function getBySellerItem(int $sellerItemId): ?SellerServiceSchedule
    {
        $schedules = static::where('seller_item_id = :seller_item_id', ['seller_item_id' => $sellerItemId], 1);
        return $schedules[0] ?? null;
    }

    /**
     * Get schedules requiring appointment
     */
    public static function getRequiringAppointment(): array
    {
        return static::where('requires_appointment = 1');
    }

    /**
     * Get schedules available today
     */
    public static function getAvailableToday(): array
    {
        $today = date('D');
        $sql = "SELECT * FROM " . static::getTableName() . "
                WHERE days_available LIKE :day AND deleted_at IS NULL";
        $data = \App\Database::fetchAll($sql, ['day' => "%{$today}%"]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Get schedules by organization
     */
    public static function getByOrganization(int $organizationId): array
    {
        $sql = "SELECT ss.* FROM " . static::getTableName() . " ss
                INNER JOIN seller_item si ON ss.seller_item_id = si.id
                WHERE si.organization_id = :org_id AND ss.deleted_at IS NULL";
        $data = \App\Database::fetchAll($sql, ['org_id' => $organizationId]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }
}
