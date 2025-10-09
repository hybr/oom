<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * SellerServiceSchedule Entity
 * Service and rental availability schedules
 */
class SellerServiceSchedule extends BaseEntity {
    protected $table = 'seller_service_schedules';
    protected $fillable = [
        'seller_item_id', 'available_from_time', 'available_to_time',
        'days_available', 'duration_minutes', 'requires_appointment'
    ];

    /**
     * Get seller item
     */
    public function getSellerItem($scheduleId) {
        $sql = "SELECT si.* FROM seller_items si
                JOIN seller_service_schedules sss ON sss.seller_item_id = si.id
                WHERE sss.id = ? AND si.deleted_at IS NULL";
        return $this->queryOne($sql, [$scheduleId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($scheduleId) {
        $sql = "SELECT sss.*,
                si.type as seller_item_type, si.availability_status,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sss.id = ? AND sss.deleted_at IS NULL";
        return $this->queryOne($sql, [$scheduleId]);
    }

    /**
     * Get schedule by seller item
     */
    public function getBySellerItem($sellerItemId) {
        $sql = "SELECT * FROM seller_service_schedules
                WHERE seller_item_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$sellerItemId]);
    }

    /**
     * Get schedules by organization
     */
    public function getByOrganization($organizationId) {
        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                si.type as seller_item_type
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                WHERE si.organization_id = ? AND sss.deleted_at IS NULL
                ORDER BY ci.name ASC";
        return $this->query($sql, [$organizationId]);
    }

    /**
     * Get schedules available on specific day
     */
    public function getAvailableOnDay($dayOfWeek, $organizationId = null) {
        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sss.days_available LIKE ?
                AND si.availability_status = 'Available'
                AND sss.deleted_at IS NULL";

        $params = ["%$dayOfWeek%"];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY o.short_name ASC, ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get schedules requiring appointment
     */
    public function getRequiringAppointment($organizationId = null) {
        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sss.requires_appointment = 1
                AND si.availability_status = 'Available'
                AND sss.deleted_at IS NULL";

        $params = [];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY o.short_name ASC, ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get schedules by duration range
     */
    public function getByDurationRange($minMinutes, $maxMinutes, $organizationId = null) {
        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sss.duration_minutes BETWEEN ? AND ?
                AND si.availability_status = 'Available'
                AND sss.deleted_at IS NULL";

        $params = [$minMinutes, $maxMinutes];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY sss.duration_minutes ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get schedules by type (Service or Rent)
     */
    public function getByType($type, $organizationId = null) {
        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE si.type = ?
                AND si.availability_status = 'Available'
                AND sss.deleted_at IS NULL";

        $params = [$type];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY o.short_name ASC, ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Check if available on specific day
     */
    public function isAvailableOnDay($scheduleId, $dayOfWeek) {
        $schedule = $this->find($scheduleId);
        if (!$schedule) {
            return false;
        }

        $daysAvailable = json_decode($schedule['days_available'], true);
        if (is_array($daysAvailable)) {
            return in_array($dayOfWeek, $daysAvailable);
        }

        // If stored as comma-separated string
        return strpos($schedule['days_available'], $dayOfWeek) !== false;
    }

    /**
     * Get available days for schedule
     */
    public function getAvailableDays($scheduleId) {
        $schedule = $this->find($scheduleId);
        if (!$schedule) {
            return [];
        }

        $daysAvailable = json_decode($schedule['days_available'], true);
        if (is_array($daysAvailable)) {
            return $daysAvailable;
        }

        // If stored as comma-separated string
        return array_map('trim', explode(',', $schedule['days_available']));
    }

    /**
     * Update available days
     */
    public function updateAvailableDays($scheduleId, $days) {
        if (is_array($days)) {
            $daysJson = json_encode($days);
        } else {
            $daysJson = $days;
        }

        return $this->update($scheduleId, ['days_available' => $daysJson]);
    }

    /**
     * Check if schedule is currently available
     */
    public function isCurrentlyAvailable($scheduleId) {
        $schedule = $this->find($scheduleId);
        if (!$schedule) {
            return false;
        }

        $currentDay = date('D'); // Mon, Tue, etc.
        $currentTime = date('H:i:s');

        // Check if current day is in available days
        if (!$this->isAvailableOnDay($scheduleId, $currentDay)) {
            return false;
        }

        // Check if current time is within available time range
        if ($schedule['available_from_time'] && $schedule['available_to_time']) {
            return $currentTime >= $schedule['available_from_time']
                && $currentTime <= $schedule['available_to_time'];
        }

        return true;
    }

    /**
     * Get schedules available now
     */
    public function getCurrentlyAvailable($organizationId = null) {
        $currentDay = date('D');
        $currentTime = date('H:i:s');

        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                WHERE sss.days_available LIKE ?
                AND sss.available_from_time <= ?
                AND sss.available_to_time >= ?
                AND si.availability_status = 'Available'
                AND sss.deleted_at IS NULL";

        $params = ["%$currentDay%", $currentTime, $currentTime];

        if ($organizationId) {
            $sql .= " AND o.id = ?";
            $params[] = $organizationId;
        }

        $sql .= " ORDER BY o.short_name ASC, ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Calculate number of slots available in a day
     */
    public function calculateDailySlots($scheduleId) {
        $schedule = $this->find($scheduleId);
        if (!$schedule || !$schedule['duration_minutes']) {
            return 0;
        }

        $fromTime = strtotime($schedule['available_from_time']);
        $toTime = strtotime($schedule['available_to_time']);

        $availableMinutes = ($toTime - $fromTime) / 60;
        return floor($availableMinutes / $schedule['duration_minutes']);
    }

    /**
     * Get formatted schedule description
     */
    public function getScheduleDescription($scheduleId) {
        $schedule = $this->getWithDetails($scheduleId);
        if (!$schedule) {
            return 'N/A';
        }

        $days = $this->getAvailableDays($scheduleId);
        $daysStr = is_array($days) ? implode(', ', $days) : $days;

        $timeStr = '';
        if ($schedule['available_from_time'] && $schedule['available_to_time']) {
            $timeStr = date('g:i A', strtotime($schedule['available_from_time'])) . ' - '
                     . date('g:i A', strtotime($schedule['available_to_time']));
        }

        $duration = $schedule['duration_minutes'] ? $schedule['duration_minutes'] . ' minutes' : '';
        $appointment = $schedule['requires_appointment'] ? 'Appointment required' : 'Walk-in available';

        return "$daysStr | $timeStr | $duration | $appointment";
    }

    /**
     * Search schedules
     */
    public function searchSchedules($term, $city = null, $limit = 50) {
        $sql = "SELECT sss.*,
                ci.name as catalog_item_name,
                o.short_name as organization_name,
                pa.city
                FROM seller_service_schedules sss
                LEFT JOIN seller_items si ON sss.seller_item_id = si.id
                LEFT JOIN catalog_items ci ON si.catalog_item_id = ci.id
                LEFT JOIN organizations o ON si.organization_id = o.id
                LEFT JOIN organization_buildings ob ON si.available_from_building_id = ob.id
                LEFT JOIN postal_addresses pa ON ob.postal_address_id = pa.id
                WHERE (ci.name LIKE ? OR o.short_name LIKE ?)
                AND si.availability_status = 'Available'
                AND sss.deleted_at IS NULL";

        $params = ["%$term%", "%$term%"];

        if ($city) {
            $sql .= " AND pa.city = ?";
            $params[] = $city;
        }

        $sql .= " ORDER BY o.short_name ASC, ci.name ASC LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get statistics
     */
    public function getStatistics($organizationId = null) {
        $sql = "SELECT
                    COUNT(*) as total_schedules,
                    COUNT(CASE WHEN requires_appointment = 1 THEN 1 END) as appointment_required_count,
                    COUNT(CASE WHEN requires_appointment = 0 THEN 1 END) as walk_in_count,
                    AVG(duration_minutes) as average_duration,
                    MIN(duration_minutes) as min_duration,
                    MAX(duration_minutes) as max_duration,
                    COUNT(DISTINCT seller_item_id) as unique_items
                FROM seller_service_schedules sss";

        $params = [];

        if ($organizationId) {
            $sql .= " JOIN seller_items si ON sss.seller_item_id = si.id
                      WHERE si.organization_id = ? AND sss.deleted_at IS NULL";
            $params[] = $organizationId;
        } else {
            $sql .= " WHERE sss.deleted_at IS NULL";
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'seller_item_id' => 'required|integer',
            'available_from_time' => 'time',
            'available_to_time' => 'time',
            'days_available' => 'required',
            'duration_minutes' => 'integer|min:1',
            'requires_appointment' => 'boolean',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $schedule = $this->getWithDetails($id);
        if (!$schedule) {
            return 'N/A';
        }

        $days = $this->getAvailableDays($id);
        $daysStr = is_array($days) ? implode(', ', $days) : $days;

        return $schedule['catalog_item_name'] . ' - ' . $daysStr;
    }
}
