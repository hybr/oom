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
     * Get branches
     */
    public function getBranches($organizationId) {
        $sql = "SELECT * FROM organization_branches WHERE organization_id = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$organizationId]);
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
     * Get seller items
     */
    public function getSellerItems($organizationId) {
        $sql = "SELECT si.*, ci.name as catalog_item_name, ci.type
                FROM seller_items si
                JOIN catalog_items ci ON si.catalog_item_id = ci.id
                WHERE si.organization_id = ? AND si.deleted_at IS NULL
                ORDER BY ci.name";
        return $this->query($sql, [$organizationId]);
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
