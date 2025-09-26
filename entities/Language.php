<?php

require_once __DIR__ . '/BaseEntity.php';

class Language extends BaseEntity {
    protected $table = 'languages';
    protected $fillable = [
        'id',
        'name',
        'native_name',
        'iso_639_1',
        'iso_639_2',
        'iso_639_3',
        'language_family',
        'writing_system',
        'speakers_native',
        'speakers_total',
        'language_type',
        'status',
        'description',
        'is_active',
        'created_at',
        'updated_at'
    ];

    const STATUS_LIVING = 'living';
    const STATUS_EXTINCT = 'extinct';
    const STATUS_DORMANT = 'dormant';
    const STATUS_ENDANGERED = 'endangered';
    const STATUS_REVITALIZED = 'revitalized';

    const TYPE_NATURAL = 'natural';
    const TYPE_CONSTRUCTED = 'constructed';
    const TYPE_SIGN = 'sign';
    const TYPE_PIDGIN = 'pidgin';
    const TYPE_CREOLE = 'creole';

    public function __construct() {
        parent::__construct();
        $this->attributes['language_type'] = self::TYPE_NATURAL;
        $this->attributes['status'] = self::STATUS_LIVING;
        $this->attributes['is_active'] = 1;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function getFormattedSpeakersNative() {
        if (!$this->speakers_native) return 'Unknown';
        return $this->formatNumber($this->speakers_native);
    }

    public function getFormattedSpeakersTotal() {
        if (!$this->speakers_total) return 'Unknown';
        return $this->formatNumber($this->speakers_total);
    }

    private function formatNumber($number) {
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 2) . 'B';
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        return number_format($number);
    }

    public function getDisplayName() {
        if ($this->native_name && $this->native_name !== $this->name) {
            return "{$this->name} ({$this->native_name})";
        }
        return $this->name;
    }

    public function getISOCodes() {
        $codes = [];
        if ($this->iso_639_1) $codes[] = "ISO 639-1: {$this->iso_639_1}";
        if ($this->iso_639_2) $codes[] = "ISO 639-2: {$this->iso_639_2}";
        if ($this->iso_639_3) $codes[] = "ISO 639-3: {$this->iso_639_3}";
        return implode(', ', $codes);
    }

    public function isEndangered() {
        return in_array($this->status, [self::STATUS_ENDANGERED, self::STATUS_DORMANT, self::STATUS_EXTINCT]);
    }

    public function isLiving() {
        return $this->status === self::STATUS_LIVING;
    }

    public function isExtinct() {
        return $this->status === self::STATUS_EXTINCT;
    }

    public function updateStatus($status) {
        if (!in_array($status, [
            self::STATUS_LIVING,
            self::STATUS_EXTINCT,
            self::STATUS_DORMANT,
            self::STATUS_ENDANGERED,
            self::STATUS_REVITALIZED
        ])) {
            throw new InvalidArgumentException('Invalid language status');
        }

        $this->status = $status;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function updateSpeakersCount($native, $total = null) {
        $this->speakers_native = $native;
        if ($total !== null) {
            $this->speakers_total = $total;
        } else {
            $this->speakers_total = $native; // Default to native speakers if total not provided
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
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

    public function isActive() {
        return $this->is_active == 1;
    }

    public static function findByISO639_1($code) {
        $results = static::where('iso_639_1', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByISO639_2($code) {
        $results = static::where('iso_639_2', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByISO639_3($code) {
        $results = static::where('iso_639_3', '=', $code);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByName($name) {
        $results = static::where('name', '=', $name);
        return !empty($results) ? $results[0] : null;
    }

    public static function getByStatus($status) {
        return static::where('status', '=', $status);
    }

    public static function getByLanguageFamily($family) {
        return static::where('language_family', '=', $family);
    }

    public static function getByType($type) {
        return static::where('language_type', '=', $type);
    }

    public static function getEndangeredLanguages() {
        $languages = static::all();
        return array_filter($languages, function($lang) {
            return $lang->isEndangered();
        });
    }

    public static function getMostSpokenLanguages($limit = 10) {
        $languages = static::all();
        usort($languages, function($a, $b) {
            return ($b->speakers_total ?: 0) <=> ($a->speakers_total ?: 0);
        });
        return array_slice($languages, 0, $limit);
    }

    public static function getByWritingSystem($system) {
        return static::where('writing_system', '=', $system);
    }

    public static function searchLanguages($query) {
        $languages = static::all();
        $query = strtolower($query);

        return array_filter($languages, function($lang) use ($query) {
            return strpos(strtolower($lang->name), $query) !== false ||
                   strpos(strtolower($lang->native_name ?: ''), $query) !== false ||
                   strpos(strtolower($lang->language_family ?: ''), $query) !== false ||
                   strpos(strtolower($lang->iso_639_1 ?: ''), $query) !== false ||
                   strpos(strtolower($lang->iso_639_2 ?: ''), $query) !== false ||
                   strpos(strtolower($lang->iso_639_3 ?: ''), $query) !== false;
        });
    }

    public function getStatistics() {
        return [
            'name' => $this->name,
            'display_name' => $this->getDisplayName(),
            'iso_codes' => $this->getISOCodes(),
            'family' => $this->language_family,
            'type' => $this->language_type,
            'status' => $this->status,
            'writing_system' => $this->writing_system,
            'speakers_native_formatted' => $this->getFormattedSpeakersNative(),
            'speakers_total_formatted' => $this->getFormattedSpeakersTotal(),
            'is_endangered' => $this->isEndangered(),
            'is_living' => $this->isLiving(),
            'is_active' => $this->isActive()
        ];
    }

    public static function getLanguageFamilies() {
        $languages = static::all();
        $families = array_unique(array_filter(array_map(function($lang) {
            return $lang->language_family;
        }, $languages)));
        sort($families);
        return $families;
    }

    public static function getWritingSystems() {
        $languages = static::all();
        $systems = array_unique(array_filter(array_map(function($lang) {
            return $lang->writing_system;
        }, $languages)));
        sort($systems);
        return $systems;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS languages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT UNIQUE NOT NULL,
                native_name TEXT,
                iso_639_1 TEXT UNIQUE,
                iso_639_2 TEXT UNIQUE,
                iso_639_3 TEXT UNIQUE,
                language_family TEXT,
                writing_system TEXT,
                speakers_native INTEGER DEFAULT 0,
                speakers_total INTEGER DEFAULT 0,
                language_type TEXT DEFAULT 'natural',
                status TEXT DEFAULT 'living',
                description TEXT,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
    }
}