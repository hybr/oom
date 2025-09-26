<?php

require_once __DIR__ . '/BaseEntity.php';

class OrganizationLegalType extends BaseEntity {
    protected $table = 'organization_legal_types';
    protected $fillable = [
        'id',
        'name',
        'abbreviation',
        'description',
        'country_id',
        'jurisdiction',
        'category',
        'min_shareholders',
        'max_shareholders',
        'min_directors',
        'max_directors',
        'min_capital_required',
        'currency_code',
        'liability_type',
        'tax_structure',
        'reporting_requirements',
        'formation_time_days',
        'formation_cost_range',
        'annual_compliance_requirements',
        'legal_code',
        'is_public_company',
        'allows_foreign_ownership',
        'foreign_ownership_limit',
        'requires_local_director',
        'requires_company_secretary',
        'requires_registered_office',
        'allows_single_director',
        'allows_nominee_directors',
        'share_capital_requirements',
        'governance_requirements',
        'dissolution_requirements',
        'regulatory_authority',
        'legal_framework',
        'advantages',
        'disadvantages',
        'common_usage',
        'examples',
        'sort_order',
        'is_active',
        'is_commonly_used',
        'created_at',
        'updated_at'
    ];

    // Legal entity categories
    const CATEGORY_CORPORATION = 'corporation';
    const CATEGORY_LLC = 'llc';
    const CATEGORY_PARTNERSHIP = 'partnership';
    const CATEGORY_SOLE_PROPRIETORSHIP = 'sole_proprietorship';
    const CATEGORY_COOPERATIVE = 'cooperative';
    const CATEGORY_NONPROFIT = 'nonprofit';
    const CATEGORY_TRUST = 'trust';
    const CATEGORY_JOINT_VENTURE = 'joint_venture';
    const CATEGORY_BRANCH_OFFICE = 'branch_office';
    const CATEGORY_REPRESENTATIVE_OFFICE = 'representative_office';
    const CATEGORY_OTHER = 'other';

    // Liability types
    const LIABILITY_LIMITED = 'limited';
    const LIABILITY_UNLIMITED = 'unlimited';
    const LIABILITY_MIXED = 'mixed';

    // Tax structures
    const TAX_CORPORATE = 'corporate';
    const TAX_PASS_THROUGH = 'pass_through';
    const TAX_HYBRID = 'hybrid';
    const TAX_EXEMPT = 'exempt';

