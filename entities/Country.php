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
     * Get all languages in this country
     */
    public function getLanguages($countryId) {
        $sql = "SELECT * FROM languages WHERE country_id = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Get countries by continent
     */
    public function getByContinent($continentId) {
        return $this->all(['continent_id' => $continentId], 'name ASC');
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
}
