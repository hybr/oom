<?php

require_once __DIR__ . '/BaseEntity.php';

class Country extends BaseEntity {
    protected $table = 'countries';
    protected $fillable = [
        'id',
        'name',
        'official_name',
        'continent_id',
        'iso_alpha_2',
        'iso_alpha_3',
        'iso_numeric',
        'capital',
        'area_km2',
        'population',
        'population_density',
        'gdp_usd',
        'gdp_per_capita',
        'currency_code',
        'currency_name',
        'official_languages',
        'calling_code',
        'internet_tld',
        'independence_date',
        'government_type',
        'head_of_state',
        'head_of_government',
        'flag_emoji',
        'latitude',
        'longitude',
        'timezone',
        'region',
        'subregion',
        'is_landlocked',
        'is_developed',
        'is_active',
        'created_at',
        'updated_at'
    ];

    const GOVERNMENT_REPUBLIC = 'republic';
    const GOVERNMENT_MONARCHY = 'monarchy';
    const GOVERNMENT_FEDERATION = 'federation';
    const GOVERNMENT_PARLIAMENTARY = 'parliamentary';
    const GOVERNMENT_PRESIDENTIAL = 'presidential';
    const GOVERNMENT_DICTATORSHIP = 'dictatorship';
    const GOVERNMENT_COMMUNIST = 'communist';
    const GOVERNMENT_OTHER = 'other';

