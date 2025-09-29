<?php

require_once __DIR__ . '/BaseEntity.php';

class OrganizationBranch extends BaseEntity {
    protected $table = 'organization_branches';
    protected $fillable = [
        'id',
        'organization_id',
        'name',
        'code',
        'slug',
        'description',
        'branch_type',
        'status',
        'verification_status',
        'opening_date',
        'closure_date',
        'manager_person_id',
        'assistant_manager_person_id',
        'employee_count',
        'monthly_revenue',
        'monthly_target',
        'revenue_currency',
        'operating_hours',
        'time_zone',
        'phone',
        'email',
        'fax',
        'website_url',
        'social_media',
        'languages_spoken',
        'services_offered',
        'specializations',
        'facilities',
        'accessibility_features',
        'parking_available',
        'public_transport_access',
        'delivery_services',
        'pickup_services',
        'appointment_required',
        'walk_ins_welcome',
        'online_services',
        'payment_methods',
        'loyalty_programs',
        'promotions_active',
        'seasonal_hours',
        'holiday_schedule',
        'emergency_contact',
        'safety_protocols',
        'health_protocols',
        'environmental_certifications',
        'awards_recognition',
        'customer_reviews_enabled',
        'booking_system_enabled',
        'inventory_system_enabled',
        'pos_system_type',
        'security_features',
        'insurance_details',
        'license_numbers',
        'compliance_certifications',
        'inspection_dates',
        'maintenance_schedule',
        'utilities_info',
        'lease_details',
        'rent_amount',
        'lease_expiry',
        'property_owner_contact',
        'floor_area',
        'storage_area',
        'seating_capacity',
        'meeting_rooms',
        'equipment_list',
        'technology_features',
        'wifi_available',
        'backup_power',
        'is_headquarters',
        'is_flagship',
        'is_franchise',
        'is_temporary',
        'is_seasonal',
        'is_under_renovation',
        'is_featured',
        'is_public',
        'allows_reviews',
        'display_online',
        'sort_order',
        'priority_level',
        'performance_score',
        'customer_satisfaction',
        'staff_satisfaction',
        'operational_efficiency',
        'cost_efficiency',
        'profit_margin',
        'growth_rate',
        'market_share',
        'competition_analysis',
        'swot_analysis',
        'risk_assessment',
        'improvement_plans',
        'expansion_plans',
        'marketing_budget',
        'training_budget',
        'maintenance_budget',
        'notes',
        'internal_notes',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_at',
        'updated_at'
    ];

    // Branch types
    const TYPE_MAIN = 'main';
    const TYPE_REGIONAL = 'regional';
    const TYPE_FLAGSHIP = 'flagship';
    const TYPE_OUTLET = 'outlet';
    const TYPE_KIOSK = 'kiosk';
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_DISTRIBUTION = 'distribution';
    const TYPE_OFFICE = 'office';
    const TYPE_SHOWROOM = 'showroom';
    const TYPE_SERVICE_CENTER = 'service_center';
    const TYPE_FRANCHISE = 'franchise';
    const TYPE_POPUP = 'popup';
    const TYPE_MOBILE = 'mobile';
    const TYPE_VIRTUAL = 'virtual';

    // Branch statuses
    const STATUS_PLANNING = 'planning';
    const STATUS_UNDER_CONSTRUCTION = 'under_construction';
    const STATUS_OPENING_SOON = 'opening_soon';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_TEMPORARILY_CLOSED = 'temporarily_closed';
    const STATUS_UNDER_RENOVATION = 'under_renovation';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CLOSING = 'closing';
    const STATUS_CLOSED = 'closed';
    const STATUS_ARCHIVED = 'archived';

    // Verification statuses
    const VERIFICATION_PENDING = 'pending';
    const VERIFICATION_IN_REVIEW = 'in_review';
    const VERIFICATION_VERIFIED = 'verified';
    const VERIFICATION_REJECTED = 'rejected';
    const VERIFICATION_EXPIRED = 'expired';
    const VERIFICATION_SUSPENDED = 'suspended';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    // Employee count ranges
    const EMPLOYEES_1_5 = '1-5';
    const EMPLOYEES_6_10 = '6-10';
    const EMPLOYEES_11_25 = '11-25';
    const EMPLOYEES_26_50 = '26-50';
    const EMPLOYEES_51_100 = '51-100';
    const EMPLOYEES_101_200 = '101-200';
    const EMPLOYEES_201_PLUS = '201+';

