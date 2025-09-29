<?php

require_once __DIR__ . '/BaseEntity.php';

class Organization extends BaseEntity {
    protected $table = 'organizations';
    protected $fillable = [
        'id',
        'name',
        'legal_name',
        'slug',
        'description',
        'tagline',
        'industry_category_id',
        'organization_legal_type_id',
        'subdomain',
        'website_url',
        'admin_person_id',
        'founded_date',
        'employee_count',
        'annual_revenue',
        'revenue_currency',
        'business_model',
        'status',
        'verification_status',
        'tax_id',
        'registration_number',
        'phone',
        'email',
        'logo_url',
        'banner_url',
        'social_media',
        'operating_hours',
        'languages',
        'certifications',
        'awards',
        'specializations',
        'target_markets',
        'mission_statement',
        'vision_statement',
        'organization_values',
        'sustainability_practices',
        'is_featured',
        'is_public',
        'is_hiring',
        'allows_reviews',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_at',
        'updated_at'
    ];

    // Organization statuses
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_ARCHIVED = 'archived';

    // Verification statuses
    const VERIFICATION_PENDING = 'pending';
    const VERIFICATION_VERIFIED = 'verified';
    const VERIFICATION_REJECTED = 'rejected';
    const VERIFICATION_EXPIRED = 'expired';

    // Business models
    const BUSINESS_B2B = 'b2b';
    const BUSINESS_B2C = 'b2c';
    const BUSINESS_B2B2C = 'b2b2c';
    const BUSINESS_C2C = 'c2c';
    const BUSINESS_NONPROFIT = 'nonprofit';
    const BUSINESS_GOVERNMENT = 'government';
    const BUSINESS_COOPERATIVE = 'cooperative';

    // Employee count ranges
    const EMPLOYEES_1_10 = '1-10';
    const EMPLOYEES_11_50 = '11-50';
    const EMPLOYEES_51_200 = '51-200';
    const EMPLOYEES_201_500 = '201-500';
    const EMPLOYEES_501_1000 = '501-1000';
    const EMPLOYEES_1001_5000 = '1001-5000';
    const EMPLOYEES_5001_PLUS = '5001+';

