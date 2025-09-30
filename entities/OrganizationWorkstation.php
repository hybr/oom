<?php

require_once __DIR__ . '/BaseEntity.php';

class OrganizationWorkstation extends BaseEntity {
    protected $table = 'organization_workstations';
    protected $fillable = [
        'id',
        'building_id',
        'floor_number',
        'zone_area',
        'seat_number',
        'seat_code',
        'workstation_type',
        'facilities_features',
        'occupancy_status',
        'assigned_employee_id',
        'coordinate_x',
        'coordinate_y',
        'width_cm',
        'depth_cm',
        'height_cm',
        'desk_material',
        'chair_type',
        'chair_material',
        'storage_type',
        'storage_capacity',
        'privacy_level',
        'lighting_type',
        'power_outlets',
        'network_ports',
        'phone_extension',
        'monitor_count',
        'monitor_size',
        'monitor_resolution',
        'cpu_holder',
        'keyboard_tray',
        'mouse_pad',
        'document_holder',
        'cable_management',
        'ergonomic_accessories',
        'environmental_controls',
        'noise_level',
        'accessibility_features',
        'special_equipment',
        'maintenance_schedule',
        'last_maintenance_date',
        'next_maintenance_date',
        'warranty_expiry',
        'insurance_coverage',
        'asset_tag',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'depreciation_rate',
        'current_value',
        'supplier_id',
        'department_id',
        'cost_center',
        'budget_code',
        'reservation_allowed',
        'booking_duration_limit',
        'advance_booking_days',
        'cleaning_frequency',
        'sanitization_required',
        'safety_compliance',
        'fire_safety_zone',
        'evacuation_route',
        'emergency_contact_point',
        'status',
        'is_active',
        'remarks',
        'notes',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function __construct() {
        parent::__construct();
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        $this->attributes['occupancy_status'] = 'Available';
        $this->attributes['is_active'] = 1;
        $this->attributes['status'] = 'Active';
    }

    protected function getSchema() {
        return "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            building_id INTEGER NOT NULL,
            floor_number INTEGER NOT NULL,
            zone_area VARCHAR(100),
            seat_number VARCHAR(50),
            seat_code VARCHAR(50) UNIQUE NOT NULL,
            workstation_type VARCHAR(50) DEFAULT 'Open Desk',
            facilities_features TEXT,
            occupancy_status VARCHAR(20) DEFAULT 'Available',
            assigned_employee_id INTEGER NULL,
            coordinate_x DECIMAL(10,2),
            coordinate_y DECIMAL(10,2),
            width_cm DECIMAL(8,2),
            depth_cm DECIMAL(8,2),
            height_cm DECIMAL(8,2),
            desk_material VARCHAR(100),
            chair_type VARCHAR(100),
            chair_material VARCHAR(100),
            storage_type VARCHAR(100),
            storage_capacity VARCHAR(100),
            privacy_level VARCHAR(50),
            lighting_type VARCHAR(100),
            power_outlets INTEGER DEFAULT 2,
            network_ports INTEGER DEFAULT 1,
            phone_extension VARCHAR(20),
            monitor_count INTEGER DEFAULT 1,
            monitor_size VARCHAR(20),
            monitor_resolution VARCHAR(20),
            cpu_holder BOOLEAN DEFAULT 0,
            keyboard_tray BOOLEAN DEFAULT 0,
            mouse_pad BOOLEAN DEFAULT 1,
            document_holder BOOLEAN DEFAULT 0,
            cable_management BOOLEAN DEFAULT 0,
            ergonomic_accessories TEXT,
            environmental_controls TEXT,
            noise_level VARCHAR(20),
            accessibility_features TEXT,
            special_equipment TEXT,
            maintenance_schedule VARCHAR(50),
            last_maintenance_date DATE,
            next_maintenance_date DATE,
            warranty_expiry DATE,
            insurance_coverage TEXT,
            asset_tag VARCHAR(50),
            serial_number VARCHAR(100),
            purchase_date DATE,
            purchase_cost DECIMAL(12,2),
            depreciation_rate DECIMAL(5,2),
            current_value DECIMAL(12,2),
            supplier_id INTEGER,
            department_id INTEGER,
            cost_center VARCHAR(50),
            budget_code VARCHAR(50),
            reservation_allowed BOOLEAN DEFAULT 1,
            booking_duration_limit INTEGER DEFAULT 8,
            advance_booking_days INTEGER DEFAULT 30,
            cleaning_frequency VARCHAR(50),
            sanitization_required BOOLEAN DEFAULT 0,
            safety_compliance TEXT,
            fire_safety_zone VARCHAR(50),
            evacuation_route TEXT,
            emergency_contact_point VARCHAR(100),
            status VARCHAR(20) DEFAULT 'Active',
            is_active BOOLEAN DEFAULT 1,
            remarks TEXT,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_by INTEGER,
            updated_by INTEGER,
            FOREIGN KEY (building_id) REFERENCES organization_buildings(id),
            FOREIGN KEY (assigned_employee_id) REFERENCES employees(id),
            FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
            FOREIGN KEY (department_id) REFERENCES departments(id),
            FOREIGN KEY (created_by) REFERENCES users(id),
            FOREIGN KEY (updated_by) REFERENCES users(id)
        )";
    }

