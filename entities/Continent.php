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
     * Validate continent data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:100' . ($id ? "|unique:continents,name,$id" : '|unique:continents,name'),
        ];

        return $this->validate($data, $rules);
    }
}
