<?php

require_once __DIR__ . '/BaseEntity.php';

class OrganizationBuilding extends BaseEntity {
    protected $table = 'organization_buildings';
    protected $fillable = [
        'id',
        'organization_branch_id',
        'postal_address_id',
        'name',
        'code',
        'slug',
        'description',
        'building_type',
        'status',
        'ownership_type',
        'construction_year',
        'renovation_year',
        'architectural_style',
        'building_condition',
        'occupancy_date',
        'vacancy_date',
        'total_floors',
        'basement_levels',
        'total_area_sqft',
        'usable_area_sqft',
        'rentable_area_sqft',
        'common_area_sqft',
        'storage_area_sqft',
        'parking_spaces',
        'parking_type',
        'accessibility_compliant',
        'accessibility_features',
        'elevator_count',
        'escalator_count',
        'stairwell_count',
        'loading_dock_count',
        'entrance_count',
        'emergency_exits',
        'fire_safety_systems',
        'security_systems',
        'surveillance_cameras',
        'access_control_systems',
        'alarm_systems',
        'sprinkler_systems',
        'hvac_type',
        'hvac_zones',
        'heating_system',
        'cooling_system',
        'ventilation_type',
        'air_quality_monitoring',
        'temperature_control',
        'humidity_control',
        'lighting_type',
        'lighting_control',
        'natural_lighting',
        'emergency_lighting',
        'electrical_capacity',
        'electrical_panels',
        'backup_generator',
        'ups_systems',
        'power_outlets',
        'network_infrastructure',
        'internet_connectivity',
        'wifi_coverage',
        'phone_systems',
        'intercom_systems',
        'av_systems',
        'conference_facilities',
        'water_supply',
        'sewage_systems',
        'plumbing_fixtures',
        'restroom_count',
        'kitchen_facilities',
        'cafeteria_capacity',
        'break_room_count',
        'meeting_room_count',
        'office_count',
        'cubicle_count',
        'workstation_count',
        'reception_areas',
        'waiting_areas',
        'storage_rooms',
        'server_rooms',
        'utility_rooms',
        'janitorial_closets',
        'maintenance_areas',
        'bike_storage',
        'locker_facilities',
        'fitness_facilities',
        'outdoor_spaces',
        'landscaping',
        'signage_external',
        'signage_internal',
        'wayfinding_systems',
        'building_materials',
        'roofing_type',
        'roofing_condition',
        'exterior_walls',
        'windows_type',
        'flooring_types',
        'ceiling_types',
        'insulation_type',
        'insulation_rating',
        'energy_efficiency_rating',
        'green_certifications',
        'environmental_features',
        'sustainability_measures',
        'waste_management',
        'recycling_facilities',
        'water_conservation',
        'energy_conservation',
        'renewable_energy',
        'carbon_footprint',
        'lease_terms',
        'lease_start_date',
        'lease_end_date',
        'lease_renewal_options',
        'monthly_rent',
        'annual_rent',
        'rent_currency',
        'rent_escalation',
        'maintenance_fee',
        'utilities_included',
        'utilities_separate',
        'property_taxes',
        'insurance_required',
        'insurance_coverage',
        'tenant_improvements',
        'landlord_improvements',
        'maintenance_responsibilities',
        'repair_responsibilities',
        'compliance_requirements',
        'zoning_classification',
        'permitted_uses',
        'occupancy_limits',
        'building_codes',
        'safety_inspections',
        'fire_inspections',
        'elevator_inspections',
        'environmental_inspections',
        'last_inspection_date',
        'next_inspection_date',
        'inspection_certifications',
        'violations_history',
        'maintenance_schedule',
        'cleaning_schedule',
        'pest_control_schedule',
        'landscape_maintenance',
        'equipment_maintenance',
        'preventive_maintenance',
        'maintenance_contracts',
        'service_providers',
        'emergency_procedures',
        'evacuation_plans',
        'business_continuity',
        'disaster_recovery',
        'weather_protection',
        'flood_protection',
        'seismic_upgrades',
        'historical_significance',
        'architectural_heritage',
        'landmark_status',
        'historical_preservation',
        'future_expansion_plans',
        'renovation_plans',
        'modernization_needs',
        'technology_upgrades',
        'space_optimization',
        'capacity_planning',
        'move_in_costs',
        'move_out_costs',
        'setup_requirements',
        'furniture_included',
        'equipment_included',
        'tenant_services',
        'building_amenities',
        'shared_facilities',
        'community_spaces',
        'building_management',
        'property_manager_contact',
        'maintenance_contact',
        'security_contact',
        'emergency_contact',
        'operating_hours',
        'access_hours',
        'holiday_schedule',
        'special_events',
        'building_policies',
        'smoking_policy',
        'pet_policy',
        'visitor_policy',
        'delivery_procedures',
        'noise_regulations',
        'photography_restrictions',
        'marketing_materials',
        'virtual_tour_url',
        'floor_plans',
        'building_photos',
        'promotional_materials',
        'marketing_contact',
        'is_flagship',
        'is_headquarters',
        'is_leased',
        'is_owned',
        'is_available',
        'is_occupied',
        'is_under_renovation',
        'is_energy_efficient',
        'is_green_certified',
        'is_accessible',
        'is_historic',
        'is_featured',
        'is_public',
        'display_online',
        'allows_tours',
        'priority_level',
        'condition_score',
        'energy_efficiency_score',
        'accessibility_score',
        'security_score',
        'technology_score',
        'occupancy_rate',
        'satisfaction_score',
        'maintenance_score',
        'sustainability_score',
        'sort_order',
        'notes',
        'internal_notes',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_at',
        'updated_at'
    ];