    public function __construct() {
        parent::__construct();
        $this->attributes['min_shareholders'] = 1;
        $this->attributes['max_shareholders'] = null;
        $this->attributes['min_directors'] = 1;
        $this->attributes['max_directors'] = null;
        $this->attributes['min_capital_required'] = 0;
        $this->attributes['liability_type'] = self::LIABILITY_LIMITED;
        $this->attributes['tax_structure'] = self::TAX_CORPORATE;
        $this->attributes['formation_time_days'] = 0;
        $this->attributes['foreign_ownership_limit'] = 100;
        $this->attributes['is_public_company'] = 0;
        $this->attributes['allows_foreign_ownership'] = 1;
        $this->attributes['requires_local_director'] = 0;
        $this->attributes['requires_company_secretary'] = 0;
        $this->attributes['requires_registered_office'] = 1;
        $this->attributes['allows_single_director'] = 1;
        $this->attributes['allows_nominee_directors'] = 1;
        $this->attributes['sort_order'] = 0;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_commonly_used'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // Relationship with Country entity
    public function getCountry() {
        if (!$this->country_id) return null;

        require_once __DIR__ . '/Country.php';
        return Country::find($this->country_id);
    }

    public function getCountryName() {
        $country = $this->getCountry();
        return $country ? $country->name : 'Unknown';
    }

    public function getCountryCode() {
        $country = $this->getCountry();
        return $country ? $country->iso_alpha_2 : null;
    }

    // Business Logic Methods
    public function getFullName() {
        if ($this->abbreviation) {
            return "{$this->name} ({$this->abbreviation})";
        }
        return $this->name;
    }

    public function getDisplayName() {
        $countryName = $this->getCountryName();
        if ($countryName && $countryName !== 'Unknown') {
            return "{$this->getFullName()} - {$countryName}";
        }
        return $this->getFullName();
    }

    public function isPublicCompany() {
        return $this->is_public_company == 1;
    }

    public function isPrivateCompany() {
        return $this->is_public_company == 0;
    }

    public function allowsForeignOwnership() {
        return $this->allows_foreign_ownership == 1;
    }

    public function requiresLocalDirector() {
        return $this->requires_local_director == 1;
    }

    public function requiresCompanySecretary() {
        return $this->requires_company_secretary == 1;
    }

    public function requiresRegisteredOffice() {
        return $this->requires_registered_office == 1;
    }

    public function allowsSingleDirector() {
        return $this->allows_single_director == 1;
    }

    public function allowsNomineeDirectors() {
        return $this->allows_nominee_directors == 1;
    }

    public function isActive() {
        return $this->is_active == 1;
    }

    public function isCommonlyUsed() {
        return $this->is_commonly_used == 1;
    }

    // Capital and Share Requirements
    public function hasMinimumCapitalRequirement() {
        return $this->min_capital_required > 0;
    }

    public function getFormattedMinCapital() {
        if (!$this->hasMinimumCapitalRequirement()) {
            return 'No minimum capital required';
        }

        $currency = $this->currency_code ?: 'USD';
        return $currency . ' ' . number_format($this->min_capital_required);
    }

    public function getShareholderRange() {
        if ($this->max_shareholders === null) {
            return "Minimum {$this->min_shareholders} shareholder(s), no maximum";
        }

        if ($this->min_shareholders === $this->max_shareholders) {
            return "Exactly {$this->min_shareholders} shareholder(s)";
        }

        return "Between {$this->min_shareholders} and {$this->max_shareholders} shareholders";
    }

    public function getDirectorRange() {
        if ($this->max_directors === null) {
            return "Minimum {$this->min_directors} director(s), no maximum";
        }

        if ($this->min_directors === $this->max_directors) {
            return "Exactly {$this->min_directors} director(s)";
        }

        return "Between {$this->min_directors} and {$this->max_directors} directors";
    }

    // Formation and Compliance
    public function getFormationTimeDescription() {
        if ($this->formation_time_days <= 0) {
            return 'Formation time varies';
        }

        if ($this->formation_time_days == 1) {
            return '1 day';
        }

        if ($this->formation_time_days <= 7) {
            return "{$this->formation_time_days} days (1 week)";
        }

        if ($this->formation_time_days <= 30) {
            $weeks = ceil($this->formation_time_days / 7);
            return "{$this->formation_time_days} days ({$weeks} weeks)";
        }

        $months = ceil($this->formation_time_days / 30);
        return "{$this->formation_time_days} days ({$months} months)";
    }

    public function getForeignOwnershipDescription() {
        if (!$this->allowsForeignOwnership()) {
            return 'Foreign ownership not allowed';
        }

        if ($this->foreign_ownership_limit >= 100) {
            return '100% foreign ownership allowed';
        }

        return "Up to {$this->foreign_ownership_limit}% foreign ownership allowed";
    }

    // Status Management
    public function activate() {
        $this->is_active = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function deactivate() {
        $this->is_active = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsCommonlyUsed() {
        $this->is_commonly_used = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function markAsNotCommonlyUsed() {
        $this->is_commonly_used = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Query Methods
    public static function findByCountry($countryId) {
        return static::where('country_id', '=', $countryId);
    }

    public static function findByCountryCode($countryCode) {
        require_once __DIR__ . '/Country.php';
        $country = Country::findByISO2($countryCode);

        if (!$country) return [];

        return static::findByCountry($country->id);
    }

    public static function findByCategory($category) {
        return static::where('category', '=', $category);
    }

    public static function findByLiabilityType($liabilityType) {
        return static::where('liability_type', '=', $liabilityType);
    }

    public static function findByTaxStructure($taxStructure) {
        return static::where('tax_structure', '=', $taxStructure);
    }

    public static function findPublicCompanyTypes() {
        return static::where('is_public_company', '=', 1);
    }

    public static function findPrivateCompanyTypes() {
        return static::where('is_public_company', '=', 0);
    }

    public static function findCommonlyUsed() {
        return static::where('is_commonly_used', '=', 1);
    }

    public static function findAllowingForeignOwnership() {
        return static::where('allows_foreign_ownership', '=', 1);
    }

    public static function findRequiringMinimumCapital() {
        $allTypes = static::all();
        return array_filter($allTypes, function($type) {
            return $type->hasMinimumCapitalRequirement();
        });
    }

    public static function findByJurisdiction($jurisdiction) {
        return static::where('jurisdiction', '=', $jurisdiction);
    }

    public static function searchLegalTypes($query) {
        $types = static::all();
        $query = strtolower($query);

        return array_filter($types, function($type) use ($query) {
            return strpos(strtolower($type->name), $query) !== false ||
                   strpos(strtolower($type->abbreviation ?: ''), $query) !== false ||
                   strpos(strtolower($type->description ?: ''), $query) !== false ||
                   strpos(strtolower($type->category ?: ''), $query) !== false ||
                   strpos(strtolower($type->jurisdiction ?: ''), $query) !== false ||
                   strpos(strtolower($type->getCountryName()), $query) !== false;
        });
    }

    // Utility Methods
    public function getRequirementsArray() {
        $requirements = [];

        if ($this->requiresLocalDirector()) {
            $requirements[] = 'Local director required';
        }

        if ($this->requiresCompanySecretary()) {
            $requirements[] = 'Company secretary required';
        }

        if ($this->requiresRegisteredOffice()) {
            $requirements[] = 'Registered office required';
        }

        if ($this->hasMinimumCapitalRequirement()) {
            $requirements[] = 'Minimum capital: ' . $this->getFormattedMinCapital();
        }

        if (!$this->allowsForeignOwnership()) {
            $requirements[] = 'Local ownership only';
        } elseif ($this->foreign_ownership_limit < 100) {
            $requirements[] = "Foreign ownership up to {$this->foreign_ownership_limit}%";
        }

        return $requirements;
    }

    public function getAdvantagesArray() {
        if (!$this->advantages) return [];
        return array_map('trim', explode("\n", $this->advantages));
    }

    public function getDisadvantagesArray() {
        if (!$this->disadvantages) return [];
        return array_map('trim', explode("\n", $this->disadvantages));
    }

    public function getExamplesArray() {
        if (!$this->examples) return [];
        return array_map('trim', explode(',', $this->examples));
    }

    public function getStatistics() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'full_name' => $this->getFullName(),
            'display_name' => $this->getDisplayName(),
            'country' => $this->getCountryName(),
            'category' => $this->category,
            'liability_type' => $this->liability_type,
            'tax_structure' => $this->tax_structure,
            'shareholder_range' => $this->getShareholderRange(),
            'director_range' => $this->getDirectorRange(),
            'formation_time' => $this->getFormationTimeDescription(),
            'min_capital' => $this->getFormattedMinCapital(),
            'foreign_ownership' => $this->getForeignOwnershipDescription(),
            'is_public' => $this->isPublicCompany(),
            'is_active' => $this->isActive(),
            'is_commonly_used' => $this->isCommonlyUsed(),
            'requirements' => $this->getRequirementsArray()
        ];
    }

    // Seed Method for Legal Types
    public static function seedOrganizationLegalTypes() {
        echo "Seeding organization legal types...\n";

        $legalTypes = self::getComprehensiveLegalTypes();

        foreach ($legalTypes as $countryData) {
            $countryName = $countryData['country'];

            // Find country by name
            require_once __DIR__ . '/Country.php';
            $country = Country::findByName($countryName);

            if (!$country) {
                echo "Warning: Country '{$countryName}' not found, skipping legal types...\n";
                continue;
            }

            foreach ($countryData['legal_types'] as $typeData) {
                $legalType = new static();
                $legalType->name = $typeData['name'];
                $legalType->abbreviation = $typeData['abbreviation'] ?? '';
                $legalType->description = $typeData['description'] ?? '';
                $legalType->country_id = $country->id;
                $legalType->jurisdiction = $typeData['jurisdiction'] ?? $countryName;
                $legalType->category = $typeData['category'] ?? self::CATEGORY_CORPORATION;
                $legalType->min_shareholders = $typeData['min_shareholders'] ?? 1;
                $legalType->max_shareholders = $typeData['max_shareholders'] ?? null;
                $legalType->min_directors = $typeData['min_directors'] ?? 1;
                $legalType->max_directors = $typeData['max_directors'] ?? null;
                $legalType->min_capital_required = $typeData['min_capital_required'] ?? 0;
                $legalType->currency_code = $typeData['currency_code'] ?? $country->currency_code ?? 'USD';
                $legalType->liability_type = $typeData['liability_type'] ?? self::LIABILITY_LIMITED;
                $legalType->tax_structure = $typeData['tax_structure'] ?? self::TAX_CORPORATE;
                $legalType->reporting_requirements = $typeData['reporting_requirements'] ?? '';
                $legalType->formation_time_days = $typeData['formation_time_days'] ?? 0;
                $legalType->formation_cost_range = $typeData['formation_cost_range'] ?? '';
                $legalType->annual_compliance_requirements = $typeData['annual_compliance_requirements'] ?? '';
                $legalType->legal_code = $typeData['legal_code'] ?? '';
                $legalType->is_public_company = $typeData['is_public_company'] ?? 0;
                $legalType->allows_foreign_ownership = $typeData['allows_foreign_ownership'] ?? 1;
                $legalType->foreign_ownership_limit = $typeData['foreign_ownership_limit'] ?? 100;
                $legalType->requires_local_director = $typeData['requires_local_director'] ?? 0;
                $legalType->requires_company_secretary = $typeData['requires_company_secretary'] ?? 0;
                $legalType->requires_registered_office = $typeData['requires_registered_office'] ?? 1;
                $legalType->allows_single_director = $typeData['allows_single_director'] ?? 1;
                $legalType->allows_nominee_directors = $typeData['allows_nominee_directors'] ?? 1;
                $legalType->share_capital_requirements = $typeData['share_capital_requirements'] ?? '';
                $legalType->governance_requirements = $typeData['governance_requirements'] ?? '';
                $legalType->dissolution_requirements = $typeData['dissolution_requirements'] ?? '';
                $legalType->regulatory_authority = $typeData['regulatory_authority'] ?? '';
                $legalType->legal_framework = $typeData['legal_framework'] ?? '';
                $legalType->advantages = $typeData['advantages'] ?? '';
                $legalType->disadvantages = $typeData['disadvantages'] ?? '';
                $legalType->common_usage = $typeData['common_usage'] ?? '';
                $legalType->examples = $typeData['examples'] ?? '';
                $legalType->sort_order = $typeData['sort_order'] ?? 0;
                $legalType->is_commonly_used = $typeData['is_commonly_used'] ?? 0;

                if ($legalType->save()) {
                    echo "Created legal type: {$legalType->getDisplayName()}\n";
                }
            }
        }

        echo "Organization legal types seeded successfully!\n";
    }

    private static function getComprehensiveLegalTypes() {
        return [
            [
                'country' => 'United States',
                'legal_types' => [
                    [
                        'name' => 'Corporation',
                        'abbreviation' => 'Corp',
                        'description' => 'A legal entity separate from its owners, providing limited liability protection',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'min_directors' => 1,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 7,
                        'formation_cost_range' => '$300-$1000',
                        'allows_foreign_ownership' => 1,
                        'requires_registered_office' => 1,
                        'advantages' => "Limited liability protection\nSeparate legal entity\nContinuous existence\nEasy transfer of ownership",
                        'disadvantages' => "Double taxation\nMore paperwork and regulations\nCorporate formalities required",
                        'common_usage' => 'Large businesses seeking to raise capital through stock issuance',
                        'examples' => 'Apple Inc., Microsoft Corporation, General Motors Corp',
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Limited Liability Company',
                        'abbreviation' => 'LLC',
                        'description' => 'A business structure that combines the limited liability of a corporation with the tax benefits of a partnership',
                        'category' => self::CATEGORY_LLC,
                        'min_shareholders' => 1,
                        'min_directors' => 1,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 5,
                        'formation_cost_range' => '$100-$500',
                        'allows_foreign_ownership' => 1,
                        'advantages' => "Limited liability protection\nPass-through taxation\nFlexible management structure\nFewer formalities",
                        'disadvantages' => "Limited life in some states\nSelf-employment taxes on profits\nLess established legal precedent",
                        'common_usage' => 'Small to medium-sized businesses, professional services',
                        'examples' => 'Many private businesses, professional practices',
                        'is_commonly_used' => 1,
                        'sort_order' => 2
                    ],
                    [
                        'name' => 'S Corporation',
                        'abbreviation' => 'S Corp',
                        'description' => 'A corporation that elects to pass corporate income, losses, deductions, and credits through to shareholders',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'max_shareholders' => 100,
                        'min_directors' => 1,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 7,
                        'allows_foreign_ownership' => 0,
                        'common_usage' => 'Small businesses wanting corporate protection with pass-through taxation',
                        'is_commonly_used' => 1,
                        'sort_order' => 3
                    ],
                    [
                        'name' => 'General Partnership',
                        'abbreviation' => 'GP',
                        'description' => 'A business owned by two or more people who share management and profits',
                        'category' => self::CATEGORY_PARTNERSHIP,
                        'min_shareholders' => 2,
                        'liability_type' => self::LIABILITY_UNLIMITED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 1,
                        'formation_cost_range' => '$0-$200',
                        'common_usage' => 'Small businesses with multiple owners who want simple structure',
                        'sort_order' => 4
                    ],
                    [
                        'name' => 'Limited Partnership',
                        'abbreviation' => 'LP',
                        'description' => 'A partnership with both general and limited partners, where limited partners have limited liability',
                        'category' => self::CATEGORY_PARTNERSHIP,
                        'min_shareholders' => 2,
                        'liability_type' => self::LIABILITY_MIXED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 3,
                        'common_usage' => 'Investment funds, real estate ventures',
                        'sort_order' => 5
                    ]
                ]
            ],
            [
                'country' => 'India',
                'legal_types' => [
                    [
                        'name' => 'Private Limited Company',
                        'abbreviation' => 'Pvt Ltd',
                        'description' => 'A company with limited liability whose shares are not offered to the general public',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 2,
                        'max_shareholders' => 200,
                        'min_directors' => 2,
                        'min_capital_required' => 100000,
                        'currency_code' => 'INR',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 15,
                        'formation_cost_range' => '₹15,000-₹25,000',
                        'allows_foreign_ownership' => 1,
                        'requires_company_secretary' => 1,
                        'regulatory_authority' => 'Ministry of Corporate Affairs (MCA)',
                        'advantages' => "Limited liability\nPerpetual succession\nEasy transfer of shares\nCredibility with banks and investors",
                        'disadvantages' => "Complex compliance requirements\nHigher formation cost\nRestricted share transfer",
                        'common_usage' => 'Startups, small to medium enterprises, family businesses',
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Public Limited Company',
                        'abbreviation' => 'Ltd',
                        'description' => 'A company that can offer its shares to the general public and can be listed on stock exchanges',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 7,
                        'min_directors' => 3,
                        'min_capital_required' => 500000,
                        'currency_code' => 'INR',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 30,
                        'is_public_company' => 1,
                        'allows_foreign_ownership' => 1,
                        'requires_company_secretary' => 1,
                        'common_usage' => 'Large businesses planning to raise capital from public',
                        'is_commonly_used' => 1,
                        'sort_order' => 2
                    ],
                    [
                        'name' => 'Limited Liability Partnership',
                        'abbreviation' => 'LLP',
                        'description' => 'A partnership where partners have limited liability and are not responsible for misconduct of other partners',
                        'category' => self::CATEGORY_PARTNERSHIP,
                        'min_shareholders' => 2,
                        'min_directors' => 2,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 10,
                        'formation_cost_range' => '₹5,000-₹10,000',
                        'allows_foreign_ownership' => 1,
                        'common_usage' => 'Professional services, consulting firms',
                        'is_commonly_used' => 1,
                        'sort_order' => 3
                    ],
                    [
                        'name' => 'One Person Company',
                        'abbreviation' => 'OPC',
                        'description' => 'A company with only one member, providing corporate benefits to sole proprietorships',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'max_shareholders' => 1,
                        'min_directors' => 1,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 10,
                        'allows_foreign_ownership' => 0,
                        'common_usage' => 'Solo entrepreneurs wanting corporate structure',
                        'sort_order' => 4
                    ]
                ]
            ],
            [
                'country' => 'United Kingdom',
                'legal_types' => [
                    [
                        'name' => 'Private Company Limited by Shares',
                        'abbreviation' => 'Ltd',
                        'description' => 'A company where shareholders\' liability is limited to the amount unpaid on shares',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'min_directors' => 1,
                        'min_capital_required' => 100,
                        'currency_code' => 'GBP',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 1,
                        'formation_cost_range' => '£12-£100',
                        'allows_foreign_ownership' => 1,
                        'regulatory_authority' => 'Companies House',
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Public Limited Company',
                        'abbreviation' => 'PLC',
                        'description' => 'A company that can offer shares to the public and be listed on stock exchanges',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 2,
                        'min_directors' => 2,
                        'min_capital_required' => 50000,
                        'currency_code' => 'GBP',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 7,
                        'is_public_company' => 1,
                        'allows_foreign_ownership' => 1,
                        'requires_company_secretary' => 1,
                        'is_commonly_used' => 1,
                        'sort_order' => 2
                    ],
                    [
                        'name' => 'Limited Liability Partnership',
                        'abbreviation' => 'LLP',
                        'description' => 'A partnership with limited liability for partners',
                        'category' => self::CATEGORY_PARTNERSHIP,
                        'min_shareholders' => 2,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 3,
                        'allows_foreign_ownership' => 1,
                        'common_usage' => 'Professional services, law firms, accounting firms',
                        'is_commonly_used' => 1,
                        'sort_order' => 3
                    ]
                ]
            ],
            [
                'country' => 'Singapore',
                'legal_types' => [
                    [
                        'name' => 'Private Limited Company',
                        'abbreviation' => 'Pte Ltd',
                        'description' => 'A company limited by shares with restricted share transfers',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'max_shareholders' => 50,
                        'min_directors' => 1,
                        'min_capital_required' => 1,
                        'currency_code' => 'SGD',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 1,
                        'formation_cost_range' => 'S$315-S$1000',
                        'allows_foreign_ownership' => 1,
                        'requires_local_director' => 1,
                        'regulatory_authority' => 'Accounting and Corporate Regulatory Authority (ACRA)',
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Public Company Limited by Shares',
                        'abbreviation' => 'Ltd',
                        'description' => 'A company that can offer shares to the public',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'min_directors' => 2,
                        'min_capital_required' => 1,
                        'currency_code' => 'SGD',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 3,
                        'is_public_company' => 1,
                        'allows_foreign_ownership' => 1,
                        'requires_local_director' => 1,
                        'requires_company_secretary' => 1,
                        'sort_order' => 2
                    ],
                    [
                        'name' => 'Limited Liability Partnership',
                        'abbreviation' => 'LLP',
                        'description' => 'A partnership with limited liability protection',
                        'category' => self::CATEGORY_PARTNERSHIP,
                        'min_shareholders' => 2,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 2,
                        'allows_foreign_ownership' => 1,
                        'requires_local_director' => 1,
                        'sort_order' => 3
                    ]
                ]
            ],
            [
                'country' => 'Germany',
                'legal_types' => [
                    [
                        'name' => 'Gesellschaft mit beschränkter Haftung',
                        'abbreviation' => 'GmbH',
                        'description' => 'A limited liability company popular for small to medium enterprises',
                        'category' => self::CATEGORY_LLC,
                        'min_shareholders' => 1,
                        'min_directors' => 1,
                        'min_capital_required' => 25000,
                        'currency_code' => 'EUR',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 14,
                        'formation_cost_range' => '€600-€1500',
                        'allows_foreign_ownership' => 1,
                        'common_usage' => 'Small to medium enterprises',
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Aktiengesellschaft',
                        'abbreviation' => 'AG',
                        'description' => 'A stock corporation suitable for large businesses',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'min_directors' => 3,
                        'min_capital_required' => 50000,
                        'currency_code' => 'EUR',
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 30,
                        'is_public_company' => 1,
                        'allows_foreign_ownership' => 1,
                        'common_usage' => 'Large corporations, publicly traded companies',
                        'is_commonly_used' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'country' => 'Canada',
                'legal_types' => [
                    [
                        'name' => 'Corporation',
                        'abbreviation' => 'Corp',
                        'description' => 'A legal entity separate from its shareholders',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'min_directors' => 1,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 5,
                        'formation_cost_range' => 'CAD $200-$500',
                        'allows_foreign_ownership' => 1,
                        'requires_local_director' => 1,
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Limited Partnership',
                        'abbreviation' => 'LP',
                        'description' => 'A partnership with general and limited partners',
                        'category' => self::CATEGORY_PARTNERSHIP,
                        'min_shareholders' => 2,
                        'liability_type' => self::LIABILITY_MIXED,
                        'tax_structure' => self::TAX_PASS_THROUGH,
                        'formation_time_days' => 3,
                        'allows_foreign_ownership' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'country' => 'Australia',
                'legal_types' => [
                    [
                        'name' => 'Proprietary Limited Company',
                        'abbreviation' => 'Pty Ltd',
                        'description' => 'A private company with limited liability',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'max_shareholders' => 50,
                        'min_directors' => 1,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 1,
                        'formation_cost_range' => 'AUD $500-$1500',
                        'allows_foreign_ownership' => 1,
                        'regulatory_authority' => 'Australian Securities and Investments Commission (ASIC)',
                        'is_commonly_used' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'name' => 'Public Company Limited by Shares',
                        'abbreviation' => 'Ltd',
                        'description' => 'A public company that can raise funds from the public',
                        'category' => self::CATEGORY_CORPORATION,
                        'min_shareholders' => 1,
                        'min_directors' => 3,
                        'liability_type' => self::LIABILITY_LIMITED,
                        'tax_structure' => self::TAX_CORPORATE,
                        'formation_time_days' => 5,
                        'is_public_company' => 1,
                        'allows_foreign_ownership' => 1,
                        'requires_company_secretary' => 1,
                        'sort_order' => 2
                    ]
                ]
            ]
        ];
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS organization_legal_types (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                abbreviation TEXT,
                description TEXT,
                country_id INTEGER NOT NULL,
                jurisdiction TEXT,
                category TEXT DEFAULT 'corporation',
                min_shareholders INTEGER DEFAULT 1,
                max_shareholders INTEGER,
                min_directors INTEGER DEFAULT 1,
                max_directors INTEGER,
                min_capital_required INTEGER DEFAULT 0,
                currency_code TEXT DEFAULT 'USD',
                liability_type TEXT DEFAULT 'limited',
                tax_structure TEXT DEFAULT 'corporate',
                reporting_requirements TEXT,
                formation_time_days INTEGER DEFAULT 0,
                formation_cost_range TEXT,
                annual_compliance_requirements TEXT,
                legal_code TEXT,
                is_public_company INTEGER DEFAULT 0,
                allows_foreign_ownership INTEGER DEFAULT 1,
                foreign_ownership_limit INTEGER DEFAULT 100,
                requires_local_director INTEGER DEFAULT 0,
                requires_company_secretary INTEGER DEFAULT 0,
                requires_registered_office INTEGER DEFAULT 1,
                allows_single_director INTEGER DEFAULT 1,
                allows_nominee_directors INTEGER DEFAULT 1,
                share_capital_requirements TEXT,
                governance_requirements TEXT,
                dissolution_requirements TEXT,
                regulatory_authority TEXT,
                legal_framework TEXT,
                advantages TEXT,
                disadvantages TEXT,
                common_usage TEXT,
                examples TEXT,
                sort_order INTEGER DEFAULT 0,
                is_active INTEGER DEFAULT 1,
                is_commonly_used INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (country_id) REFERENCES countries (id) ON DELETE RESTRICT
            )
        ";
    }
}