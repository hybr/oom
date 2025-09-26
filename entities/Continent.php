<?php

require_once __DIR__ . '/BaseEntity.php';

class Continent extends BaseEntity {
    protected $table = 'continents';
    protected $fillable = [
        'id',
        'name',
        'code',
        'area_km2',
        'population',
        'countries_count',
        'largest_country',
        'description',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function __construct() {
        parent::__construct();
        $this->attributes['is_active'] = 1;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function getFormattedArea() {
        if (!$this->area_km2) return 'N/A';
        return number_format($this->area_km2) . ' km²';
    }

    public function getFormattedPopulation() {
        if (!$this->population) return 'N/A';

        if ($this->population >= 1000000000) {
            return number_format($this->population / 1000000000, 2) . 'B';
        } elseif ($this->population >= 1000000) {
            return number_format($this->population / 1000000, 1) . 'M';
        } elseif ($this->population >= 1000) {
            return number_format($this->population / 1000, 1) . 'K';
        }

        return number_format($this->population);
    }

    public function getPopulationDensity() {
        if (!$this->area_km2 || !$this->population) return 0;
        return round($this->population / $this->area_km2, 2);
    }

    public function updateStatus($status) {
        $this->is_active = $status ? 1 : 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function isActive() {
        return $this->is_active == 1;
    }

    public function activate() {
        return $this->updateStatus(true);
    }

    public function deactivate() {
        return $this->updateStatus(false);
    }

    public function updatePopulation($population) {
        $this->population = $population;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateCountriesCount($count) {
        $this->countries_count = $count;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function setLargestCountry($country) {
        $this->largest_country = $country;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public static function findByCode($code) {
        $results = static::where('code', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByName($name) {
        $results = static::where('name', '=', $name);
        return !empty($results) ? $results[0] : null;
    }

    public static function getActiveOnly() {
        return static::where('is_active', '=', 1);
    }

    public static function getByAreaSize($ascending = false) {
        $continents = static::all();
        usort($continents, function($a, $b) use ($ascending) {
            $comparison = $a->area_km2 <=> $b->area_km2;
            return $ascending ? $comparison : -$comparison;
        });
        return $continents;
    }

    public static function getByPopulation($ascending = false) {
        $continents = static::all();
        usort($continents, function($a, $b) use ($ascending) {
            $comparison = $a->population <=> $b->population;
            return $ascending ? $comparison : -$comparison;
        });
        return $continents;
    }

    public function getStatistics() {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'area_formatted' => $this->getFormattedArea(),
            'population_formatted' => $this->getFormattedPopulation(),
            'density' => $this->getPopulationDensity(),
            'countries_count' => $this->countries_count,
            'largest_country' => $this->largest_country,
            'is_active' => $this->isActive()
        ];
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS continents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT UNIQUE NOT NULL,
                code TEXT UNIQUE NOT NULL,
                area_km2 INTEGER,
                population INTEGER,
                countries_count INTEGER DEFAULT 0,
                largest_country TEXT,
                description TEXT,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
    }
}