    // Building types
    const TYPE_OFFICE = 'office';
    const TYPE_RETAIL = 'retail';
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_MANUFACTURING = 'manufacturing';
    const TYPE_MIXED_USE = 'mixed_use';
    const TYPE_MEDICAL = 'medical';
    const TYPE_EDUCATIONAL = 'educational';
    const TYPE_HOSPITALITY = 'hospitality';
    const TYPE_RESIDENTIAL = 'residential';
    const TYPE_INDUSTRIAL = 'industrial';
    const TYPE_LABORATORY = 'laboratory';
    const TYPE_DATA_CENTER = 'data_center';
    const TYPE_PARKING = 'parking';
    const TYPE_STORAGE = 'storage';
    const TYPE_RECREATIONAL = 'recreational';

    // Building statuses
    const STATUS_PLANNING = 'planning';
    const STATUS_UNDER_CONSTRUCTION = 'under_construction';
    const STATUS_COMPLETED = 'completed';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_PARTIALLY_OCCUPIED = 'partially_occupied';
    const STATUS_VACANT = 'vacant';
    const STATUS_UNDER_RENOVATION = 'under_renovation';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_CONDEMNED = 'condemned';
    const STATUS_DEMOLISHED = 'demolished';
    const STATUS_ARCHIVED = 'archived';

    // Ownership types
    const OWNERSHIP_OWNED = 'owned';
    const OWNERSHIP_LEASED = 'leased';
    const OWNERSHIP_RENTED = 'rented';
    const OWNERSHIP_SUBLEASED = 'subleased';
    const OWNERSHIP_MANAGED = 'managed';
    const OWNERSHIP_PARTNERSHIP = 'partnership';
    const OWNERSHIP_FRANCHISE = 'franchise';

    // Building conditions
    const CONDITION_EXCELLENT = 'excellent';
    const CONDITION_VERY_GOOD = 'very_good';
    const CONDITION_GOOD = 'good';
    const CONDITION_FAIR = 'fair';
    const CONDITION_POOR = 'poor';
    const CONDITION_NEEDS_REPAIR = 'needs_repair';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    // Parking types
    const PARKING_SURFACE = 'surface';
    const PARKING_GARAGE = 'garage';
    const PARKING_UNDERGROUND = 'underground';
    const PARKING_STREET = 'street';
    const PARKING_VALET = 'valet';
    const PARKING_NONE = 'none';

