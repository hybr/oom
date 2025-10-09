<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Language Entity
 * Represents languages spoken in different countries
 */
class Language extends BaseEntity {
    protected $table = 'languages';
    protected $fillable = ['name', 'country_id'];

    /**
     * Get country for this language
     */
    public function getCountry($languageId) {
        $sql = "SELECT c.* FROM countries c
                JOIN languages l ON l.country_id = c.id
                WHERE l.id = ? AND c.deleted_at IS NULL";
        return $this->queryOne($sql, [$languageId]);
    }

    /**
     * Get languages by country
     */
    public function getByCountry($countryId) {
        return $this->all(['country_id' => $countryId], 'name ASC');
    }

    /**
     * Get most spoken languages (by number of countries)
     */
    public function getMostSpoken($limit = 10) {
        $sql = "SELECT l.name, COUNT(DISTINCT l.country_id) as country_count
                FROM languages l
                WHERE l.deleted_at IS NULL
                GROUP BY l.name
                ORDER BY country_count DESC, l.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get languages starting with letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM languages
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Check if language exists in country
     */
    public function existsInCountry($languageName, $countryId) {
        $sql = "SELECT COUNT(*) as count FROM languages
                WHERE name = ? AND country_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$languageName, $countryId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get all unique language names
     */
    public function getUniqueLanguages() {
        $sql = "SELECT DISTINCT name FROM languages
                WHERE deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql);
    }

    /**
     * Get country count for a language name
     */
    public function getCountryCountByName($languageName) {
        $sql = "SELECT COUNT(DISTINCT country_id) as count
                FROM languages
                WHERE name = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$languageName]);
        return $result['count'] ?? 0;
    }

    /**
     * Get languages with country information
     */
    public function getWithCountryInfo($languageId) {
        $sql = "SELECT l.*, c.name as country_name, cont.name as continent_name
                FROM languages l
                LEFT JOIN countries c ON l.country_id = c.id
                LEFT JOIN continents cont ON c.continent_id = cont.id
                WHERE l.id = ? AND l.deleted_at IS NULL";
        return $this->queryOne($sql, [$languageId]);
    }

    /**
     * Search languages by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Get languages by continent
     */
    public function getByContinent($continentId) {
        $sql = "SELECT DISTINCT l.*
                FROM languages l
                JOIN countries c ON l.country_id = c.id
                WHERE c.continent_id = ? AND l.deleted_at IS NULL
                ORDER BY l.name ASC";
        return $this->query($sql, [$continentId]);
    }

    /**
     * Get statistics for languages
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(DISTINCT id) as total_language_records,
                    COUNT(DISTINCT name) as unique_languages,
                    COUNT(DISTINCT country_id) as countries_with_languages
                FROM languages
                WHERE deleted_at IS NULL";
        return $this->queryOne($sql);
    }

    /**
     * Bulk import languages for a country
     */
    public function bulkImportForCountry($countryId, $languageNames) {
        $imported = 0;
        foreach ($languageNames as $name) {
            if (!$this->existsInCountry($name, $countryId)) {
                $this->create(['name' => $name, 'country_id' => $countryId]);
                $imported++;
            }
        }
        return $imported;
    }

    /**
     * Validate language data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:100',
            'country_id' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel to return language name with country
     */
    public function getLabel($id) {
        $language = $this->getWithCountryInfo($id);
        if (!$language) {
            return 'N/A';
        }
        return $language['name'] . ' (' . ($language['country_name'] ?? 'Unknown Country') . ')';
    }
}
