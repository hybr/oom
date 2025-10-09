<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Organization Entity
 */
class Organization extends BaseEntity {
    protected $table = 'organizations';
    protected $fillable = [
        'short_name', 'tag_line', 'website', 'subdomain',
        'admin_id', 'industry_id', 'legal_category_id'
    ];

    /**
     * Get organization full name (including legal category)
     */
    public function getOrganizationFullName($organizationId) {
        $sql = "SELECT o.short_name, olc.name as legal_category
                FROM organizations o
                LEFT JOIN organization_legal_categories olc ON o.legal_category_id = olc.id
                WHERE o.id = ?";
        $org = $this->queryOne($sql, [$organizationId]);

        if (!$org) {
            return 'Unknown';
        }

        $name = $org['short_name'];
        if (!empty($org['legal_category'])) {
            $name .= ' (' . $org['legal_category'] . ')';
        }

        return $name;
    }

    /**
     * Get with full details
     */
    public function getWithDetails($organizationId) {
        $sql = "SELECT o.*,
                ic.name as industry_name,
                olc.name as legal_category_name,
                p.first_name as admin_first_name, p.last_name as admin_last_name,
                (SELECT COUNT(*) FROM organization_branches WHERE organization_id = o.id AND deleted_at IS NULL) as branch_count,
                (SELECT COUNT(*) FROM organization_vacancies WHERE organization_id = o.id AND deleted_at IS NULL) as vacancy_count,
                (SELECT COUNT(*) FROM seller_items WHERE organization_id = o.id AND deleted_at IS NULL) as seller_item_count
                FROM organizations o
                LEFT JOIN industry_categories ic ON o.industry_id = ic.id
                LEFT JOIN organization_legal_categories olc ON o.legal_category_id = olc.id
                LEFT JOIN persons p ON o.admin_id = p.id
                WHERE o.id = ? AND o.deleted_at IS NULL";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get admin person
     */
    public function getAdmin($organizationId) {
        $sql = "SELECT p.*, c.username
                FROM persons p
                JOIN organizations o ON p.id = o.admin_id
                LEFT JOIN credentials c ON c.person_id = p.id
                WHERE o.id = ?";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get industry category
     */
    public function getIndustry($organizationId) {
        $sql = "SELECT ic.* FROM industry_categories ic
                JOIN organizations o ON o.industry_id = ic.id
                WHERE o.id = ? AND ic.deleted_at IS NULL";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get legal category
     */
    public function getLegalCategory($organizationId) {
        $sql = "SELECT olc.* FROM organization_legal_categories olc
                JOIN organizations o ON o.legal_category_id = olc.id
                WHERE o.id = ? AND olc.deleted_at IS NULL";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get branches
     */
    public function getBranches($organizationId) {
        $sql = "SELECT * FROM organization_branches WHERE organization_id = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$organizationId]);
    }

    /**
     * Get branch count
     */
    public function getBranchCount($organizationId) {
        $sql = "SELECT COUNT(*) as count FROM organization_branches WHERE organization_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$organizationId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get all buildings across all branches
     */
    public function getBuildings($organizationId) {
        $sql = "SELECT ob.*, obr.name as branch_name
                FROM organization_buildings ob
                JOIN organization_branches obr ON ob.branch_id = obr.id
                WHERE obr.organization_id = ? AND ob.deleted_at IS NULL
                ORDER BY obr.name ASC, ob.name ASC";
        return $this->query($sql, [$organizationId]);
    }

    /**
     * Get building count
     */
    public function getBuildingCount($organizationId) {
        $sql = "SELECT COUNT(*) as count
                FROM organization_buildings ob
                JOIN organization_branches obr ON ob.branch_id = obr.id
                WHERE obr.organization_id = ? AND ob.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$organizationId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get vacancies
     */
    public function getVacancies($organizationId, $status = null) {
        $sql = "SELECT ov.*, pop.name as position_name
                FROM organization_vacancies ov
                JOIN popular_organization_positions pop ON ov.popular_position_id = pop.id
                WHERE ov.organization_id = ? AND ov.deleted_at IS NULL";

        $params = [$organizationId];

        if ($status) {
            $sql .= " AND ov.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY ov.opening_date DESC";

        return $this->query($sql, $params);
    }

    /**
     * Get vacancy count
     */
    public function getVacancyCount($organizationId, $status = null) {
        $sql = "SELECT COUNT(*) as count FROM organization_vacancies
                WHERE organization_id = ? AND deleted_at IS NULL";

        $params = [$organizationId];

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $result = $this->queryOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Get open vacancies
     */
    public function getOpenVacancies($organizationId) {
        return $this->getVacancies($organizationId, 'Open');
    }

    /**
     * Get seller items
     */
    public function getSellerItems($organizationId, $type = null, $status = 'Available') {
        $sql = "SELECT si.*, ci.name as catalog_item_name, ci.type as catalog_item_type
                FROM seller_items si
                JOIN catalog_items ci ON si.catalog_item_id = ci.id
                WHERE si.organization_id = ? AND si.availability_status = ?
                AND si.deleted_at IS NULL";

        $params = [$organizationId, $status];

        if ($type) {
            $sql .= " AND si.type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY ci.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get seller item count
     */
    public function getSellerItemCount($organizationId, $status = 'Available') {
        $sql = "SELECT COUNT(*) as count FROM seller_items
                WHERE organization_id = ? AND availability_status = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$organizationId, $status]);
        return $result['count'] ?? 0;
    }

    /**
     * Get average rating from seller item reviews
     */
    public function getAverageRating($organizationId) {
        $sql = "SELECT AVG(sir.rating) as average_rating, COUNT(sir.id) as review_count
                FROM seller_item_reviews sir
                JOIN seller_items si ON sir.seller_item_id = si.id
                WHERE si.organization_id = ? AND sir.status = 'Visible' AND sir.deleted_at IS NULL";
        return $this->queryOne($sql, [$organizationId]);
    }

    /**
     * Get organizations by industry
     */
    public function getByIndustry($industryId, $limit = 50) {
        $sql = "SELECT * FROM organizations
                WHERE industry_id = ? AND deleted_at IS NULL
                ORDER BY short_name ASC
                LIMIT ?";
        return $this->query($sql, [$industryId, $limit]);
    }

    /**
     * Get organizations by legal category
     */
    public function getByLegalCategory($legalCategoryId, $limit = 50) {
        $sql = "SELECT * FROM organizations
                WHERE legal_category_id = ? AND deleted_at IS NULL
                ORDER BY short_name ASC
                LIMIT ?";
        return $this->query($sql, [$legalCategoryId, $limit]);
    }

    /**
     * Search organizations by name
     */
    public function searchByName($term, $limit = 50) {
        $sql = "SELECT o.*,
                ic.name as industry_name,
                olc.name as legal_category_name
                FROM organizations o
                LEFT JOIN industry_categories ic ON o.industry_id = ic.id
                LEFT JOIN organization_legal_categories olc ON o.legal_category_id = olc.id
                WHERE o.short_name LIKE ? AND o.deleted_at IS NULL
                ORDER BY o.short_name ASC
                LIMIT ?";
        return $this->query($sql, ["%$term%", $limit]);
    }

    /**
     * Check if subdomain is available
     */
    public function isSubdomainAvailable($subdomain, $exceptId = null) {
        $sql = "SELECT id FROM organizations WHERE subdomain = ? AND deleted_at IS NULL";
        $params = [$subdomain];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return empty($result);
    }

    /**
     * Get organizations by subdomain
     */
    public function getBySubdomain($subdomain) {
        $sql = "SELECT * FROM organizations
                WHERE subdomain = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$subdomain]);
    }

    /**
     * Get all organizations with counts
     */
    public function getAllWithCounts($industryId = null, $limit = 50) {
        $sql = "SELECT o.*,
                ic.name as industry_name,
                olc.name as legal_category_name,
                COUNT(DISTINCT obr.id) as branch_count,
                COUNT(DISTINCT ov.id) as vacancy_count,
                COUNT(DISTINCT si.id) as seller_item_count
                FROM organizations o
                LEFT JOIN industry_categories ic ON o.industry_id = ic.id
                LEFT JOIN organization_legal_categories olc ON o.legal_category_id = olc.id
                LEFT JOIN organization_branches obr ON obr.organization_id = o.id AND obr.deleted_at IS NULL
                LEFT JOIN organization_vacancies ov ON ov.organization_id = o.id AND ov.deleted_at IS NULL
                LEFT JOIN seller_items si ON si.organization_id = o.id AND si.deleted_at IS NULL
                WHERE o.deleted_at IS NULL";

        $params = [];

        if ($industryId) {
            $sql .= " AND o.industry_id = ?";
            $params[] = $industryId;
        }

        $sql .= " GROUP BY o.id ORDER BY o.short_name ASC LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    /**
     * Get statistics
     */
    public function getStatistics($industryId = null) {
        $sql = "SELECT
                    COUNT(DISTINCT o.id) as total_organizations,
                    COUNT(DISTINCT obr.id) as total_branches,
                    COUNT(DISTINCT ov.id) as total_vacancies,
                    COUNT(DISTINCT si.id) as total_seller_items,
                    AVG(branch_counts.count) as avg_branches_per_org
                FROM organizations o
                LEFT JOIN organization_branches obr ON obr.organization_id = o.id AND obr.deleted_at IS NULL
                LEFT JOIN organization_vacancies ov ON ov.organization_id = o.id AND ov.deleted_at IS NULL
                LEFT JOIN seller_items si ON si.organization_id = o.id AND si.deleted_at IS NULL
                LEFT JOIN (
                    SELECT organization_id, COUNT(*) as count
                    FROM organization_branches
                    WHERE deleted_at IS NULL
                    GROUP BY organization_id
                ) branch_counts ON o.id = branch_counts.organization_id
                WHERE o.deleted_at IS NULL";

        $params = [];

        if ($industryId) {
            $sql .= " AND o.industry_id = ?";
            $params[] = $industryId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate organization data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'short_name' => 'required|min:2|max:200',
            'tag_line' => 'max:500',
            'website' => 'url',
            'subdomain' => 'required|min:3|max:50' . ($id ? "|unique:organizations,subdomain,$id" : '|unique:organizations,subdomain'),
            'admin_id' => 'required|integer',
            'legal_category_id' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel to return organization full name
     */
    public function getLabel($id) {
        return $this->getOrganizationFullName($id);
    }
}