    // Entity-specific methods

    public function assignToEmployee($employeeId) {
        $this->assigned_employee_id = $employeeId;
        $this->occupancy_status = 'Occupied';
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function unassignFromEmployee() {
        $this->assigned_employee_id = null;
        $this->occupancy_status = 'Available';
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function reserve() {
        if ($this->occupancy_status === 'Available') {
            $this->occupancy_status = 'Reserved';
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->save();
        }
        return false;
    }

    public function setMaintenanceMode() {
        $this->occupancy_status = 'Under Maintenance';
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function completeMaintenanceMode() {
        $this->occupancy_status = 'Available';
        $this->last_maintenance_date = date('Y-m-d');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateFacilities($facilities) {
        $this->facilities_features = is_array($facilities) ? json_encode($facilities) : $facilities;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function getFacilities() {
        if (!empty($this->facilities_features)) {
            $decoded = json_decode($this->facilities_features, true);
            return $decoded !== null ? $decoded : $this->facilities_features;
        }
        return [];
    }

    public function calculateDepreciation() {
        if ($this->purchase_date && $this->purchase_cost && $this->depreciation_rate) {
            $purchaseDate = new DateTime($this->purchase_date);
            $currentDate = new DateTime();
            $yearsDiff = $currentDate->diff($purchaseDate)->y;

            $depreciatedAmount = ($this->purchase_cost * $this->depreciation_rate / 100) * $yearsDiff;
            $this->current_value = max(0, $this->purchase_cost - $depreciatedAmount);
            return $this->current_value;
        }
        return 0;
    }

    public function isAvailable() {
        return $this->occupancy_status === 'Available' && $this->is_active && $this->status === 'Active';
    }

    public function isOccupied() {
        return $this->occupancy_status === 'Occupied';
    }

    public function isReserved() {
        return $this->occupancy_status === 'Reserved';
    }

    public function isUnderMaintenance() {
        return $this->occupancy_status === 'Under Maintenance';
    }

    public function getLocationDescription() {
        $parts = [];
        if ($this->zone_area) $parts[] = $this->zone_area;
        if ($this->floor_number) $parts[] = "Floor " . $this->floor_number;
        if ($this->seat_code) $parts[] = $this->seat_code;
        return implode(', ', $parts);
    }

    public function needsMaintenance() {
        if ($this->next_maintenance_date) {
            $nextDate = new DateTime($this->next_maintenance_date);
            $currentDate = new DateTime();
            return $currentDate >= $nextDate;
        }
        return false;
    }

    public function getMaintenanceDaysRemaining() {
        if ($this->next_maintenance_date) {
            $nextDate = new DateTime($this->next_maintenance_date);
            $currentDate = new DateTime();
            $diff = $currentDate->diff($nextDate);
            return $diff->invert ? 0 : $diff->days;
        }
        return null;
    }

    public function updateCoordinates($x, $y) {
        $this->coordinate_x = $x;
        $this->coordinate_y = $y;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function addSpecialEquipment($equipment) {
        $currentEquipment = $this->getSpecialEquipment();
        $currentEquipment[] = $equipment;
        $this->special_equipment = json_encode($currentEquipment);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function removeSpecialEquipment($equipment) {
        $currentEquipment = $this->getSpecialEquipment();
        $index = array_search($equipment, $currentEquipment);
        if ($index !== false) {
            unset($currentEquipment[$index]);
            $this->special_equipment = json_encode(array_values($currentEquipment));
            $this->updated_at = date('Y-m-d H:i:s');
            return $this->save();
        }
        return false;
    }

    public function getSpecialEquipment() {
        if (!empty($this->special_equipment)) {
            $decoded = json_decode($this->special_equipment, true);
            return $decoded !== null ? $decoded : [];
        }
        return [];
    }

    public function scheduleNextMaintenance($days) {
        $this->next_maintenance_date = date('Y-m-d', strtotime("+{$days} days"));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateAccessibilityFeatures($features) {
        $this->accessibility_features = is_array($features) ? json_encode($features) : $features;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function getAccessibilityFeatures() {
        if (!empty($this->accessibility_features)) {
            $decoded = json_decode($this->accessibility_features, true);
            return $decoded !== null ? $decoded : $this->accessibility_features;
        }
        return [];
    }

    public function isAccessible() {
        $features = $this->getAccessibilityFeatures();
        return !empty($features);
    }

    public function checkWarrantyStatus() {
        if ($this->warranty_expiry) {
            $expiryDate = new DateTime($this->warranty_expiry);
            $currentDate = new DateTime();
            return $currentDate <= $expiryDate;
        }
        return false;
    }

    public function getWarrantyDaysRemaining() {
        if ($this->warranty_expiry) {
            $expiryDate = new DateTime($this->warranty_expiry);
            $currentDate = new DateTime();
            $diff = $currentDate->diff($expiryDate);
            return $diff->invert ? 0 : $diff->days;
        }
        return null;
    }

    // Static methods for business logic

    public static function getAvailableWorkstations($buildingId = null) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE occupancy_status = 'Available' AND is_active = 1 AND status = 'Active'";
        $params = [];

        if ($buildingId) {
            $sql .= " AND building_id = :building_id";
            $params['building_id'] = $buildingId;
        }

        $results = $instance->db->fetchAll($sql, $params);
        $workstations = [];
        foreach ($results as $data) {
            $workstation = new static();
            $workstation->fill($data);
            $workstations[] = $workstation;
        }
        return $workstations;
    }

    public static function getWorkstationsByType($type) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE workstation_type = :type AND is_active = 1";
        $results = $instance->db->fetchAll($sql, ['type' => $type]);

        $workstations = [];
        foreach ($results as $data) {
            $workstation = new static();
            $workstation->fill($data);
            $workstations[] = $workstation;
        }
        return $workstations;
    }

    public static function getWorkstationsByFloor($buildingId, $floorNumber) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE building_id = :building_id AND floor_number = :floor_number AND is_active = 1";
        $results = $instance->db->fetchAll($sql, ['building_id' => $buildingId, 'floor_number' => $floorNumber]);

        $workstations = [];
        foreach ($results as $data) {
            $workstation = new static();
            $workstation->fill($data);
            $workstations[] = $workstation;
        }
        return $workstations;
    }

    public static function getMaintenanceSchedule() {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE next_maintenance_date <= DATE('now', '+7 days') AND is_active = 1 ORDER BY next_maintenance_date";
        $results = $instance->db->fetchAll($sql);

        $workstations = [];
        foreach ($results as $data) {
            $workstation = new static();
            $workstation->fill($data);
            $workstations[] = $workstation;
        }
        return $workstations;
    }

    public static function getOccupancyReport($buildingId = null) {
        $instance = new static();
        $sql = "SELECT occupancy_status, COUNT(*) as count FROM {$instance->table} WHERE is_active = 1";
        $params = [];

        if ($buildingId) {
            $sql .= " AND building_id = :building_id";
            $params['building_id'] = $buildingId;
        }

        $sql .= " GROUP BY occupancy_status";

        return $instance->db->fetchAll($sql, $params);
    }

    public static function searchWorkstations($criteria) {
        $instance = new static();
        $conditions = [];
        $params = [];

        if (!empty($criteria['building_id'])) {
            $conditions[] = "building_id = :building_id";
            $params['building_id'] = $criteria['building_id'];
        }

        if (!empty($criteria['floor_number'])) {
            $conditions[] = "floor_number = :floor_number";
            $params['floor_number'] = $criteria['floor_number'];
        }

        if (!empty($criteria['workstation_type'])) {
            $conditions[] = "workstation_type = :workstation_type";
            $params['workstation_type'] = $criteria['workstation_type'];
        }

        if (!empty($criteria['occupancy_status'])) {
            $conditions[] = "occupancy_status = :occupancy_status";
            $params['occupancy_status'] = $criteria['occupancy_status'];
        }

        if (!empty($criteria['zone_area'])) {
            $conditions[] = "zone_area LIKE :zone_area";
            $params['zone_area'] = '%' . $criteria['zone_area'] . '%';
        }

        $sql = "SELECT * FROM {$instance->table} WHERE is_active = 1";
        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY floor_number, zone_area, seat_code";

        $results = $instance->db->fetchAll($sql, $params);
        $workstations = [];
        foreach ($results as $data) {
            $workstation = new static();
            $workstation->fill($data);
            $workstations[] = $workstation;
        }
        return $workstations;
    }

    public function validate() {
        $errors = [];

        if (empty($this->attributes['building_id'])) {
            $errors[] = "Building ID is required";
        }

        if (empty($this->attributes['seat_code'])) {
            $errors[] = "Seat code is required";
        }

        if (empty($this->attributes['floor_number'])) {
            $errors[] = "Floor number is required";
        }

        $status = $this->attributes['occupancy_status'] ?? '';
        if (!in_array($status, ['Available', 'Occupied', 'Reserved', 'Under Maintenance'])) {
            $errors[] = "Invalid occupancy status";
        }

        $type = $this->attributes['workstation_type'] ?? '';
        if (!in_array($type, ['Cabin', 'Open Desk', 'Cubicle', 'Standing Desk', 'Hot Desk', 'Meeting Booth'])) {
            $errors[] = "Invalid workstation type";
        }

        return $errors;
    }

    public function beforeSave() {
        $this->updated_at = date('Y-m-d H:i:s');

        // Auto-generate seat code if not provided
        if (empty($this->seat_code) && !empty($this->floor_number) && !empty($this->seat_number)) {
            $this->seat_code = "WS-{$this->floor_number}F-{$this->seat_number}";
        }

        // Calculate current value if purchase details are available
        if ($this->purchase_date && $this->purchase_cost && $this->depreciation_rate) {
            $this->calculateDepreciation();
        }

        return true;
    }
}