    public function __construct() {
        parent::__construct();
        $this->attributes['status'] = self::STATUS_DRAFT;
        $this->attributes['verification_status'] = self::VERIFICATION_PENDING;
        $this->attributes['business_model'] = self::BUSINESS_B2C;
        $this->attributes['employee_count'] = self::EMPLOYEES_1_10;
        $this->attributes['is_featured'] = 0;
        $this->attributes['is_public'] = 1;
        $this->attributes['is_hiring'] = 0;
        $this->attributes['allows_reviews'] = 1;
        $this->attributes['sort_order'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // Relationship with IndustryCategory entity
    public function getIndustryCategory() {
        if (!$this->industry_category_id) return null;

        require_once __DIR__ . '/IndustryCategory.php';
        return IndustryCategory::find($this->industry_category_id);
    }

    public function getIndustryCategoryName() {
        $category = $this->getIndustryCategory();
        return $category ? $category->name : 'Uncategorized';
    }

    public function getIndustryCategoryPath() {
        $category = $this->getIndustryCategory();
        return $category ? $category->path : '';
    }

    // Relationship with OrganizationLegalType entity
    public function getLegalType() {
        if (!$this->legal_type_id) return null;

        require_once __DIR__ . '/OrganizationLegalType.php';
        return OrganizationLegalType::find($this->legal_type_id);
    }

    public function getLegalTypeName() {
        $legalType = $this->getLegalType();
        return $legalType ? $legalType->name : 'Unknown';
    }

    public function getLegalTypeDisplayName() {
        $legalType = $this->getLegalType();
        return $legalType ? $legalType->getDisplayName() : 'Unknown';
    }

    // Relationship with Person entity (Admin)
    public function getAdmin() {
        if (!$this->admin_person_id) return null;

        require_once __DIR__ . '/Person.php';
        return Person::find($this->admin_person_id);
    }

    public function getAdminName() {
        $admin = $this->getAdmin();
        return $admin ? $admin->getFullName() : 'No Admin';
    }

    public function getAdminEmail() {
        $admin = $this->getAdmin();
        return $admin ? $admin->email : null;
    }

    // Subdomain Management
    public function getFullSubdomain() {
        return $this->subdomain ? $this->subdomain . '.v4l.app' : null;
    }

    public function getSubdomainUrl() {
        return $this->subdomain ? 'https://' . $this->subdomain . '.v4l.app' : null;
    }

    public function validateSubdomain($subdomain) {
        // Basic validation rules
        if (strlen($subdomain) < 3 || strlen($subdomain) > 30) {
            return false;
        }

        // Only allow alphanumeric characters and hyphens
        if (!preg_match('/^[a-z0-9-]+$/', $subdomain)) {
            return false;
        }

        // Cannot start or end with hyphen
        if (str_starts_with($subdomain, '-') || str_ends_with($subdomain, '-')) {
            return false;
        }

        // Cannot have consecutive hyphens
        if (strpos($subdomain, '--') !== false) {
            return false;
        }

        // Reserved subdomains
        $reserved = ['www', 'api', 'admin', 'app', 'mail', 'ftp', 'test', 'staging', 'dev', 'beta'];
        if (in_array($subdomain, $reserved)) {
            return false;
        }

        return true;
    }

    public function isSubdomainAvailable($subdomain) {
        if (!$this->validateSubdomain($subdomain)) {
            return false;
        }

        $existing = static::where('subdomain', '=', $subdomain);

        // If editing, exclude current organization
        if ($this->id) {
            $existing = array_filter($existing, function($org) {
                return $org->id !== $this->id;
            });
        }

        return empty($existing);
    }

    // Business Logic Methods
    public function getDisplayName() {
        return $this->name ?: $this->legal_name;
    }

    public function getShortDescription($maxLength = 150) {
        if (!$this->description) return '';

        if (strlen($this->description) <= $maxLength) {
            return $this->description;
        }

        return substr($this->description, 0, $maxLength - 3) . '...';
    }

    public function isActive() {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isVerified() {
        return $this->verification_status === self::VERIFICATION_VERIFIED;
    }

    public function isFeatured() {
        return $this->is_featured == 1;
    }

    public function isPublic() {
        return $this->is_public == 1;
    }

    public function isHiring() {
        return $this->is_hiring == 1;
    }

    public function allowsReviews() {
        return $this->allows_reviews == 1;
    }

    // Status Management
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

    public function archive() {
        $this->status = self::STATUS_ARCHIVED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Verification Management
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

    public function expireVerification() {
        $this->verification_status = self::VERIFICATION_EXPIRED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Feature Management
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

    public function makePublic() {
        $this->is_public = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function makePrivate() {
        $this->is_public = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function enableHiring() {
        $this->is_hiring = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function disableHiring() {
        $this->is_hiring = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Social Media and URLs
    public function getSocialMediaArray() {
        if (!$this->social_media) return [];
        return json_decode($this->social_media, true) ?: [];
    }

    public function setSocialMedia($socialMediaData) {
        $this->social_media = json_encode($socialMediaData);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getLanguagesArray() {
        if (!$this->languages) return [];
        return json_decode($this->languages, true) ?: [];
    }

    public function setLanguages($languagesData) {
        $this->languages = json_encode($languagesData);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getCertificationsArray() {
        if (!$this->certifications) return [];
        return json_decode($this->certifications, true) ?: [];
    }

    public function setCertifications($certificationsData) {
        $this->certifications = json_encode($certificationsData);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getAwardsArray() {
        if (!$this->awards) return [];
        return json_decode($this->awards, true) ?: [];
    }

    public function setAwards($awardsData) {
        $this->awards = json_encode($awardsData);
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

    public function getTargetMarketsArray() {
        if (!$this->target_markets) return [];
        return explode(',', $this->target_markets);
    }

    public function setTargetMarkets($marketsArray) {
        $this->target_markets = is_array($marketsArray) ? implode(',', $marketsArray) : $marketsArray;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // Slug Management
    public function generateSlug($name = null) {
        $name = $name ?: $this->name;
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

        // If editing, exclude current organization
        if ($this->id) {
            $existing = array_filter($existing, function($org) {
                return $org->id !== $this->id;
            });
        }

        return !empty($existing);
    }

    // Revenue formatting
    public function getFormattedRevenue() {
        if (!$this->annual_revenue) return 'Not disclosed';

        $currency = $this->revenue_currency ?: 'USD';
        $amount = floatval($this->annual_revenue);

        if ($amount >= 1000000000) {
            return $currency . ' ' . number_format($amount / 1000000000, 1) . 'B';
        } elseif ($amount >= 1000000) {
            return $currency . ' ' . number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return $currency . ' ' . number_format($amount / 1000, 1) . 'K';
        } else {
            return $currency . ' ' . number_format($amount);
        }
    }

    // Age calculation
    public function getAge() {
        if (!$this->founded_date) return null;

        $foundedDate = new DateTime($this->founded_date);
        $today = new DateTime();
        return $today->diff($foundedDate)->y;
    }

    public function getAgeDescription() {
        $age = $this->getAge();
        if (!$age) return 'Unknown';

        if ($age < 1) return 'Less than a year';
        if ($age == 1) return '1 year';
        return $age . ' years';
    }

    // Search and Query Methods
    public static function findBySlug($slug) {
        $results = static::where('slug', '=', $slug);
        return !empty($results) ? $results[0] : null;
    }

    public static function findBySubdomain($subdomain) {
        $results = static::where('subdomain', '=', $subdomain);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByIndustryCategory($industryId) {
        return static::where('industry_category_id', '=', $industryId);
    }

    public static function findByLegalType($legalTypeId) {
        return static::where('legal_type_id', '=', $legalTypeId);
    }

    public static function findByAdmin($adminId) {
        return static::where('admin_person_id', '=', $adminId);
    }

    public static function findByStatus($status) {
        return static::where('status', '=', $status);
    }

    public static function findVerified() {
        return static::where('verification_status', '=', self::VERIFICATION_VERIFIED);
    }

    public static function findFeatured() {
        return static::where('is_featured', '=', 1);
    }

    public static function findPublic() {
        return static::where('is_public', '=', 1);
    }

    public static function findHiring() {
        return static::where('is_hiring', '=', 1);
    }

    public static function findByBusinessModel($businessModel) {
        return static::where('business_model', '=', $businessModel);
    }

    public static function findByEmployeeCount($employeeCount) {
        return static::where('employee_count', '=', $employeeCount);
    }

    public static function searchOrganizations($query) {
        $organizations = static::all();
        $query = strtolower($query);

        return array_filter($organizations, function($org) use ($query) {
            return strpos(strtolower($org->name ?: ''), $query) !== false ||
                   strpos(strtolower($org->legal_name ?: ''), $query) !== false ||
                   strpos(strtolower($org->description ?: ''), $query) !== false ||
                   strpos(strtolower($org->tagline ?: ''), $query) !== false ||
                   strpos(strtolower($org->specializations ?: ''), $query) !== false;
        });
    }

    // Utility Methods
    public function getStatistics() {
        return [
            'id' => $this->id,
            'name' => $this->getDisplayName(),
            'slug' => $this->slug,
            'subdomain' => $this->getFullSubdomain(),
            'industry' => $this->getIndustryCategoryName(),
            'legal_type' => $this->getLegalTypeName(),
            'admin' => $this->getAdminName(),
            'status' => $this->status,
            'verification_status' => $this->verification_status,
            'business_model' => $this->business_model,
            'employee_count' => $this->employee_count,
            'revenue' => $this->getFormattedRevenue(),
            'age' => $this->getAgeDescription(),
            'is_featured' => $this->isFeatured(),
            'is_public' => $this->isPublic(),
            'is_hiring' => $this->isHiring(),
            'website' => $this->website_url,
            'created_at' => $this->created_at
        ];
    }

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['display_name'] = $this->getDisplayName();
        $data['industry_name'] = $this->getIndustryCategoryName();
        $data['legal_type_name'] = $this->getLegalTypeName();
        $data['admin_name'] = $this->getAdminName();
        $data['full_subdomain'] = $this->getFullSubdomain();
        $data['subdomain_url'] = $this->getSubdomainUrl();
        $data['formatted_revenue'] = $this->getFormattedRevenue();
        $data['age_description'] = $this->getAgeDescription();
        $data['social_media_array'] = $this->getSocialMediaArray();
        $data['languages_array'] = $this->getLanguagesArray();
        $data['specializations_array'] = $this->getSpecializationsArray();
        $data['target_markets_array'] = $this->getTargetMarketsArray();

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS organizations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                legal_name TEXT,
                slug TEXT UNIQUE,
                description TEXT,
                tagline TEXT,
                industry_category_id INTEGER,
                organization_legal_type_id INTEGER,
                subdomain TEXT UNIQUE,
                website_url TEXT,
                admin_person_id INTEGER NOT NULL,
                founded_date DATE,
                employee_count TEXT DEFAULT '1-10',
                annual_revenue DECIMAL(15,2),
                revenue_currency TEXT DEFAULT 'USD',
                business_model TEXT DEFAULT 'b2c',
                status TEXT DEFAULT 'draft',
                verification_status TEXT DEFAULT 'pending',
                tax_id TEXT,
                registration_number TEXT,
                phone TEXT,
                email TEXT,
                logo_url TEXT,
                banner_url TEXT,
                social_media TEXT,
                operating_hours TEXT,
                languages TEXT,
                certifications TEXT,
                awards TEXT,
                specializations TEXT,
                target_markets TEXT,
                mission_statement TEXT,
                vision_statement TEXT,
                organization_values TEXT,
                sustainability_practices TEXT,
                is_featured INTEGER DEFAULT 0,
                is_public INTEGER DEFAULT 1,
                is_hiring INTEGER DEFAULT 0,
                allows_reviews INTEGER DEFAULT 1,
                sort_order INTEGER DEFAULT 0,
                meta_title TEXT,
                meta_description TEXT,
                meta_keywords TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (industry_category_id) REFERENCES industry_categories (id) ON DELETE SET NULL,
                FOREIGN KEY (organization_legal_type_id) REFERENCES organization_legal_types (id) ON DELETE SET NULL,
                FOREIGN KEY (admin_person_id) REFERENCES persons (id) ON DELETE RESTRICT,
                UNIQUE(subdomain),
                UNIQUE(slug)
            )
        ";
    }
}