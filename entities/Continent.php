<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Continent Entity
 */
class Continent extends BaseEntity {
    protected $table = 'continents';
    protected $fillable = ['name'];

    /**
     * Get all countries in this continent
     */
    public function getCountries($continentId) {
        $sql = "SELECT * FROM countries WHERE continent_id = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$continentId]);
    }

    /**
     * Get country count for this continent
     */
    public function getCountryCount($continentId) {
        $sql = "SELECT COUNT(*) as count FROM countries WHERE continent_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$continentId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get with full details
     */
    public function getWithDetails($continentId) {
        $sql = "SELECT c.*,
                (SELECT COUNT(*) FROM countries WHERE continent_id = c.id AND deleted_at IS NULL) as country_count,
                (SELECT COUNT(*) FROM languages l
                 JOIN countries co ON l.country_id = co.id
                 WHERE co.continent_id = c.id AND l.deleted_at IS NULL) as language_count
                FROM continents c
                WHERE c.id = ? AND c.deleted_at IS NULL";
        return $this->queryOne($sql, [$continentId]);
    }

    /**
     * Get all continents with country counts
     */
    public function getAllWithCountryCounts() {
        $sql = "SELECT c.*,
                COUNT(co.id) as country_count
                FROM continents c
                LEFT JOIN countries co ON c.id = co.continent_id AND co.deleted_at IS NULL
                WHERE c.deleted_at IS NULL
                GROUP BY c.id
                ORDER BY c.name ASC";
        return $this->query($sql);
    }

    /**
     * Get continents by name pattern
     */
    public function searchByName($term) {
        $sql = "SELECT * FROM continents
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, ["%$term%"]);
    }

    /**
     * Get continents by first letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM continents
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Get continents ordered by country count
     */
    public function getOrderedByCountryCount($order = 'DESC') {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $sql = "SELECT c.*,
                COUNT(co.id) as country_count
                FROM continents c
                LEFT JOIN countries co ON c.id = co.continent_id AND co.deleted_at IS NULL
                WHERE c.deleted_at IS NULL
                GROUP BY c.id
                ORDER BY country_count $order, c.name ASC";
        return $this->query($sql);
    }

    /**
     * Check if continent has any countries
     */
    public function hasCountries($continentId) {
        return $this->getCountryCount($continentId) > 0;
    }

    /**
     * Get languages in continent
     */
    public function getLanguages($continentId) {
        $sql = "SELECT DISTINCT l.*, co.name as country_name
                FROM languages l
                JOIN countries co ON l.country_id = co.id
                WHERE co.continent_id = ? AND l.deleted_at IS NULL
                ORDER BY l.name ASC";
        return $this->query($sql, [$continentId]);
    }

    /**
     * Get language count for continent
     */
    public function getLanguageCount($continentId) {
        $sql = "SELECT COUNT(DISTINCT l.id) as count
                FROM languages l
                JOIN countries co ON l.country_id = co.id
                WHERE co.continent_id = ? AND l.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$continentId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get organizations in continent
     */
    public function getOrganizations($continentId, $limit = 50) {
        $sql = "SELECT o.*, co.name as country_name
                FROM organizations o
                JOIN countries co ON co.id = (
                    SELECT pa.country_id FROM postal_addresses pa
                    JOIN organization_buildings ob ON ob.postal_address_id = pa.id
                    JOIN organization_branches obr ON obr.id = ob.branch_id
                    WHERE obr.organization_id = o.id
                    LIMIT 1
                )
                WHERE co.continent_id = ? AND o.deleted_at IS NULL
                ORDER BY o.short_name ASC
                LIMIT ?";
        return $this->query($sql, [$continentId, $limit]);
    }

    /**
     * Get organization count in continent
     */
    public function getOrganizationCount($continentId) {
        $sql = "SELECT COUNT(DISTINCT o.id) as count
                FROM organizations o
                JOIN countries co ON co.id = (
                    SELECT pa.country_id FROM postal_addresses pa
                    JOIN organization_buildings ob ON ob.postal_address_id = pa.id
                    JOIN organization_branches obr ON obr.id = ob.branch_id
                    WHERE obr.organization_id = o.id
                    LIMIT 1
                )
                WHERE co.continent_id = ? AND o.deleted_at IS NULL";
        $result = $this->queryOne($sql, [$continentId]);
        return $result['count'] ?? 0;
    }

    /**
     * Check if continent name exists
     */
    public function nameExists($name, $exceptId = null) {
        $sql = "SELECT id FROM continents WHERE name = ? AND deleted_at IS NULL";
        $params = [$name];

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
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(DISTINCT c.id) as total_continents,
                    COUNT(DISTINCT co.id) as total_countries,
                    AVG(country_counts.count) as avg_countries_per_continent,
                    MAX(country_counts.count) as max_countries,
                    MIN(country_counts.count) as min_countries
                FROM continents c
                LEFT JOIN countries co ON c.id = co.continent_id AND co.deleted_at IS NULL
                LEFT JOIN (
                    SELECT continent_id, COUNT(*) as count
                    FROM countries
                    WHERE deleted_at IS NULL
                    GROUP BY continent_id
                ) country_counts ON c.id = country_counts.continent_id
                WHERE c.deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Validate continent data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:100' . ($id ? "|unique:continents,name,$id" : '|unique:continents,name'),
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $continent = $this->find($id);
        if (!$continent) {
            return 'N/A';
        }
        $countryCount = $this->getCountryCount($id);
        return $continent['name'] . ' (' . $countryCount . ' countries)';
    }
}