    public function __construct() {
        parent::__construct();
        $this->attributes['is_landlocked'] = 0;
        $this->attributes['is_developed'] = 0;
        $this->attributes['is_active'] = 1;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function getContinent() {
        if (!$this->continent_id) return null;

        require_once __DIR__ . '/Continent.php';
        return Continent::find($this->continent_id);
    }

    public function getContinentName() {
        $continent = $this->getContinent();
        return $continent ? $continent->name : 'Unknown';
    }

    public function getFormattedArea() {
        if (!$this->area_km2) return 'Unknown';
        return number_format($this->area_km2) . ' km²';
    }

    public function getFormattedPopulation() {
        if (!$this->population) return 'Unknown';
        return $this->formatNumber($this->population);
    }

    public function getFormattedGDP() {
        if (!$this->gdp_usd) return 'Unknown';
        return '$' . $this->formatNumber($this->gdp_usd);
    }

    public function getFormattedGDPPerCapita() {
        if (!$this->gdp_per_capita) return 'Unknown';
        return '$' . number_format($this->gdp_per_capita);
    }

    private function formatNumber($number) {
        if ($number >= 1000000000000) {
            return number_format($number / 1000000000000, 2) . 'T';
        } elseif ($number >= 1000000000) {
            return number_format($number / 1000000000, 2) . 'B';
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        return number_format($number);
    }

    public function calculatePopulationDensity() {
        if (!$this->area_km2 || !$this->population) return 0;
        return round($this->population / $this->area_km2, 2);
    }

    public function updatePopulationDensity() {
        $this->population_density = $this->calculatePopulationDensity();
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function getDisplayName() {
        if ($this->flag_emoji) {
            return $this->flag_emoji . ' ' . $this->name;
        }
        return $this->name;
    }

    public function getFullDisplayName() {
        $display = $this->getDisplayName();
        if ($this->official_name && $this->official_name !== $this->name) {
            $display .= ' (' . $this->official_name . ')';
        }
        return $display;
    }

    public function isLandlocked() {
        return $this->is_landlocked == 1;
    }

    public function isDeveloped() {
        return $this->is_developed == 1;
    }

    public function isActive() {
        return $this->is_active == 1;
    }

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

    public function updatePopulation($population) {
        $this->population = $population;
        $this->updatePopulationDensity();
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateGDP($gdp, $gdpPerCapita = null) {
        $this->gdp_usd = $gdp;
        if ($gdpPerCapita !== null) {
            $this->gdp_per_capita = $gdpPerCapita;
        } elseif ($this->population > 0) {
            $this->gdp_per_capita = round($gdp / $this->population, 2);
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function setContinent($continentId) {
        $this->continent_id = $continentId;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public static function findByISO2($iso2) {
        $results = static::where('iso_alpha_2', '=', strtoupper($iso2));
        return !empty($results) ? $results[0] : null;
    }

    public static function findByISO3($iso3) {
        $results = static::where('iso_alpha_3', '=', strtoupper($iso3));
        return !empty($results) ? $results[0] : null;
    }

    public static function findByName($name) {
        $results = static::where('name', '=', $name);
        return !empty($results) ? $results[0] : null;
    }

    public static function getByContinent($continentId) {
        return static::where('continent_id', '=', $continentId);
    }

    public static function getByRegion($region) {
        return static::where('region', '=', $region);
    }

    public static function getBySubregion($subregion) {
        return static::where('subregion', '=', $subregion);
    }

    public static function getLandlockedCountries() {
        return static::where('is_landlocked', '=', 1);
    }

    public static function getDevelopedCountries() {
        return static::where('is_developed', '=', 1);
    }

    public static function getDevelopingCountries() {
        return static::where('is_developed', '=', 0);
    }

    public static function getByGovernmentType($type) {
        return static::where('government_type', '=', $type);
    }

    public static function getLargestByArea($limit = 10) {
        $countries = static::all();
        usort($countries, function($a, $b) {
            return ($b->area_km2 ?: 0) <=> ($a->area_km2 ?: 0);
        });
        return array_slice($countries, 0, $limit);
    }

    public static function getLargestByPopulation($limit = 10) {
        $countries = static::all();
        usort($countries, function($a, $b) {
            return ($b->population ?: 0) <=> ($a->population ?: 0);
        });
        return array_slice($countries, 0, $limit);
    }

    public static function getRichestByGDP($limit = 10) {
        $countries = static::all();
        usort($countries, function($a, $b) {
            return ($b->gdp_usd ?: 0) <=> ($a->gdp_usd ?: 0);
        });
        return array_slice($countries, 0, $limit);
    }

    public static function getRichestByGDPPerCapita($limit = 10) {
        $countries = static::all();
        usort($countries, function($a, $b) {
            return ($b->gdp_per_capita ?: 0) <=> ($a->gdp_per_capita ?: 0);
        });
        return array_slice($countries, 0, $limit);
    }

    public static function getMostDensely($limit = 10) {
        $countries = static::all();
        usort($countries, function($a, $b) {
            $densityA = $a->calculatePopulationDensity();
            $densityB = $b->calculatePopulationDensity();
            return $densityB <=> $densityA;
        });
        return array_slice($countries, 0, $limit);
    }

    public static function searchCountries($query) {
        $countries = static::all();
        $query = strtolower($query);

        return array_filter($countries, function($country) use ($query) {
            return strpos(strtolower($country->name), $query) !== false ||
                   strpos(strtolower($country->official_name ?: ''), $query) !== false ||
                   strpos(strtolower($country->capital ?: ''), $query) !== false ||
                   strpos(strtolower($country->iso_alpha_2 ?: ''), $query) !== false ||
                   strpos(strtolower($country->iso_alpha_3 ?: ''), $query) !== false ||
                   strpos(strtolower($country->region ?: ''), $query) !== false ||
                   strpos(strtolower($country->subregion ?: ''), $query) !== false;
        });
    }

    public function getContinentWithCountries() {
        $continent = $this->getContinent();
        if (!$continent) return null;

        $countries = static::getByContinent($continent->id);
        return [
            'continent' => $continent->toArray(),
            'countries' => array_map(function($country) {
                return $country->toArray();
            }, $countries)
        ];
    }

    public function getStatistics() {
        return [
            'name' => $this->name,
            'display_name' => $this->getDisplayName(),
            'full_display_name' => $this->getFullDisplayName(),
            'continent' => $this->getContinentName(),
            'capital' => $this->capital,
            'area_formatted' => $this->getFormattedArea(),
            'population_formatted' => $this->getFormattedPopulation(),
            'density' => $this->calculatePopulationDensity(),
            'gdp_formatted' => $this->getFormattedGDP(),
            'gdp_per_capita_formatted' => $this->getFormattedGDPPerCapita(),
            'is_landlocked' => $this->isLandlocked(),
            'is_developed' => $this->isDeveloped(),
            'is_active' => $this->isActive(),
            'iso_codes' => [
                'alpha2' => $this->iso_alpha_2,
                'alpha3' => $this->iso_alpha_3,
                'numeric' => $this->iso_numeric
            ]
        ];
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS countries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT UNIQUE NOT NULL,
                official_name TEXT,
                continent_id INTEGER NOT NULL,
                iso_alpha_2 TEXT UNIQUE,
                iso_alpha_3 TEXT UNIQUE,
                iso_numeric TEXT,
                capital TEXT,
                area_km2 INTEGER,
                population INTEGER,
                population_density REAL,
                gdp_usd INTEGER,
                gdp_per_capita REAL,
                currency_code TEXT,
                currency_name TEXT,
                official_languages TEXT,
                calling_code TEXT,
                internet_tld TEXT,
                independence_date DATE,
                government_type TEXT,
                head_of_state TEXT,
                head_of_government TEXT,
                flag_emoji TEXT,
                latitude REAL,
                longitude REAL,
                timezone TEXT,
                region TEXT,
                subregion TEXT,
                is_landlocked INTEGER DEFAULT 0,
                is_developed INTEGER DEFAULT 0,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (continent_id) REFERENCES continents (id) ON DELETE RESTRICT
            )
        ";
    }
}