    public function __construct() {
        parent::__construct();
        $this->attributes['building_type'] = self::TYPE_OFFICE;
        $this->attributes['status'] = self::STATUS_PLANNING;
        $this->attributes['ownership_type'] = self::OWNERSHIP_LEASED;
        $this->attributes['building_condition'] = self::CONDITION_GOOD;
        $this->attributes['priority_level'] = self::PRIORITY_MEDIUM;
        $this->attributes['parking_type'] = self::PARKING_SURFACE;
        $this->attributes['rent_currency'] = 'USD';
        $this->attributes['accessibility_compliant'] = 1;
        $this->attributes['is_flagship'] = 0;
        $this->attributes['is_headquarters'] = 0;
        $this->attributes['is_leased'] = 1;
        $this->attributes['is_owned'] = 0;
        $this->attributes['is_available'] = 1;
        $this->attributes['is_occupied'] = 0;
        $this->attributes['is_under_renovation'] = 0;
        $this->attributes['is_energy_efficient'] = 0;
        $this->attributes['is_green_certified'] = 0;
        $this->attributes['is_accessible'] = 1;
        $this->attributes['is_historic'] = 0;
        $this->attributes['is_featured'] = 0;
        $this->attributes['is_public'] = 1;
        $this->attributes['display_online'] = 1;
        $this->attributes['allows_tours'] = 1;
        $this->attributes['condition_score'] = 0;
        $this->attributes['energy_efficiency_score'] = 0;
        $this->attributes['accessibility_score'] = 0;
        $this->attributes['security_score'] = 0;
        $this->attributes['technology_score'] = 0;
        $this->attributes['occupancy_rate'] = 0;
        $this->attributes['satisfaction_score'] = 0;
        $this->attributes['maintenance_score'] = 0;
        $this->attributes['sustainability_score'] = 0;
        $this->attributes['sort_order'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // Relationship with OrganizationBranch entity (parent branch)
    public function getOrganizationBranch() {
        if (!$this->organization_branch_id) return null;

        require_once __DIR__ . '/OrganizationBranch.php';
        return OrganizationBranch::find($this->organization_branch_id);
    }

    public function getBranchName() {
        $branch = $this->getOrganizationBranch();
        return $branch ? $branch->getDisplayName() : 'Unknown Branch';
    }

    public function getOrganizationName() {
        $branch = $this->getOrganizationBranch();
        return $branch ? $branch->getOrganizationName() : 'Unknown Organization';
    }

    // Relationship with PostalAddress entity
    public function getPostalAddress() {
        if (!$this->postal_address_id) return null;

        require_once __DIR__ . '/PostalAddress.php';
        return PostalAddress::find($this->postal_address_id);
    }

    public function getAddressText() {
        $address = $this->getPostalAddress();
        return $address ? $address->getFormattedAddress() : 'No Address';
    }

    public function getShortAddress() {
        $address = $this->getPostalAddress();
        return $address ? $address->getShortAddress() : 'No Address';
    }

    public function getCountryName() {
        $address = $this->getPostalAddress();
        return $address ? $address->getCountryName() : 'Unknown';
    }

    public function getCoordinates() {
        $address = $this->getPostalAddress();
        if (!$address || !$address->hasCoordinates()) {
            return null;
        }
        return [
            'latitude' => $address->latitude,
            'longitude' => $address->longitude
        ];
    }

    // Building identification and naming
    public function getDisplayName() {
        if ($this->name) {
            return $this->name;
        }

        $branch = $this->getOrganizationBranch();
        $branchName = $branch ? $branch->getDisplayName() : 'Branch';

        if ($this->code) {
            return $branchName . ' - Building ' . $this->code;
        }

        return $branchName . ' Building #' . $this->id;
    }

    public function getFullName() {
        $orgName = $this->getOrganizationName();
        $branchName = $this->getBranchName();

        if ($this->name) {
            return $orgName . ' - ' . $branchName . ' - ' . $this->name;
        }

        if ($this->code) {
            return $orgName . ' - ' . $branchName . ' - Building ' . $this->code;
        }

        return $orgName . ' - ' . $branchName . ' Building #' . $this->id;
    }

    public function generateCode($code = null) {
        if ($code) {
            // Validate custom code
            if (!$this->isCodeAvailable($code)) {
                throw new Exception('Building code already exists');
            }
            return strtoupper($code);
        }

        // Auto-generate code
        $branch = $this->getOrganizationBranch();
        if (!$branch) {
            throw new Exception('Organization branch required to generate building code');
        }

        $branchPrefix = $branch->code ? substr($branch->code, 0, 6) : 'BLD';
        $buildingType = strtoupper(substr($this->building_type, 0, 2));

        // Find next available number
        $counter = 1;
        do {
            $code = $branchPrefix . $buildingType . sprintf('%02d', $counter);
            $counter++;
        } while (!$this->isCodeAvailable($code) && $counter <= 99);

        if ($counter > 99) {
            throw new Exception('Unable to generate unique building code');
        }

        return $code;
    }

    private function isCodeAvailable($code) {
        $existing = static::where('code', '=', $code);

        // If editing, exclude current building
        if ($this->id) {
            $existing = array_filter($existing, function($building) {
                return $building->id !== $this->id;
            });
        }

        return empty($existing);
    }

    public function generateSlug($name = null) {
        $name = $name ?: $this->getDisplayName();
        if (!$name) return '';

        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while ($this->isSlugTaken($slug)) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    private function isSlugTaken($slug) {
        $existing = static::where('slug', '=', $slug);

        // If editing, exclude current building
        if ($this->id) {
            $existing = array_filter($existing, function($building) {
                return $building->id !== $this->id;
            });
        }

        return !empty($existing);
    }

    // Business logic methods
    public function isOccupied() {
        return $this->status === self::STATUS_OCCUPIED || $this->status === self::STATUS_PARTIALLY_OCCUPIED;
    }

    public function isAvailable() {
        return $this->is_available == 1;
    }

    public function isOwned() {
        return $this->ownership_type === self::OWNERSHIP_OWNED || $this->is_owned == 1;
    }

    public function isLeased() {
        return $this->ownership_type === self::OWNERSHIP_LEASED || $this->is_leased == 1;
    }

    public function isAccessible() {
        return $this->accessibility_compliant == 1 || $this->is_accessible == 1;
    }

    public function isEnergyEfficient() {
        return $this->is_energy_efficient == 1;
    }

    public function isGreenCertified() {
        return $this->is_green_certified == 1;
    }

    public function isHistoric() {
        return $this->is_historic == 1;
    }

    public function isFeatured() {
        return $this->is_featured == 1;
    }

    public function isHeadquarters() {
        return $this->is_headquarters == 1;
    }

    public function isFlagship() {
        return $this->is_flagship == 1;
    }

    public function isUnderRenovation() {
        return $this->is_under_renovation == 1 || $this->status === self::STATUS_UNDER_RENOVATION;
    }

    public function allowsTours() {
        return $this->allows_tours == 1;
    }

    public function isDisplayedOnline() {
        return $this->display_online == 1;
    }

    public function isPublic() {
        return $this->is_public == 1;
    }

    // Space calculations
    public function getUsableSpacePercentage() {
        if (!$this->total_area_sqft || !$this->usable_area_sqft) return 0;
        return round(($this->usable_area_sqft / $this->total_area_sqft) * 100, 2);
    }

    public function getRentableSpacePercentage() {
        if (!$this->total_area_sqft || !$this->rentable_area_sqft) return 0;
        return round(($this->rentable_area_sqft / $this->total_area_sqft) * 100, 2);
    }

    public function getCommonAreaPercentage() {
        if (!$this->total_area_sqft || !$this->common_area_sqft) return 0;
        return round(($this->common_area_sqft / $this->total_area_sqft) * 100, 2);
    }

    public function getFormattedArea($area) {
        if (!$area) return 'Not specified';

        if ($area >= 1000000) {
            return number_format($area / 1000000, 1) . 'M sq ft';
        } elseif ($area >= 1000) {
            return number_format($area / 1000, 1) . 'K sq ft';
        } else {
            return number_format($area) . ' sq ft';
        }
    }

    // Financial calculations
    public function getFormattedRent($rentType = 'monthly') {
        $rent = $rentType === 'monthly' ? $this->monthly_rent : $this->annual_rent;
        if (!$rent) return 'Not disclosed';

        $currency = $this->rent_currency ?: 'USD';
        $amount = floatval($rent);

        if ($amount >= 1000000) {
            return $currency . ' ' . number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return $currency . ' ' . number_format($amount / 1000, 1) . 'K';
        } else {
            return $currency . ' ' . number_format($amount);
        }
    }

    public function getRentPerSquareFoot() {
        if (!$this->monthly_rent || !$this->rentable_area_sqft) return 0;
        return round(($this->monthly_rent * 12) / $this->rentable_area_sqft, 2);
    }

    // Age calculation
    public function getBuildingAge() {
        if (!$this->construction_year) return null;
        return date('Y') - $this->construction_year;
    }

    public function getAgeDescription() {
        $age = $this->getBuildingAge();
        if (!$age) return 'Unknown';

        if ($age < 1) return 'Less than a year';
        if ($age == 1) return '1 year old';
        return $age . ' years old';
    }

    public function getYearsSinceRenovation() {
        if (!$this->renovation_year) return null;
        return date('Y') - $this->renovation_year;
    }

    // Status management
    public function occupy() {
        $this->status = self::STATUS_OCCUPIED;
        $this->is_occupied = 1;
        $this->is_available = 0;
        $this->occupancy_date = date('Y-m-d');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function vacate() {
        $this->status = self::STATUS_VACANT;
        $this->is_occupied = 0;
        $this->is_available = 1;
        $this->vacancy_date = date('Y-m-d');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function startRenovation() {
        $this->status = self::STATUS_UNDER_RENOVATION;
        $this->is_under_renovation = 1;
        $this->is_available = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function completeRenovation($renovationYear = null) {
        $this->status = self::STATUS_COMPLETED;
        $this->is_under_renovation = 0;
        $this->is_available = 1;
        $this->renovation_year = $renovationYear ?: date('Y');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function archive() {
        $this->status = self::STATUS_ARCHIVED;
        $this->is_available = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Feature management
    public function makeHeadquarters() {
        $this->is_headquarters = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function removeHeadquarters() {
        $this->is_headquarters = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function makeFlagship() {
        $this->is_flagship = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function removeFlagship() {
        $this->is_flagship = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function feature() {
        $this->is_featured = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function unfeature() {
        $this->is_featured = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Score management
    public function updateConditionScore($score) {
        $this->condition_score = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateEnergyEfficiencyScore($score) {
        $this->energy_efficiency_score = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateAccessibilityScore($score) {
        $this->accessibility_score = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateSecurityScore($score) {
        $this->security_score = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateOccupancyRate($rate) {
        $this->occupancy_rate = max(0, min(100, floatval($rate)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Array field handlers
    public function getTagsArray() {
        if (!$this->tags) return [];
        return explode(',', $this->tags);
    }

    public function setTags($tagsArray) {
        $this->tags = is_array($tagsArray) ? implode(',', $tagsArray) : $tagsArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getAccessibilityFeaturesArray() {
        if (!$this->accessibility_features) return [];
        return explode(',', $this->accessibility_features);
    }

    public function setAccessibilityFeatures($featuresArray) {
        $this->accessibility_features = is_array($featuresArray) ? implode(',', $featuresArray) : $featuresArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getBuildingAmenitiesArray() {
        if (!$this->building_amenities) return [];
        return explode(',', $this->building_amenities);
    }

    public function setBuildingAmenities($amenitiesArray) {
        $this->building_amenities = is_array($amenitiesArray) ? implode(',', $amenitiesArray) : $amenitiesArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // Search and query methods
    public static function findByBranch($branchId) {
        return static::where('organization_branch_id', '=', $branchId);
    }

    public static function findByType($buildingType) {
        return static::where('building_type', '=', $buildingType);
    }

    public static function findByStatus($status) {
        return static::where('status', '=', $status);
    }

    public static function findByOwnership($ownershipType) {
        return static::where('ownership_type', '=', $ownershipType);
    }

    public static function findByCode($code) {
        $results = static::where('code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findBySlug($slug) {
        $results = static::where('slug', '=', $slug);
        return !empty($results) ? $results[0] : null;
    }

    public static function findOccupied() {
        return array_filter(static::all(), function($building) {
            return $building->isOccupied();
        });
    }

    public static function findAvailable() {
        return static::where('is_available', '=', 1);
    }

    public static function findOwned() {
        return static::where('ownership_type', '=', self::OWNERSHIP_OWNED);
    }

    public static function findLeased() {
        return static::where('ownership_type', '=', self::OWNERSHIP_LEASED);
    }

    public static function findAccessible() {
        return static::where('is_accessible', '=', 1);
    }

    public static function findEnergyEfficient() {
        return static::where('is_energy_efficient', '=', 1);
    }

    public static function findGreenCertified() {
        return static::where('is_green_certified', '=', 1);
    }

    public static function findFeatured() {
        return static::where('is_featured', '=', 1);
    }

    public static function findHeadquarters() {
        return static::where('is_headquarters', '=', 1);
    }

    public static function findFlagship() {
        return static::where('is_flagship', '=', 1);
    }

    public static function searchBuildings($query) {
        $buildings = static::all();
        $query = strtolower($query);

        return array_filter($buildings, function($building) use ($query) {
            return strpos(strtolower($building->name ?: ''), $query) !== false ||
                   strpos(strtolower($building->code ?: ''), $query) !== false ||
                   strpos(strtolower($building->description ?: ''), $query) !== false ||
                   strpos(strtolower($building->getBranchName()), $query) !== false ||
                   strpos(strtolower($building->getOrganizationName()), $query) !== false ||
                   strpos(strtolower($building->getAddressText()), $query) !== false;
        });
    }

    // Utility methods
    public function getStatistics() {
        return [
            'id' => $this->id,
            'name' => $this->getDisplayName(),
            'full_name' => $this->getFullName(),
            'code' => $this->code,
            'slug' => $this->slug,
            'branch' => $this->getBranchName(),
            'organization' => $this->getOrganizationName(),
            'type' => $this->building_type,
            'status' => $this->status,
            'ownership' => $this->ownership_type,
            'condition' => $this->building_condition,
            'address' => $this->getShortAddress(),
            'total_area' => $this->getFormattedArea($this->total_area_sqft),
            'rentable_area' => $this->getFormattedArea($this->rentable_area_sqft),
            'monthly_rent' => $this->getFormattedRent('monthly'),
            'rent_per_sqft' => $this->getRentPerSquareFoot(),
            'building_age' => $this->getAgeDescription(),
            'occupancy_rate' => $this->occupancy_rate . '%',
            'condition_score' => $this->condition_score,
            'is_owned' => $this->isOwned(),
            'is_leased' => $this->isLeased(),
            'is_accessible' => $this->isAccessible(),
            'is_energy_efficient' => $this->isEnergyEfficient(),
            'is_featured' => $this->isFeatured(),
            'created_at' => $this->created_at
        ];
    }

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['display_name'] = $this->getDisplayName();
        $data['full_name'] = $this->getFullName();
        $data['branch_name'] = $this->getBranchName();
        $data['organization_name'] = $this->getOrganizationName();
        $data['address_text'] = $this->getAddressText();
        $data['short_address'] = $this->getShortAddress();
        $data['country_name'] = $this->getCountryName();
        $data['coordinates'] = $this->getCoordinates();
        $data['formatted_monthly_rent'] = $this->getFormattedRent('monthly');
        $data['formatted_annual_rent'] = $this->getFormattedRent('annual');
        $data['rent_per_sqft'] = $this->getRentPerSquareFoot();
        $data['formatted_total_area'] = $this->getFormattedArea($this->total_area_sqft);
        $data['formatted_usable_area'] = $this->getFormattedArea($this->usable_area_sqft);
        $data['formatted_rentable_area'] = $this->getFormattedArea($this->rentable_area_sqft);
        $data['usable_space_percentage'] = $this->getUsableSpacePercentage();
        $data['rentable_space_percentage'] = $this->getRentableSpacePercentage();
        $data['common_area_percentage'] = $this->getCommonAreaPercentage();
        $data['building_age'] = $this->getBuildingAge();
        $data['age_description'] = $this->getAgeDescription();
        $data['years_since_renovation'] = $this->getYearsSinceRenovation();
        $data['tags_array'] = $this->getTagsArray();
        $data['accessibility_features_array'] = $this->getAccessibilityFeaturesArray();
        $data['building_amenities_array'] = $this->getBuildingAmenitiesArray();

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS organization_buildings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                organization_branch_id INTEGER NOT NULL,
                postal_address_id INTEGER NOT NULL,
                name TEXT,
                code TEXT UNIQUE,
                slug TEXT UNIQUE,
                description TEXT,
                building_type TEXT DEFAULT 'office',
                status TEXT DEFAULT 'planning',
                ownership_type TEXT DEFAULT 'leased',
                construction_year INTEGER,
                renovation_year INTEGER,
                architectural_style TEXT,
                building_condition TEXT DEFAULT 'good',
                occupancy_date DATE,
                vacancy_date DATE,
                total_floors INTEGER,
                basement_levels INTEGER,
                total_area_sqft DECIMAL(12,2),
                usable_area_sqft DECIMAL(12,2),
                rentable_area_sqft DECIMAL(12,2),
                common_area_sqft DECIMAL(12,2),
                storage_area_sqft DECIMAL(12,2),
                parking_spaces INTEGER,
                parking_type TEXT DEFAULT 'surface',
                accessibility_compliant INTEGER DEFAULT 1,
                accessibility_features TEXT,
                elevator_count INTEGER DEFAULT 0,
                escalator_count INTEGER DEFAULT 0,
                stairwell_count INTEGER DEFAULT 1,
                loading_dock_count INTEGER DEFAULT 0,
                entrance_count INTEGER DEFAULT 1,
                emergency_exits INTEGER,
                fire_safety_systems TEXT,
                security_systems TEXT,
                surveillance_cameras INTEGER DEFAULT 0,
                access_control_systems TEXT,
                alarm_systems TEXT,
                sprinkler_systems TEXT,
                hvac_type TEXT,
                hvac_zones INTEGER,
                heating_system TEXT,
                cooling_system TEXT,
                ventilation_type TEXT,
                air_quality_monitoring INTEGER DEFAULT 0,
                temperature_control INTEGER DEFAULT 1,
                humidity_control INTEGER DEFAULT 0,
                lighting_type TEXT,
                lighting_control TEXT,
                natural_lighting INTEGER DEFAULT 1,
                emergency_lighting INTEGER DEFAULT 1,
                electrical_capacity DECIMAL(10,2),
                electrical_panels INTEGER,
                backup_generator INTEGER DEFAULT 0,
                ups_systems INTEGER DEFAULT 0,
                power_outlets INTEGER,
                network_infrastructure TEXT,
                internet_connectivity TEXT,
                wifi_coverage INTEGER DEFAULT 1,
                phone_systems TEXT,
                intercom_systems TEXT,
                av_systems TEXT,
                conference_facilities INTEGER DEFAULT 0,
                water_supply TEXT,
                sewage_systems TEXT,
                plumbing_fixtures INTEGER,
                restroom_count INTEGER,
                kitchen_facilities INTEGER DEFAULT 0,
                cafeteria_capacity INTEGER DEFAULT 0,
                break_room_count INTEGER DEFAULT 0,
                meeting_room_count INTEGER DEFAULT 0,
                office_count INTEGER DEFAULT 0,
                cubicle_count INTEGER DEFAULT 0,
                workstation_count INTEGER DEFAULT 0,
                reception_areas INTEGER DEFAULT 0,
                waiting_areas INTEGER DEFAULT 0,
                storage_rooms INTEGER DEFAULT 0,
                server_rooms INTEGER DEFAULT 0,
                utility_rooms INTEGER DEFAULT 0,
                janitorial_closets INTEGER DEFAULT 0,
                maintenance_areas INTEGER DEFAULT 0,
                bike_storage INTEGER DEFAULT 0,
                locker_facilities INTEGER DEFAULT 0,
                fitness_facilities INTEGER DEFAULT 0,
                outdoor_spaces INTEGER DEFAULT 0,
                landscaping TEXT,
                signage_external TEXT,
                signage_internal TEXT,
                wayfinding_systems TEXT,
                building_materials TEXT,
                roofing_type TEXT,
                roofing_condition TEXT,
                exterior_walls TEXT,
                windows_type TEXT,
                flooring_types TEXT,
                ceiling_types TEXT,
                insulation_type TEXT,
                insulation_rating TEXT,
                energy_efficiency_rating TEXT,
                green_certifications TEXT,
                environmental_features TEXT,
                sustainability_measures TEXT,
                waste_management TEXT,
                recycling_facilities INTEGER DEFAULT 0,
                water_conservation TEXT,
                energy_conservation TEXT,
                renewable_energy TEXT,
                carbon_footprint DECIMAL(10,2),
                lease_terms TEXT,
                lease_start_date DATE,
                lease_end_date DATE,
                lease_renewal_options TEXT,
                monthly_rent DECIMAL(12,2),
                annual_rent DECIMAL(12,2),
                rent_currency TEXT DEFAULT 'USD',
                rent_escalation TEXT,
                maintenance_fee DECIMAL(10,2),
                utilities_included TEXT,
                utilities_separate TEXT,
                property_taxes DECIMAL(10,2),
                insurance_required INTEGER DEFAULT 1,
                insurance_coverage TEXT,
                tenant_improvements TEXT,
                landlord_improvements TEXT,
                maintenance_responsibilities TEXT,
                repair_responsibilities TEXT,
                compliance_requirements TEXT,
                zoning_classification TEXT,
                permitted_uses TEXT,
                occupancy_limits INTEGER,
                building_codes TEXT,
                safety_inspections TEXT,
                fire_inspections TEXT,
                elevator_inspections TEXT,
                environmental_inspections TEXT,
                last_inspection_date DATE,
                next_inspection_date DATE,
                inspection_certifications TEXT,
                violations_history TEXT,
                maintenance_schedule TEXT,
                cleaning_schedule TEXT,
                pest_control_schedule TEXT,
                landscape_maintenance TEXT,
                equipment_maintenance TEXT,
                preventive_maintenance TEXT,
                maintenance_contracts TEXT,
                service_providers TEXT,
                emergency_procedures TEXT,
                evacuation_plans TEXT,
                business_continuity TEXT,
                disaster_recovery TEXT,
                weather_protection TEXT,
                flood_protection TEXT,
                seismic_upgrades TEXT,
                historical_significance TEXT,
                architectural_heritage TEXT,
                landmark_status TEXT,
                historical_preservation TEXT,
                future_expansion_plans TEXT,
                renovation_plans TEXT,
                modernization_needs TEXT,
                technology_upgrades TEXT,
                space_optimization TEXT,
                capacity_planning TEXT,
                move_in_costs DECIMAL(10,2),
                move_out_costs DECIMAL(10,2),
                setup_requirements TEXT,
                furniture_included TEXT,
                equipment_included TEXT,
                tenant_services TEXT,
                building_amenities TEXT,
                shared_facilities TEXT,
                community_spaces TEXT,
                building_management TEXT,
                property_manager_contact TEXT,
                maintenance_contact TEXT,
                security_contact TEXT,
                emergency_contact TEXT,
                operating_hours TEXT,
                access_hours TEXT,
                holiday_schedule TEXT,
                special_events TEXT,
                building_policies TEXT,
                smoking_policy TEXT,
                pet_policy TEXT,
                visitor_policy TEXT,
                delivery_procedures TEXT,
                noise_regulations TEXT,
                photography_restrictions TEXT,
                marketing_materials TEXT,
                virtual_tour_url TEXT,
                floor_plans TEXT,
                building_photos TEXT,
                promotional_materials TEXT,
                marketing_contact TEXT,
                is_flagship INTEGER DEFAULT 0,
                is_headquarters INTEGER DEFAULT 0,
                is_leased INTEGER DEFAULT 1,
                is_owned INTEGER DEFAULT 0,
                is_available INTEGER DEFAULT 1,
                is_occupied INTEGER DEFAULT 0,
                is_under_renovation INTEGER DEFAULT 0,
                is_energy_efficient INTEGER DEFAULT 0,
                is_green_certified INTEGER DEFAULT 0,
                is_accessible INTEGER DEFAULT 1,
                is_historic INTEGER DEFAULT 0,
                is_featured INTEGER DEFAULT 0,
                is_public INTEGER DEFAULT 1,
                display_online INTEGER DEFAULT 1,
                allows_tours INTEGER DEFAULT 1,
                priority_level TEXT DEFAULT 'medium',
                condition_score DECIMAL(5,2) DEFAULT 0,
                energy_efficiency_score DECIMAL(5,2) DEFAULT 0,
                accessibility_score DECIMAL(5,2) DEFAULT 0,
                security_score DECIMAL(5,2) DEFAULT 0,
                technology_score DECIMAL(5,2) DEFAULT 0,
                occupancy_rate DECIMAL(5,2) DEFAULT 0,
                satisfaction_score DECIMAL(5,2) DEFAULT 0,
                maintenance_score DECIMAL(5,2) DEFAULT 0,
                sustainability_score DECIMAL(5,2) DEFAULT 0,
                sort_order INTEGER DEFAULT 0,
                notes TEXT,
                internal_notes TEXT,
                tags TEXT,
                meta_title TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (organization_branch_id) REFERENCES organization_branches (id) ON DELETE CASCADE,
                FOREIGN KEY (postal_address_id) REFERENCES postal_addresses (id) ON DELETE RESTRICT,
                UNIQUE(code),
                UNIQUE(slug)
            )
        ";
    }
}