    public function __construct() {
        parent::__construct();
        $this->attributes['branch_type'] = self::TYPE_OUTLET;
        $this->attributes['status'] = self::STATUS_PLANNING;
        $this->attributes['verification_status'] = self::VERIFICATION_PENDING;
        $this->attributes['employee_count'] = self::EMPLOYEES_1_5;
        $this->attributes['revenue_currency'] = 'USD';
        $this->attributes['priority_level'] = self::PRIORITY_MEDIUM;
        $this->attributes['is_headquarters'] = 0;
        $this->attributes['is_flagship'] = 0;
        $this->attributes['is_franchise'] = 0;
        $this->attributes['is_temporary'] = 0;
        $this->attributes['is_seasonal'] = 0;
        $this->attributes['is_under_renovation'] = 0;
        $this->attributes['is_featured'] = 0;
        $this->attributes['is_public'] = 1;
        $this->attributes['allows_reviews'] = 1;
        $this->attributes['display_online'] = 1;
        $this->attributes['parking_available'] = 0;
        $this->attributes['appointment_required'] = 0;
        $this->attributes['walk_ins_welcome'] = 1;
        $this->attributes['customer_reviews_enabled'] = 1;
        $this->attributes['booking_system_enabled'] = 0;
        $this->attributes['inventory_system_enabled'] = 0;
        $this->attributes['wifi_available'] = 1;
        $this->attributes['backup_power'] = 0;
        $this->attributes['sort_order'] = 0;
        $this->attributes['performance_score'] = 0;
        $this->attributes['customer_satisfaction'] = 0;
        $this->attributes['staff_satisfaction'] = 0;
        $this->attributes['operational_efficiency'] = 0;
        $this->attributes['cost_efficiency'] = 0;
        $this->attributes['profit_margin'] = 0;
        $this->attributes['growth_rate'] = 0;
        $this->attributes['market_share'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // Relationship with Organization entity (parent organization)
    public function getOrganization() {
        if (!$this->organization_id) return null;

        require_once __DIR__ . '/Organization.php';
        return Organization::find($this->organization_id);
    }

    public function getOrganizationName() {
        $org = $this->getOrganization();
        return $org ? $org->getDisplayName() : 'Unknown Organization';
    }

    // Relationship with Person entities (managers)
    public function getManager() {
        if (!$this->manager_person_id) return null;

        require_once __DIR__ . '/Person.php';
        return Person::find($this->manager_person_id);
    }

    public function getManagerName() {
        $manager = $this->getManager();
        return $manager ? $manager->getFullName() : 'No Manager Assigned';
    }

    public function getAssistantManager() {
        if (!$this->assistant_manager_person_id) return null;

        require_once __DIR__ . '/Person.php';
        return Person::find($this->assistant_manager_person_id);
    }

    public function getAssistantManagerName() {
        $assistant = $this->getAssistantManager();
        return $assistant ? $assistant->getFullName() : 'No Assistant Manager';
    }

    // Branch identification and naming
    public function getDisplayName() {
        if ($this->name) {
            return $this->name;
        }

        $org = $this->getOrganization();
        $orgName = $org ? $org->getDisplayName() : 'Organization';

        if ($this->code) {
            return $orgName . ' - ' . $this->code;
        }

        return $orgName . ' Branch #' . $this->id;
    }

    public function getFullName() {
        $org = $this->getOrganization();
        $orgName = $org ? $org->getDisplayName() : 'Organization';

        if ($this->name) {
            return $orgName . ' - ' . $this->name;
        }

        if ($this->code) {
            return $orgName . ' - ' . $this->code;
        }

        return $orgName . ' Branch #' . $this->id;
    }

    public function generateCode($code = null) {
        if ($code) {
            // Validate custom code
            if (!$this->isCodeAvailable($code)) {
                throw new Exception('Branch code already exists');
            }
            return strtoupper($code);
        }

        // Auto-generate code
        $org = $this->getOrganization();
        if (!$org) {
            throw new Exception('Organization required to generate branch code');
        }

        $orgPrefix = substr(strtoupper($org->name ?: 'ORG'), 0, 3);
        $branchType = strtoupper(substr($this->branch_type, 0, 2));

        // Find next available number
        $counter = 1;
        do {
            $code = $orgPrefix . $branchType . sprintf('%03d', $counter);
            $counter++;
        } while (!$this->isCodeAvailable($code) && $counter <= 999);

        if ($counter > 999) {
            throw new Exception('Unable to generate unique branch code');
        }

        return $code;
    }

    private function isCodeAvailable($code) {
        $existing = static::where('code', '=', $code);

        // If editing, exclude current branch
        if ($this->id) {
            $existing = array_filter($existing, function($branch) {
                return $branch->id !== $this->id;
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

        // If editing, exclude current branch
        if ($this->id) {
            $existing = array_filter($existing, function($branch) {
                return $branch->id !== $this->id;
            });
        }

        return !empty($existing);
    }

    // Business logic methods
    public function isActive() {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isVerified() {
        return $this->verification_status === self::VERIFICATION_VERIFIED;
    }

    public function isHeadquarters() {
        return $this->is_headquarters == 1;
    }

    public function isFlagship() {
        return $this->is_flagship == 1;
    }

    public function isFranchise() {
        return $this->is_franchise == 1;
    }

    public function isTemporary() {
        return $this->is_temporary == 1;
    }

    public function isSeasonal() {
        return $this->is_seasonal == 1;
    }

    public function isUnderRenovation() {
        return $this->is_under_renovation == 1;
    }

    public function isFeatured() {
        return $this->is_featured == 1;
    }

    public function isPublic() {
        return $this->is_public == 1;
    }

    public function allowsReviews() {
        return $this->allows_reviews == 1;
    }

    public function isDisplayedOnline() {
        return $this->display_online == 1;
    }

    public function hasParking() {
        return $this->parking_available == 1;
    }

    public function requiresAppointment() {
        return $this->appointment_required == 1;
    }

    public function acceptsWalkIns() {
        return $this->walk_ins_welcome == 1;
    }

    public function hasWifi() {
        return $this->wifi_available == 1;
    }

    public function hasBackupPower() {
        return $this->backup_power == 1;
    }

    // Status management
    public function activate() {
        $this->status = self::STATUS_ACTIVE;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function deactivate() {
        $this->status = self::STATUS_INACTIVE;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function suspend() {
        $this->status = self::STATUS_SUSPENDED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function temporarilyClose() {
        $this->status = self::STATUS_TEMPORARILY_CLOSED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function permanentlyClose() {
        $this->status = self::STATUS_CLOSED;
        $this->closure_date = date('Y-m-d');
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function archive() {
        $this->status = self::STATUS_ARCHIVED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Verification management
    public function verify() {
        $this->verification_status = self::VERIFICATION_VERIFIED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function rejectVerification() {
        $this->verification_status = self::VERIFICATION_REJECTED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function suspendVerification() {
        $this->verification_status = self::VERIFICATION_SUSPENDED;
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

    // JSON field handlers
    public function getSocialMediaArray() {
        if (!$this->social_media) return [];
        return json_decode($this->social_media, true) ?: [];
    }

    public function setSocialMedia($socialMediaData) {
        $this->social_media = json_encode($socialMediaData);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getServicesOfferedArray() {
        if (!$this->services_offered) return [];
        return explode(',', $this->services_offered);
    }

    public function setServicesOffered($servicesArray) {
        $this->services_offered = is_array($servicesArray) ? implode(',', $servicesArray) : $servicesArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getSpecializationsArray() {
        if (!$this->specializations) return [];
        return explode(',', $this->specializations);
    }

    public function setSpecializations($specializationsArray) {
        $this->specializations = is_array($specializationsArray) ? implode(',', $specializationsArray) : $specializationsArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getFacilitiesArray() {
        if (!$this->facilities) return [];
        return explode(',', $this->facilities);
    }

    public function setFacilities($facilitiesArray) {
        $this->facilities = is_array($facilitiesArray) ? implode(',', $facilitiesArray) : $facilitiesArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getPaymentMethodsArray() {
        if (!$this->payment_methods) return [];
        return explode(',', $this->payment_methods);
    }

    public function setPaymentMethods($paymentMethodsArray) {
        $this->payment_methods = is_array($paymentMethodsArray) ? implode(',', $paymentMethodsArray) : $paymentMethodsArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // Financial calculations
    public function getFormattedRevenue() {
        if (!$this->monthly_revenue) return 'Not disclosed';

        $currency = $this->revenue_currency ?: 'USD';
        $amount = floatval($this->monthly_revenue);

        if ($amount >= 1000000) {
            return $currency . ' ' . number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return $currency . ' ' . number_format($amount / 1000, 1) . 'K';
        } else {
            return $currency . ' ' . number_format($amount);
        }
    }

    public function getFormattedTarget() {
        if (!$this->monthly_target) return 'No target set';

        $currency = $this->revenue_currency ?: 'USD';
        $amount = floatval($this->monthly_target);

        if ($amount >= 1000000) {
            return $currency . ' ' . number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return $currency . ' ' . number_format($amount / 1000, 1) . 'K';
        } else {
            return $currency . ' ' . number_format($amount);
        }
    }

    public function getTargetAchievementPercentage() {
        if (!$this->monthly_target || !$this->monthly_revenue) return 0;
        return round(($this->monthly_revenue / $this->monthly_target) * 100, 2);
    }

    // Age calculation
    public function getAge() {
        if (!$this->opening_date) return null;

        $openingDate = new DateTime($this->opening_date);
        $today = new DateTime();
        return $today->diff($openingDate);
    }

    public function getAgeDescription() {
        $age = $this->getAge();
        if (!$age) return 'Unknown';

        $years = $age->y;
        $months = $age->m;

        if ($years > 0) {
            return $years . ' year' . ($years > 1 ? 's' : '');
        } elseif ($months > 0) {
            return $months . ' month' . ($months > 1 ? 's' : '');
        } else {
            return 'Less than a month';
        }
    }

    // Performance metrics
    public function updatePerformanceScore($score) {
        $this->performance_score = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateCustomerSatisfaction($score) {
        $this->customer_satisfaction = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateStaffSatisfaction($score) {
        $this->staff_satisfaction = max(0, min(100, floatval($score)));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Search and query methods
    public static function findByOrganization($organizationId) {
        return static::where('organization_id', '=', $organizationId);
    }

    public static function findByType($branchType) {
        return static::where('branch_type', '=', $branchType);
    }

    public static function findByStatus($status) {
        return static::where('status', '=', $status);
    }

    public static function findByManager($managerId) {
        return static::where('manager_person_id', '=', $managerId);
    }

    public static function findByCode($code) {
        $results = static::where('code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findBySlug($slug) {
        $results = static::where('slug', '=', $slug);
        return !empty($results) ? $results[0] : null;
    }

    public static function findActive() {
        return static::where('status', '=', self::STATUS_ACTIVE);
    }

    public static function findVerified() {
        return static::where('verification_status', '=', self::VERIFICATION_VERIFIED);
    }

    public static function findHeadquarters() {
        return static::where('is_headquarters', '=', 1);
    }

    public static function findFlagship() {
        return static::where('is_flagship', '=', 1);
    }

    public static function findFeatured() {
        return static::where('is_featured', '=', 1);
    }

    public static function findPublic() {
        return static::where('is_public', '=', 1);
    }

    public static function findByPriorityLevel($priority) {
        return static::where('priority_level', '=', $priority);
    }

    public static function searchBranches($query) {
        $branches = static::all();
        $query = strtolower($query);

        return array_filter($branches, function($branch) use ($query) {
            return strpos(strtolower($branch->name ?: ''), $query) !== false ||
                   strpos(strtolower($branch->code ?: ''), $query) !== false ||
                   strpos(strtolower($branch->description ?: ''), $query) !== false ||
                   strpos(strtolower($branch->getOrganizationName()), $query) !== false ||
                   strpos(strtolower($branch->services_offered ?: ''), $query) !== false ||
                   strpos(strtolower($branch->specializations ?: ''), $query) !== false;
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
            'organization' => $this->getOrganizationName(),
            'type' => $this->branch_type,
            'status' => $this->status,
            'verification_status' => $this->verification_status,
            'manager' => $this->getManagerName(),
            'employee_count' => $this->employee_count,
            'revenue' => $this->getFormattedRevenue(),
            'target' => $this->getFormattedTarget(),
            'target_achievement' => $this->getTargetAchievementPercentage() . '%',
            'age' => $this->getAgeDescription(),
            'performance_score' => $this->performance_score,
            'customer_satisfaction' => $this->customer_satisfaction,
            'is_headquarters' => $this->isHeadquarters(),
            'is_flagship' => $this->isFlagship(),
            'is_featured' => $this->isFeatured(),
            'is_public' => $this->isPublic(),
            'created_at' => $this->created_at
        ];
    }

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['display_name'] = $this->getDisplayName();
        $data['full_name'] = $this->getFullName();
        $data['organization_name'] = $this->getOrganizationName();
        $data['manager_name'] = $this->getManagerName();
        $data['assistant_manager_name'] = $this->getAssistantManagerName();
        $data['formatted_revenue'] = $this->getFormattedRevenue();
        $data['formatted_target'] = $this->getFormattedTarget();
        $data['target_achievement_percentage'] = $this->getTargetAchievementPercentage();
        $data['age_description'] = $this->getAgeDescription();
        $data['social_media_array'] = $this->getSocialMediaArray();
        $data['services_offered_array'] = $this->getServicesOfferedArray();
        $data['specializations_array'] = $this->getSpecializationsArray();
        $data['facilities_array'] = $this->getFacilitiesArray();
        $data['payment_methods_array'] = $this->getPaymentMethodsArray();

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS organization_branches (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                organization_id INTEGER NOT NULL,
                name TEXT,
                code TEXT UNIQUE,
                slug TEXT UNIQUE,
                description TEXT,
                branch_type TEXT DEFAULT 'outlet',
                status TEXT DEFAULT 'planning',
                verification_status TEXT DEFAULT 'pending',
                opening_date DATE,
                closure_date DATE,
                manager_person_id INTEGER,
                assistant_manager_person_id INTEGER,
                employee_count TEXT DEFAULT '1-5',
                monthly_revenue DECIMAL(15,2),
                monthly_target DECIMAL(15,2),
                revenue_currency TEXT DEFAULT 'USD',
                operating_hours TEXT,
                time_zone TEXT,
                phone TEXT,
                email TEXT,
                fax TEXT,
                website_url TEXT,
                social_media TEXT,
                languages_spoken TEXT,
                services_offered TEXT,
                specializations TEXT,
                facilities TEXT,
                accessibility_features TEXT,
                parking_available INTEGER DEFAULT 0,
                public_transport_access TEXT,
                delivery_services TEXT,
                pickup_services TEXT,
                appointment_required INTEGER DEFAULT 0,
                walk_ins_welcome INTEGER DEFAULT 1,
                online_services TEXT,
                payment_methods TEXT,
                loyalty_programs TEXT,
                promotions_active TEXT,
                seasonal_hours TEXT,
                holiday_schedule TEXT,
                emergency_contact TEXT,
                safety_protocols TEXT,
                health_protocols TEXT,
                environmental_certifications TEXT,
                awards_recognition TEXT,
                customer_reviews_enabled INTEGER DEFAULT 1,
                booking_system_enabled INTEGER DEFAULT 0,
                inventory_system_enabled INTEGER DEFAULT 0,
                pos_system_type TEXT,
                security_features TEXT,
                insurance_details TEXT,
                license_numbers TEXT,
                compliance_certifications TEXT,
                inspection_dates TEXT,
                maintenance_schedule TEXT,
                utilities_info TEXT,
                lease_details TEXT,
                rent_amount DECIMAL(10,2),
                lease_expiry DATE,
                property_owner_contact TEXT,
                floor_area DECIMAL(10,2),
                storage_area DECIMAL(10,2),
                seating_capacity INTEGER,
                meeting_rooms INTEGER,
                equipment_list TEXT,
                technology_features TEXT,
                wifi_available INTEGER DEFAULT 1,
                backup_power INTEGER DEFAULT 0,
                is_headquarters INTEGER DEFAULT 0,
                is_flagship INTEGER DEFAULT 0,
                is_franchise INTEGER DEFAULT 0,
                is_temporary INTEGER DEFAULT 0,
                is_seasonal INTEGER DEFAULT 0,
                is_under_renovation INTEGER DEFAULT 0,
                is_featured INTEGER DEFAULT 0,
                is_public INTEGER DEFAULT 1,
                allows_reviews INTEGER DEFAULT 1,
                display_online INTEGER DEFAULT 1,
                sort_order INTEGER DEFAULT 0,
                priority_level TEXT DEFAULT 'medium',
                performance_score DECIMAL(5,2) DEFAULT 0,
                customer_satisfaction DECIMAL(5,2) DEFAULT 0,
                staff_satisfaction DECIMAL(5,2) DEFAULT 0,
                operational_efficiency DECIMAL(5,2) DEFAULT 0,
                cost_efficiency DECIMAL(5,2) DEFAULT 0,
                profit_margin DECIMAL(5,2) DEFAULT 0,
                growth_rate DECIMAL(5,2) DEFAULT 0,
                market_share DECIMAL(5,2) DEFAULT 0,
                competition_analysis TEXT,
                swot_analysis TEXT,
                risk_assessment TEXT,
                improvement_plans TEXT,
                expansion_plans TEXT,
                marketing_budget DECIMAL(10,2),
                training_budget DECIMAL(10,2),
                maintenance_budget DECIMAL(10,2),
                notes TEXT,
                internal_notes TEXT,
                meta_title TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE,
                FOREIGN KEY (manager_person_id) REFERENCES persons (id) ON DELETE SET NULL,
                FOREIGN KEY (assistant_manager_person_id) REFERENCES persons (id) ON DELETE SET NULL,
                UNIQUE(code),
                UNIQUE(slug)
            )
        ";
    }
}