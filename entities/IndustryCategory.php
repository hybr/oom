<?php

require_once __DIR__ . '/BaseEntity.php';

class IndustryCategory extends BaseEntity {
    protected $table = 'industry_categories';
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'parent_id',
        'level',
        'path',
        'sort_order',
        'icon',
        'color',
        'naics_code',
        'sic_code',
        'isic_code',
        'is_active',
        'is_featured',
        'created_at',
        'updated_at'
    ];

    public function __construct() {
        parent::__construct();
        $this->attributes['level'] = 0;
        $this->attributes['sort_order'] = 0;
        $this->attributes['is_active'] = 1;
        $this->attributes['is_featured'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // Tree Structure Methods

    public function getParent() {
        if (!$this->parent_id) return null;
        return static::find($this->parent_id);
    }

    public function getChildren() {
        return static::where('parent_id', '=', $this->id);
    }

    public function getChildrenRecursive() {
        $children = $this->getChildren();
        $allChildren = [];

        foreach ($children as $child) {
            $allChildren[] = $child;
            $grandChildren = $child->getChildrenRecursive();
            $allChildren = array_merge($allChildren, $grandChildren);
        }

        return $allChildren;
    }

    public function getAncestors() {
        $ancestors = [];
        $current = $this->getParent();

        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->getParent();
        }

        return $ancestors;
    }

    public function getDescendants() {
        return $this->getChildrenRecursive();
    }

    public function getSiblings() {
        if (!$this->parent_id) {
            return static::where('parent_id', 'IS', null);
        }

        $siblings = static::where('parent_id', '=', $this->parent_id);
        return array_filter($siblings, function($sibling) {
            return $sibling->id !== $this->id;
        });
    }

    public function getRoot() {
        $current = $this;
        while ($current->parent_id) {
            $current = $current->getParent();
        }
        return $current;
    }

    public function isRoot() {
        return $this->parent_id === null;
    }

    public function isLeaf() {
        return count($this->getChildren()) === 0;
    }

    public function hasChildren() {
        return !$this->isLeaf();
    }

    public function isChildOf($categoryId) {
        return $this->parent_id === $categoryId;
    }

    public function isDescendantOf($categoryId) {
        $ancestors = $this->getAncestors();
        foreach ($ancestors as $ancestor) {
            if ($ancestor->id === $categoryId) {
                return true;
            }
        }
        return false;
    }

    public function isAncestorOf($categoryId) {
        $category = static::find($categoryId);
        return $category ? $category->isDescendantOf($this->id) : false;
    }

    // Path and Level Management

    public function updatePath() {
        $ancestors = $this->getAncestors();
        $pathIds = array_map(function($ancestor) {
            return $ancestor->id;
        }, $ancestors);
        $pathIds[] = $this->id;

        $this->path = implode('/', $pathIds);
        $this->level = count($pathIds) - 1;
        $this->updated_at = date('Y-m-d H:i:s');

        return $this;
    }

    public function updateChildrenPaths() {
        $children = $this->getChildren();
        foreach ($children as $child) {
            $child->updatePath();
            $child->save();
            $child->updateChildrenPaths();
        }
    }

    // Slugging Methods

    public function generateSlug($name = null) {
        $name = $name ?: $this->name;
        if (!$name) return '';

        // Convert to lowercase and replace spaces/special chars with hyphens
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Make slug unique
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists($slug) {
        $existing = static::where('slug', '=', $slug);
        return count(array_filter($existing, function($item) {
            return $item->id !== $this->id;
        })) > 0;
    }

    public function setSlug($slug = null) {
        $this->slug = $slug ?: $this->generateSlug();
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // Tree Query Optimization Methods

    public static function getTreeStructure($rootId = null) {
        $categories = $rootId ? static::where('parent_id', '=', $rootId) : static::where('parent_id', 'IS', null);

        $tree = [];
        foreach ($categories as $category) {
            $categoryData = $category->toArray();
            $categoryData['children'] = static::getTreeStructure($category->id);
            $tree[] = $categoryData;
        }

        return $tree;
    }

    public static function getFlatTree($parentId = null, $level = 0) {
        $categories = $parentId ? static::where('parent_id', '=', $parentId) : static::where('parent_id', 'IS', null);
        $flatTree = [];

        foreach ($categories as $category) {
            $category->level = $level;
            $flatTree[] = $category;

            $children = static::getFlatTree($category->id, $level + 1);
            $flatTree = array_merge($flatTree, $children);
        }

        return $flatTree;
    }

    // Search and Filter Methods

    public static function findBySlug($slug) {
        $results = static::where('slug', '=', $slug);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByNaicsCode($code) {
        $results = static::where('naics_code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findBySicCode($code) {
        $results = static::where('sic_code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByIsicCode($code) {
        $results = static::where('isic_code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function getByLevel($level) {
        return static::where('level', '=', $level);
    }

    public static function getRootCategories() {
        return static::where('parent_id', 'IS', null);
    }

    public static function getLeafCategories() {
        $allCategories = static::all();
        return array_filter($allCategories, function($category) {
            return $category->isLeaf();
        });
    }

    public static function getFeaturedCategories() {
        return static::where('is_featured', '=', 1);
    }

    public static function getActiveCategories() {
        return static::where('is_active', '=', 1);
    }

    public static function searchCategories($query) {
        $categories = static::all();
        $query = strtolower($query);

        return array_filter($categories, function($category) use ($query) {
            return strpos(strtolower($category->name), $query) !== false ||
                   strpos(strtolower($category->description ?: ''), $query) !== false ||
                   strpos(strtolower($category->slug ?: ''), $query) !== false ||
                   strpos(strtolower($category->naics_code ?: ''), $query) !== false ||
                   strpos(strtolower($category->sic_code ?: ''), $query) !== false;
        });
    }

    // Display and Formatting Methods

    public function getFullName() {
        $ancestors = $this->getAncestors();
        $names = array_map(function($ancestor) {
            return $ancestor->name;
        }, $ancestors);
        $names[] = $this->name;

        return implode(' > ', $names);
    }

    public function getBreadcrumb() {
        $ancestors = $this->getAncestors();
        $ancestors[] = $this;

        return array_map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug
            ];
        }, $ancestors);
    }

    public function getIndentedName($indent = '--') {
        return str_repeat($indent, $this->level) . ' ' . $this->name;
    }

    // Status Management Methods

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

    public function isActive() {
        return $this->is_active == 1;
    }

    public function isFeatured() {
        return $this->is_featured == 1;
    }

    // Utility Methods

    public function moveTo($newParentId) {
        $this->parent_id = $newParentId;
        $this->updatePath();
        $this->updated_at = date('Y-m-d H:i:s');

        if ($this->save()) {
            $this->updateChildrenPaths();
            return true;
        }

        return false;
    }

    public function delete() {
        // Check if has children
        if ($this->hasChildren()) {
            return false; // Cannot delete category with children
        }

        return parent::delete();
    }

    public function deleteWithChildren() {
        $children = $this->getChildren();
        foreach ($children as $child) {
            $child->deleteWithChildren();
        }

        return parent::delete();
    }

    public function getStatistics() {
        $childrenCount = count($this->getChildren());
        $descendantsCount = count($this->getDescendants());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'level' => $this->level,
            'full_name' => $this->getFullName(),
            'is_root' => $this->isRoot(),
            'is_leaf' => $this->isLeaf(),
            'children_count' => $childrenCount,
            'descendants_count' => $descendantsCount,
            'is_active' => $this->isActive(),
            'is_featured' => $this->isFeatured()
        ];
    }

    // Industry Taxonomy Seeding Method
    public static function seedIndustryTaxonomy() {
        echo "Seeding comprehensive industry taxonomy...\n";

        $taxonomy = self::getComprehensiveIndustryTaxonomy();

        foreach ($taxonomy as $rootCategory) {
            self::createCategoryTree($rootCategory);
        }

        echo "Industry taxonomy seeded successfully!\n";
    }

    private static function createCategoryTree($categoryData, $parentId = null) {
        $category = new static();
        $category->name = $categoryData['name'];
        $category->description = $categoryData['description'] ?? '';
        $category->parent_id = $parentId;
        $category->naics_code = $categoryData['naics_code'] ?? '';
        $category->sic_code = $categoryData['sic_code'] ?? '';
        $category->isic_code = $categoryData['isic_code'] ?? '';
        $category->icon = $categoryData['icon'] ?? '';
        $category->color = $categoryData['color'] ?? '#6c757d';
        $category->sort_order = $categoryData['sort_order'] ?? 0;
        $category->is_featured = $categoryData['is_featured'] ?? 0;

        $category->setSlug();
        $category->updatePath();

        if ($category->save()) {
            echo "Created industry category: {$category->getFullName()}\n";

            if (isset($categoryData['children'])) {
                foreach ($categoryData['children'] as $childData) {
                    self::createCategoryTree($childData, $category->id);
                }
            }
        }
    }

    private static function getComprehensiveIndustryTaxonomy() {
        return [
            [
                'name' => 'Agriculture, Forestry, Fishing and Hunting',
                'description' => 'Industries primarily engaged in growing crops, raising animals, harvesting timber, and harvesting fish and other animals.',
                'naics_code' => '11',
                'icon' => '🌾',
                'color' => '#28a745',
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Crop Production',
                        'description' => 'Growing crops mainly for food and fiber',
                        'naics_code' => '111',
                        'children' => [
                            ['name' => 'Grain and Oilseed Farming', 'naics_code' => '1111'],
                            ['name' => 'Vegetable and Melon Farming', 'naics_code' => '1112'],
                            ['name' => 'Fruit and Tree Nut Farming', 'naics_code' => '1113'],
                            ['name' => 'Greenhouse, Nursery, and Floriculture Production', 'naics_code' => '1114']
                        ]
                    ],
                    [
                        'name' => 'Animal Production and Aquaculture',
                        'description' => 'Raising animals for food, fiber, and other products',
                        'naics_code' => '112',
                        'children' => [
                            ['name' => 'Cattle Ranching and Farming', 'naics_code' => '1121'],
                            ['name' => 'Hog and Pig Farming', 'naics_code' => '1122'],
                            ['name' => 'Poultry and Egg Production', 'naics_code' => '1123'],
                            ['name' => 'Aquaculture', 'naics_code' => '1125']
                        ]
                    ],
                    [
                        'name' => 'Forestry and Logging',
                        'description' => 'Growing and harvesting timber',
                        'naics_code' => '113'
                    ],
                    [
                        'name' => 'Fishing, Hunting and Trapping',
                        'description' => 'Catching fish and other wild animals',
                        'naics_code' => '114'
                    ]
                ]
            ],
            [
                'name' => 'Mining, Quarrying, and Oil and Gas Extraction',
                'description' => 'Industries engaged in extracting naturally occurring mineral solids, liquids, and gases.',
                'naics_code' => '21',
                'icon' => '⛏️',
                'color' => '#6f4e37',
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Oil and Gas Extraction',
                        'description' => 'Extracting crude petroleum and natural gas',
                        'naics_code' => '211'
                    ],
                    [
                        'name' => 'Mining (except Oil and Gas)',
                        'description' => 'Mining metallic and nonmetallic minerals',
                        'naics_code' => '212',
                        'children' => [
                            ['name' => 'Coal Mining', 'naics_code' => '2121'],
                            ['name' => 'Metal Ore Mining', 'naics_code' => '2122'],
                            ['name' => 'Nonmetallic Mineral Mining', 'naics_code' => '2123']
                        ]
                    ],
                    [
                        'name' => 'Support Activities for Mining',
                        'description' => 'Specialized support services for mining operations',
                        'naics_code' => '213'
                    ]
                ]
            ],
            [
                'name' => 'Utilities',
                'description' => 'Industries engaged in providing electricity, natural gas, steam, water, and sewage removal.',
                'naics_code' => '22',
                'icon' => '⚡',
                'color' => '#ffc107',
                'sort_order' => 3,
                'children' => [
                    ['name' => 'Electric Power Generation, Transmission and Distribution', 'naics_code' => '2211'],
                    ['name' => 'Natural Gas Distribution', 'naics_code' => '2212'],
                    ['name' => 'Water, Sewage and Other Systems', 'naics_code' => '2213']
                ]
            ],
            [
                'name' => 'Construction',
                'description' => 'Industries engaged in the construction of buildings and other structures, heavy construction, and specialty trade work.',
                'naics_code' => '23',
                'icon' => '🏗️',
                'color' => '#fd7e14',
                'sort_order' => 4,
                'children' => [
                    [
                        'name' => 'Construction of Buildings',
                        'description' => 'Construction of residential and nonresidential buildings',
                        'naics_code' => '236',
                        'children' => [
                            ['name' => 'Residential Building Construction', 'naics_code' => '2361'],
                            ['name' => 'Nonresidential Building Construction', 'naics_code' => '2362']
                        ]
                    ],
                    [
                        'name' => 'Heavy and Civil Engineering Construction',
                        'description' => 'Construction of infrastructure projects',
                        'naics_code' => '237'
                    ],
                    [
                        'name' => 'Specialty Trade Contractors',
                        'description' => 'Specialized construction activities',
                        'naics_code' => '238',
                        'children' => [
                            ['name' => 'Foundation, Structure, and Building Exterior Contractors', 'naics_code' => '2381'],
                            ['name' => 'Building Equipment Contractors', 'naics_code' => '2382'],
                            ['name' => 'Building Finishing Contractors', 'naics_code' => '2383']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Manufacturing',
                'description' => 'Industries engaged in the mechanical, physical, or chemical transformation of materials into new products.',
                'naics_code' => '31-33',
                'icon' => '🏭',
                'color' => '#6c757d',
                'sort_order' => 5,
                'is_featured' => 1,
                'children' => [
                    [
                        'name' => 'Food Manufacturing',
                        'description' => 'Processing food and beverages for human consumption',
                        'naics_code' => '311',
                        'children' => [
                            ['name' => 'Animal Food Manufacturing', 'naics_code' => '3111'],
                            ['name' => 'Grain and Oilseed Milling', 'naics_code' => '3112'],
                            ['name' => 'Sugar and Confectionery Product Manufacturing', 'naics_code' => '3113'],
                            ['name' => 'Fruit and Vegetable Preserving', 'naics_code' => '3114'],
                            ['name' => 'Bakeries and Tortilla Manufacturing', 'naics_code' => '3118']
                        ]
                    ],
                    [
                        'name' => 'Beverage and Tobacco Product Manufacturing',
                        'description' => 'Manufacturing beverages and tobacco products',
                        'naics_code' => '312'
                    ],
                    [
                        'name' => 'Textile and Apparel Manufacturing',
                        'description' => 'Manufacturing textiles, clothing, and related products',
                        'naics_code' => '313-315',
                        'children' => [
                            ['name' => 'Textile Mills', 'naics_code' => '313'],
                            ['name' => 'Textile Product Mills', 'naics_code' => '314'],
                            ['name' => 'Apparel Manufacturing', 'naics_code' => '315']
                        ]
                    ],
                    [
                        'name' => 'Wood Product Manufacturing',
                        'description' => 'Manufacturing lumber and other wood products',
                        'naics_code' => '321'
                    ],
                    [
                        'name' => 'Chemical Manufacturing',
                        'description' => 'Manufacturing chemicals and chemical products',
                        'naics_code' => '325',
                        'children' => [
                            ['name' => 'Basic Chemical Manufacturing', 'naics_code' => '3251'],
                            ['name' => 'Pharmaceutical Manufacturing', 'naics_code' => '3254'],
                            ['name' => 'Paint and Coating Manufacturing', 'naics_code' => '3255']
                        ]
                    ],
                    [
                        'name' => 'Computer and Electronic Product Manufacturing',
                        'description' => 'Manufacturing computers, electronics, and related products',
                        'naics_code' => '334',
                        'is_featured' => 1,
                        'children' => [
                            ['name' => 'Computer and Peripheral Equipment Manufacturing', 'naics_code' => '3341'],
                            ['name' => 'Communications Equipment Manufacturing', 'naics_code' => '3342'],
                            ['name' => 'Semiconductor Manufacturing', 'naics_code' => '3344'],
                            ['name' => 'Electronic Instrument Manufacturing', 'naics_code' => '3345']
                        ]
                    ],
                    [
                        'name' => 'Transportation Equipment Manufacturing',
                        'description' => 'Manufacturing vehicles, aircraft, ships, and related equipment',
                        'naics_code' => '336',
                        'children' => [
                            ['name' => 'Motor Vehicle Manufacturing', 'naics_code' => '3361'],
                            ['name' => 'Aerospace Product Manufacturing', 'naics_code' => '3364'],
                            ['name' => 'Ship and Boat Building', 'naics_code' => '3366']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Wholesale Trade',
                'description' => 'Industries engaged in selling goods to retailers, business users, and other wholesalers.',
                'naics_code' => '42',
                'icon' => '📦',
                'color' => '#20c997',
                'sort_order' => 6,
                'children' => [
                    [
                        'name' => 'Merchant Wholesalers, Durable Goods',
                        'description' => 'Wholesaling durable goods',
                        'naics_code' => '423',
                        'children' => [
                            ['name' => 'Motor Vehicle and Parts Wholesalers', 'naics_code' => '4231'],
                            ['name' => 'Furniture and Furnishing Wholesalers', 'naics_code' => '4232'],
                            ['name' => 'Computer Equipment Wholesalers', 'naics_code' => '4234']
                        ]
                    ],
                    [
                        'name' => 'Merchant Wholesalers, Nondurable Goods',
                        'description' => 'Wholesaling nondurable goods',
                        'naics_code' => '424'
                    ]
                ]
            ],
            [
                'name' => 'Retail Trade',
                'description' => 'Industries engaged in retailing merchandise to the general public.',
                'naics_code' => '44-45',
                'icon' => '🛍️',
                'color' => '#e83e8c',
                'sort_order' => 7,
                'is_featured' => 1,
                'children' => [
                    [
                        'name' => 'Motor Vehicle and Parts Dealers',
                        'description' => 'Selling motor vehicles and parts',
                        'naics_code' => '441'
                    ],
                    [
                        'name' => 'Furniture and Home Furnishings Stores',
                        'description' => 'Selling furniture and home furnishings',
                        'naics_code' => '442'
                    ],
                    [
                        'name' => 'Electronics and Appliance Stores',
                        'description' => 'Selling electronics and appliances',
                        'naics_code' => '443'
                    ],
                    [
                        'name' => 'Food and Beverage Stores',
                        'description' => 'Selling food and beverages for home consumption',
                        'naics_code' => '445',
                        'children' => [
                            ['name' => 'Grocery Stores', 'naics_code' => '4451'],
                            ['name' => 'Specialty Food Stores', 'naics_code' => '4452']
                        ]
                    ],
                    [
                        'name' => 'Clothing and Accessories Stores',
                        'description' => 'Selling clothing and fashion accessories',
                        'naics_code' => '448'
                    ],
                    [
                        'name' => 'General Merchandise Stores',
                        'description' => 'Selling a wide variety of goods',
                        'naics_code' => '452',
                        'children' => [
                            ['name' => 'Department Stores', 'naics_code' => '4521'],
                            ['name' => 'Warehouse Clubs and Supercenters', 'naics_code' => '4529']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Transportation and Warehousing',
                'description' => 'Industries providing transportation services and warehousing facilities.',
                'naics_code' => '48-49',
                'icon' => '🚚',
                'color' => '#007bff',
                'sort_order' => 8,
                'children' => [
                    [
                        'name' => 'Air Transportation',
                        'description' => 'Providing air transportation services',
                        'naics_code' => '481'
                    ],
                    [
                        'name' => 'Rail Transportation',
                        'description' => 'Providing rail transportation services',
                        'naics_code' => '482'
                    ],
                    [
                        'name' => 'Water Transportation',
                        'description' => 'Providing water transportation services',
                        'naics_code' => '483'
                    ],
                    [
                        'name' => 'Truck Transportation',
                        'description' => 'Providing truck transportation services',
                        'naics_code' => '484'
                    ],
                    [
                        'name' => 'Warehousing and Storage',
                        'description' => 'Providing warehousing and storage services',
                        'naics_code' => '493'
                    ]
                ]
            ],
            [
                'name' => 'Information',
                'description' => 'Industries engaged in producing and distributing information and cultural products.',
                'naics_code' => '51',
                'icon' => '📡',
                'color' => '#6f42c1',
                'sort_order' => 9,
                'is_featured' => 1,
                'children' => [
                    [
                        'name' => 'Publishing Industries',
                        'description' => 'Publishing newspapers, books, software, and other content',
                        'naics_code' => '511',
                        'children' => [
                            ['name' => 'Newspaper Publishers', 'naics_code' => '5111'],
                            ['name' => 'Book Publishers', 'naics_code' => '5111'],
                            ['name' => 'Software Publishers', 'naics_code' => '5112']
                        ]
                    ],
                    [
                        'name' => 'Motion Picture and Sound Recording',
                        'description' => 'Producing and distributing motion pictures and sound recordings',
                        'naics_code' => '512'
                    ],
                    [
                        'name' => 'Broadcasting',
                        'description' => 'Operating broadcasting stations',
                        'naics_code' => '515'
                    ],
                    [
                        'name' => 'Telecommunications',
                        'description' => 'Providing telecommunications services',
                        'naics_code' => '517',
                        'children' => [
                            ['name' => 'Wireless Telecommunications', 'naics_code' => '5172'],
                            ['name' => 'Internet Service Providers', 'naics_code' => '5181']
                        ]
                    ],
                    [
                        'name' => 'Data Processing and Hosting',
                        'description' => 'Providing data processing and hosting services',
                        'naics_code' => '518'
                    ]
                ]
            ],
            [
                'name' => 'Finance and Insurance',
                'description' => 'Industries engaged in financial transactions and insurance services.',
                'naics_code' => '52',
                'icon' => '🏦',
                'color' => '#17a2b8',
                'sort_order' => 10,
                'is_featured' => 1,
                'children' => [
                    [
                        'name' => 'Monetary Authorities - Central Bank',
                        'description' => 'Central banking activities',
                        'naics_code' => '521'
                    ],
                    [
                        'name' => 'Credit Intermediation',
                        'description' => 'Banks and other credit intermediation',
                        'naics_code' => '522',
                        'children' => [
                            ['name' => 'Commercial Banking', 'naics_code' => '5221'],
                            ['name' => 'Credit Unions', 'naics_code' => '5223'],
                            ['name' => 'Consumer Lending', 'naics_code' => '5224']
                        ]
                    ],
                    [
                        'name' => 'Securities and Investments',
                        'description' => 'Securities trading and investment services',
                        'naics_code' => '523',
                        'children' => [
                            ['name' => 'Investment Banking', 'naics_code' => '5231'],
                            ['name' => 'Securities Brokerage', 'naics_code' => '5232'],
                            ['name' => 'Investment Management', 'naics_code' => '5239']
                        ]
                    ],
                    [
                        'name' => 'Insurance Carriers',
                        'description' => 'Providing insurance coverage',
                        'naics_code' => '524',
                        'children' => [
                            ['name' => 'Life Insurance', 'naics_code' => '5241'],
                            ['name' => 'Health Insurance', 'naics_code' => '5242'],
                            ['name' => 'Property and Casualty Insurance', 'naics_code' => '5242']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Real Estate and Rental and Leasing',
                'description' => 'Industries engaged in renting, leasing, and managing real estate and other assets.',
                'naics_code' => '53',
                'icon' => '🏢',
                'color' => '#495057',
                'sort_order' => 11,
                'children' => [
                    [
                        'name' => 'Real Estate',
                        'description' => 'Buying, selling, and managing real estate',
                        'naics_code' => '531',
                        'children' => [
                            ['name' => 'Real Estate Agents and Brokers', 'naics_code' => '5312'],
                            ['name' => 'Property Management', 'naics_code' => '5313'],
                            ['name' => 'Real Estate Development', 'naics_code' => '5313']
                        ]
                    ],
                    [
                        'name' => 'Rental and Leasing Services',
                        'description' => 'Renting and leasing equipment and other assets',
                        'naics_code' => '532'
                    ]
                ]
            ],
            [
                'name' => 'Professional, Scientific, and Technical Services',
                'description' => 'Industries requiring a high degree of expertise and providing specialized services.',
                'naics_code' => '54',
                'icon' => '🔬',
                'color' => '#dc3545',
                'sort_order' => 12,
                'is_featured' => 1,
                'children' => [
                    [
                        'name' => 'Legal Services',
                        'description' => 'Providing legal advice and representation',
                        'naics_code' => '5411'
                    ],
                    [
                        'name' => 'Accounting and Auditing Services',
                        'description' => 'Providing accounting, auditing, and bookkeeping services',
                        'naics_code' => '5412'
                    ],
                    [
                        'name' => 'Architectural and Engineering Services',
                        'description' => 'Providing architectural, engineering, and related services',
                        'naics_code' => '5413',
                        'children' => [
                            ['name' => 'Architectural Services', 'naics_code' => '54131'],
                            ['name' => 'Engineering Services', 'naics_code' => '54133'],
                            ['name' => 'Surveying and Mapping', 'naics_code' => '54137']
                        ]
                    ],
                    [
                        'name' => 'Computer Systems Design',
                        'description' => 'Designing and developing computer systems and software',
                        'naics_code' => '5415',
                        'is_featured' => 1,
                        'children' => [
                            ['name' => 'Custom Software Development', 'naics_code' => '541511'],
                            ['name' => 'Computer Systems Design', 'naics_code' => '541512'],
                            ['name' => 'Web Design Services', 'naics_code' => '541511']
                        ]
                    ],
                    [
                        'name' => 'Management Consulting',
                        'description' => 'Providing management and business consulting services',
                        'naics_code' => '5416'
                    ],
                    [
                        'name' => 'Scientific Research and Development',
                        'description' => 'Conducting research and development',
                        'naics_code' => '5417'
                    ],
                    [
                        'name' => 'Advertising and Marketing',
                        'description' => 'Providing advertising, marketing, and public relations services',
                        'naics_code' => '5418',
                        'children' => [
                            ['name' => 'Advertising Agencies', 'naics_code' => '54181'],
                            ['name' => 'Digital Marketing', 'naics_code' => '54181'],
                            ['name' => 'Public Relations', 'naics_code' => '54182']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Management of Companies and Enterprises',
                'description' => 'Industries comprising establishments that hold securities or financial assets of other companies.',
                'naics_code' => '55',
                'icon' => '👔',
                'color' => '#343a40',
                'sort_order' => 13
            ],
            [
                'name' => 'Administrative and Support Services',
                'description' => 'Industries providing administrative and support services to businesses and individuals.',
                'naics_code' => '56',
                'icon' => '📋',
                'color' => '#6c757d',
                'sort_order' => 14,
                'children' => [
                    [
                        'name' => 'Office Administrative Services',
                        'description' => 'Providing administrative support services',
                        'naics_code' => '5611'
                    ],
                    [
                        'name' => 'Facilities Support Services',
                        'description' => 'Providing facilities management and support',
                        'naics_code' => '5612'
                    ],
                    [
                        'name' => 'Employment Services',
                        'description' => 'Providing employment and staffing services',
                        'naics_code' => '5613',
                        'children' => [
                            ['name' => 'Employment Placement Agencies', 'naics_code' => '56131'],
                            ['name' => 'Temporary Staffing', 'naics_code' => '56132'],
                            ['name' => 'Executive Search Services', 'naics_code' => '56131']
                        ]
                    ],
                    [
                        'name' => 'Business Support Services',
                        'description' => 'Providing various business support services',
                        'naics_code' => '5614'
                    ],
                    [
                        'name' => 'Security and Investigation Services',
                        'description' => 'Providing security and investigation services',
                        'naics_code' => '5616'
                    ],
                    [
                        'name' => 'Cleaning Services',
                        'description' => 'Providing cleaning and maintenance services',
                        'naics_code' => '5617'
                    ]
                ]
            ],
            [
                'name' => 'Educational Services',
                'description' => 'Industries providing educational instruction and services.',
                'naics_code' => '61',
                'icon' => '🎓',
                'color' => '#28a745',
                'sort_order' => 15,
                'children' => [
                    [
                        'name' => 'Elementary and Secondary Schools',
                        'description' => 'Providing elementary and secondary education',
                        'naics_code' => '6111'
                    ],
                    [
                        'name' => 'Junior Colleges',
                        'description' => 'Providing junior college education',
                        'naics_code' => '6112'
                    ],
                    [
                        'name' => 'Colleges and Universities',
                        'description' => 'Providing higher education',
                        'naics_code' => '6113'
                    ],
                    [
                        'name' => 'Professional and Technical Training',
                        'description' => 'Providing specialized professional training',
                        'naics_code' => '6115'
                    ],
                    [
                        'name' => 'Educational Support Services',
                        'description' => 'Providing support services to educational institutions',
                        'naics_code' => '6117'
                    ]
                ]
            ],
            [
                'name' => 'Health Care and Social Assistance',
                'description' => 'Industries providing health care and social assistance services.',
                'naics_code' => '62',
                'icon' => '🏥',
                'color' => '#dc3545',
                'sort_order' => 16,
                'is_featured' => 1,
                'children' => [
                    [
                        'name' => 'Ambulatory Health Care Services',
                        'description' => 'Providing outpatient health care services',
                        'naics_code' => '621',
                        'children' => [
                            ['name' => 'Offices of Physicians', 'naics_code' => '6211'],
                            ['name' => 'Offices of Dentists', 'naics_code' => '6212'],
                            ['name' => 'Medical Laboratories', 'naics_code' => '6215'],
                            ['name' => 'Outpatient Mental Health Centers', 'naics_code' => '6214']
                        ]
                    ],
                    [
                        'name' => 'Hospitals',
                        'description' => 'Operating hospitals and medical facilities',
                        'naics_code' => '622'
                    ],
                    [
                        'name' => 'Nursing and Residential Care',
                        'description' => 'Providing nursing and residential care services',
                        'naics_code' => '623'
                    ],
                    [
                        'name' => 'Social Assistance',
                        'description' => 'Providing social assistance services',
                        'naics_code' => '624',
                        'children' => [
                            ['name' => 'Individual and Family Services', 'naics_code' => '6241'],
                            ['name' => 'Community Food Services', 'naics_code' => '6242'],
                            ['name' => 'Child Day Care Services', 'naics_code' => '6244']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Arts, Entertainment, and Recreation',
                'description' => 'Industries providing arts, entertainment, and recreational services.',
                'naics_code' => '71',
                'icon' => '🎭',
                'color' => '#e83e8c',
                'sort_order' => 17,
                'children' => [
                    [
                        'name' => 'Performing Arts and Spectator Sports',
                        'description' => 'Providing performing arts and sports entertainment',
                        'naics_code' => '711',
                        'children' => [
                            ['name' => 'Theater Companies', 'naics_code' => '7111'],
                            ['name' => 'Sports Teams and Clubs', 'naics_code' => '7112'],
                            ['name' => 'Independent Artists', 'naics_code' => '7115']
                        ]
                    ],
                    [
                        'name' => 'Museums and Historical Sites',
                        'description' => 'Operating museums and historical sites',
                        'naics_code' => '712'
                    ],
                    [
                        'name' => 'Amusement and Recreation',
                        'description' => 'Providing amusement and recreational services',
                        'naics_code' => '713',
                        'children' => [
                            ['name' => 'Amusement Parks', 'naics_code' => '7131'],
                            ['name' => 'Fitness Centers', 'naics_code' => '7139'],
                            ['name' => 'Golf Courses', 'naics_code' => '7139']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Accommodation and Food Services',
                'description' => 'Industries providing accommodation and food services.',
                'naics_code' => '72',
                'icon' => '🏨',
                'color' => '#ffc107',
                'sort_order' => 18,
                'children' => [
                    [
                        'name' => 'Accommodation',
                        'description' => 'Providing lodging services',
                        'naics_code' => '721',
                        'children' => [
                            ['name' => 'Hotels and Motels', 'naics_code' => '7211'],
                            ['name' => 'Bed and Breakfast Inns', 'naics_code' => '7213'],
                            ['name' => 'Vacation Rentals', 'naics_code' => '7213']
                        ]
                    ],
                    [
                        'name' => 'Food Services and Drinking Places',
                        'description' => 'Providing food and beverage services',
                        'naics_code' => '722',
                        'children' => [
                            ['name' => 'Full-Service Restaurants', 'naics_code' => '7221'],
                            ['name' => 'Limited-Service Restaurants', 'naics_code' => '7222'],
                            ['name' => 'Special Food Services', 'naics_code' => '7223'],
                            ['name' => 'Drinking Places', 'naics_code' => '7224']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Other Services',
                'description' => 'Industries providing various other services not classified elsewhere.',
                'naics_code' => '81',
                'icon' => '🔧',
                'color' => '#6c757d',
                'sort_order' => 19,
                'children' => [
                    [
                        'name' => 'Repair and Maintenance',
                        'description' => 'Providing repair and maintenance services',
                        'naics_code' => '811',
                        'children' => [
                            ['name' => 'Automotive Repair', 'naics_code' => '8111'],
                            ['name' => 'Electronic Equipment Repair', 'naics_code' => '8112'],
                            ['name' => 'Home and Garden Equipment Repair', 'naics_code' => '8113']
                        ]
                    ],
                    [
                        'name' => 'Personal and Laundry Services',
                        'description' => 'Providing personal care and laundry services',
                        'naics_code' => '812',
                        'children' => [
                            ['name' => 'Hair and Beauty Salons', 'naics_code' => '81211'],
                            ['name' => 'Laundry and Dry Cleaning', 'naics_code' => '81232'],
                            ['name' => 'Pet Care Services', 'naics_code' => '81291']
                        ]
                    ],
                    [
                        'name' => 'Religious Organizations',
                        'description' => 'Religious, charitable, and similar organizations',
                        'naics_code' => '813'
                    ]
                ]
            ],
            [
                'name' => 'Public Administration',
                'description' => 'Industries comprising government establishments engaged in public administration.',
                'naics_code' => '92',
                'icon' => '🏛️',
                'color' => '#495057',
                'sort_order' => 20,
                'children' => [
                    [
                        'name' => 'Executive and Legislative Offices',
                        'description' => 'Executive and legislative government offices',
                        'naics_code' => '921'
                    ],
                    [
                        'name' => 'Justice, Public Order, and Safety',
                        'description' => 'Courts, police, and public safety',
                        'naics_code' => '922'
                    ],
                    [
                        'name' => 'Public Finance Activities',
                        'description' => 'Government financial and tax administration',
                        'naics_code' => '921'
                    ],
                    [
                        'name' => 'National Security',
                        'description' => 'National defense and international affairs',
                        'naics_code' => '928'
                    ]
                ]
            ]
        ];
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS industry_categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                description TEXT,
                parent_id INTEGER,
                level INTEGER DEFAULT 0,
                path TEXT,
                sort_order INTEGER DEFAULT 0,
                icon TEXT,
                color TEXT DEFAULT '#6c757d',
                naics_code TEXT,
                sic_code TEXT,
                isic_code TEXT,
                is_active INTEGER DEFAULT 1,
                is_featured INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (parent_id) REFERENCES industry_categories (id) ON DELETE CASCADE
            )
        ";
    }
}