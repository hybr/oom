<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Country Entity
 */
class Country extends BaseEntity {
    protected $table = 'countries';
    protected $fillable = ['name', 'continent_id'];

    /**
     * Get continent for this country
     */
    public function getContinent($countryId) {
        $sql = "SELECT c.* FROM continents c
                JOIN countries co ON co.continent_id = c.id
                WHERE co.id = ? AND c.deleted_at IS NULL";
        return $this->queryOne($sql, [$countryId]);
    }

    /**
     * Get with full details
     */
    public function getWithDetails($countryId) {
        $sql = "SELECT co.*,
                c.name as continent_name,
                (SELECT COUNT(*) FROM languages WHERE country_id = co.id AND deleted_at IS NULL) as language_count,
                (SELECT COUNT(*) FROM postal_addresses WHERE country_id = co.id AND deleted_at IS NULL) as address_count
                FROM countries co
                LEFT JOIN continents c ON co.continent_id = c.id
                WHERE co.id = ? AND co.deleted_at IS NULL";
        return $this->queryOne($sql, [$countryId]);
    }

    /**
     * Get all languages in this country
     */
    public function getLanguages($countryId) {
        $sql = "SELECT * FROM languages WHERE country_id = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Get language count
     */
    public function getLanguageCount($countryId) {
        $sql = "SELECT COUNT(*) as count FROM languages WHERE country_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$countryId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get postal addresses in this country
     */
    public function getPostalAddresses($countryId, $limit = 50) {
        $sql = "SELECT * FROM postal_addresses
                WHERE country_id = ? AND deleted_at IS NULL
                ORDER BY state ASC, city ASC
                LIMIT ?";
        return $this->query($sql, [$countryId, $limit]);
    }

    /**
     * Get cities in this country
     */
    public function getCities($countryId, $state = null) {
        $sql = "SELECT DISTINCT city, COUNT(*) as address_count
                FROM postal_addresses
                WHERE country_id = ? AND deleted_at IS NULL";

        $params = [$countryId];

        if ($state) {
            $sql .= " AND state = ?";
            $params[] = $state;
        }

        $sql .= " GROUP BY city ORDER BY city ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get states in this country
     */
    public function getStates($countryId) {
        $sql = "SELECT DISTINCT state, COUNT(DISTINCT city) as city_count
                FROM postal_addresses
                WHERE country_id = ? AND state IS NOT NULL AND deleted_at IS NULL
                GROUP BY state
                ORDER BY state ASC";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Get organizations in this country
     */
    public function getOrganizations($countryId, $limit = 50) {
        $sql = "SELECT DISTINCT o.*
                FROM organizations o
                JOIN organization_branches obr ON obr.organization_id = o.id
                JOIN organization_buildings ob ON ob.branch_id = obr.id
                JOIN postal_addresses pa ON pa.id = ob.postal_address_id
                WHERE pa.country_id = ? AND o.deleted_at IS NULL
                ORDER BY o.short_name ASC
                LIMIT ?";
        return $this->query($sql, [$countryId, $limit]);
    }

    /**
     * Get organization count
     */
    public function getOrganizationCount($countryId) {
        $sql = "SELECT COUNT(DISTINCT o.id) as count
                FROM organizations o
                JOIN organization_branches obr ON obr.organization_id = o.id
                JOIN organization_buildings ob ON ob.branch_id = obr.id
                JOIN postal_addresses pa ON pa.id = ob.postal_address_id
                WHERE pa.country_id = ? AND o.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$countryId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get countries by continent
     */
    public function getByContinent($continentId) {
        return $this->all(['continent_id' => $continentId], 'name ASC');
    }

    /**
     * Search countries by name
     */
    public function searchByName($term) {
        $sql = "SELECT co.*, c.name as continent_name
                FROM countries co
                LEFT JOIN continents c ON co.continent_id = c.id
                WHERE co.name LIKE ? AND co.deleted_at IS NULL
                ORDER BY co.name ASC";
        return $this->query($sql, ["%$term%"]);
    }

    /**
     * Get countries by first letter
     */
    public function getByFirstLetter($letter, $continentId = null) {
        $sql = "SELECT co.*, c.name as continent_name
                FROM countries co
                LEFT JOIN continents c ON co.continent_id = c.id
                WHERE co.name LIKE ? AND co.deleted_at IS NULL";

        $params = [$letter . '%'];

        if ($continentId) {
            $sql .= " AND co.continent_id = ?";
            $params[] = $continentId;
        }

        $sql .= " ORDER BY co.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Get all countries with counts
     */
    public function getAllWithCounts($continentId = null) {
        $sql = "SELECT co.*,
                c.name as continent_name,
                COUNT(DISTINCT l.id) as language_count,
                COUNT(DISTINCT pa.id) as address_count
                FROM countries co
                LEFT JOIN continents c ON co.continent_id = c.id
                LEFT JOIN languages l ON l.country_id = co.id AND l.deleted_at IS NULL
                LEFT JOIN postal_addresses pa ON pa.country_id = co.id AND pa.deleted_at IS NULL
                WHERE co.deleted_at IS NULL";

        $params = [];

        if ($continentId) {
            $sql .= " AND co.continent_id = ?";
            $params[] = $continentId;
        }

        $sql .= " GROUP BY co.id ORDER BY co.name ASC";
        return $this->query($sql, $params);
    }

    /**
     * Check if country name exists
     */
    public function nameExists($name, $continentId = null, $exceptId = null) {
        $sql = "SELECT id FROM countries WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

        if ($continentId) {
            $sql .= " AND continent_id = ?";
            $params[] = $continentId;
        }

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->queryOne($sql, $params);
        return !empty($result);
    }

    /**
     * Get statistics
     */
    public function getStatistics($continentId = null) {
        $sql = "SELECT
                    COUNT(DISTINCT co.id) as total_countries,
                    COUNT(DISTINCT l.id) as total_languages,
                    COUNT(DISTINCT pa.id) as total_addresses,
                    AVG(lang_counts.count) as avg_languages_per_country
                FROM countries co
                LEFT JOIN languages l ON l.country_id = co.id AND l.deleted_at IS NULL
                LEFT JOIN postal_addresses pa ON pa.country_id = co.id AND pa.deleted_at IS NULL
                LEFT JOIN (
                    SELECT country_id, COUNT(*) as count
                    FROM languages
                    WHERE deleted_at IS NULL
                    GROUP BY country_id
                ) lang_counts ON co.id = lang_counts.country_id
                WHERE co.deleted_at IS NULL";

        $params = [];

        if ($continentId) {
            $sql .= " AND co.continent_id = ?";
            $params[] = $continentId;
        }

        return $this->queryOne($sql, $params);
    }

    /**
     * Validate country data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:100',
            'continent_id' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $country = $this->getWithDetails($id);
        if (!$country) {
            return 'N/A';
        }
        return $country['name'] . ' (' . $country['continent_name'] . ')';
    }
}
