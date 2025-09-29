<?php

require_once __DIR__ . '/BaseEntity.php';

class PostalAddress extends BaseEntity {
    protected $table = 'postal_addresses';
    protected $fillable = [
        'id',
        'name',
        'street_address_1',
        'street_address_2',
        'city',
        'state_province',
        'postal_code',
        'country_id',
        'latitude',
        'longitude',
        'address_type',
        'is_default',
        'is_verified',
        'verification_method',
        'formatted_address',
        'timezone',
        'delivery_notes',
        'access_instructions',
        'landmark',
        'building_name',
        'floor',
        'apartment_unit',
        'status',
        'created_at',
        'updated_at'
    ];

    // Address types
    const TYPE_HOME = 'home';
    const TYPE_WORK = 'work';
    const TYPE_BUSINESS = 'business';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_BILLING = 'billing';
    const TYPE_MAILING = 'mailing';
    const TYPE_OTHER = 'other';

    // Address statuses
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ARCHIVED = 'archived';

    // Verification methods
    const VERIFICATION_MANUAL = 'manual';
    const VERIFICATION_GEOCODING = 'geocoding';
    const VERIFICATION_GPS = 'gps';
    const VERIFICATION_POSTAL_SERVICE = 'postal_service';

    public function __construct() {
        parent::__construct();
        $this->attributes['address_type'] = self::TYPE_HOME;
        $this->attributes['is_default'] = 0;
        $this->attributes['is_verified'] = 0;
        $this->attributes['verification_method'] = self::VERIFICATION_MANUAL;
        $this->attributes['status'] = self::STATUS_ACTIVE;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    // Relationship with Country entity
    public function getCountry() {
        if (!$this->country_id) return null;

        require_once __DIR__ . '/Country.php';
        return Country::find($this->country_id);
    }

    public function getCountryName() {
        $country = $this->getCountry();
        return $country ? $country->name : 'Unknown';
    }

    public function getCountryCode() {
        $country = $this->getCountry();
        return $country ? $country->iso_code : '';
    }

    // Address validation
    public function validateLatitude($lat) {
        return is_numeric($lat) && $lat >= -90 && $lat <= 90;
    }

    public function validateLongitude($lng) {
        return is_numeric($lng) && $lng >= -180 && $lng <= 180;
    }

    public function validateCoordinates($lat = null, $lng = null) {
        $lat = $lat ?? $this->latitude;
        $lng = $lng ?? $this->longitude;

        return $this->validateLatitude($lat) && $this->validateLongitude($lng);
    }

    public function setCoordinates($lat, $lng) {
        if (!$this->validateCoordinates($lat, $lng)) {
            throw new InvalidArgumentException("Invalid coordinates: latitude must be between -90 and 90, longitude must be between -180 and 180");
        }

        $this->latitude = floatval($lat);
        $this->longitude = floatval($lng);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    // Address formatting
    public function getFormattedAddress($includeCountry = true) {
        if ($this->formatted_address) {
            return $this->formatted_address;
        }

        $parts = [];

        if ($this->street_address_1) {
            $parts[] = $this->street_address_1;
        }

        if ($this->street_address_2) {
            $parts[] = $this->street_address_2;
        }

        $cityStateParts = [];
        if ($this->city) {
            $cityStateParts[] = $this->city;
        }
        if ($this->state_province) {
            $cityStateParts[] = $this->state_province;
        }
        if ($this->postal_code) {
            $cityStateParts[] = $this->postal_code;
        }

        if (!empty($cityStateParts)) {
            $parts[] = implode(', ', $cityStateParts);
        }

        if ($includeCountry && $this->getCountryName() !== 'Unknown') {
            $parts[] = $this->getCountryName();
        }

        return implode(', ', array_filter($parts));
    }

    public function getShortAddress() {
        $parts = [];

        if ($this->street_address_1) {
            $parts[] = $this->street_address_1;
        }

        if ($this->city) {
            $parts[] = $this->city;
        }

        return implode(', ', array_filter($parts));
    }

    // Geo calculations
    public function getDistanceFromCoordinates($lat, $lng, $unit = 'km') {
        if (!$this->validateCoordinates() || !$this->validateCoordinates($lat, $lng)) {
            return null;
        }

        $earthRadius = ($unit === 'miles') ? 3959 : 6371;

        $latDelta = deg2rad($lat - $this->latitude);
        $lngDelta = deg2rad($lng - $this->longitude);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    public function getDistanceFromAddress(PostalAddress $otherAddress, $unit = 'km') {
        if (!$otherAddress->validateCoordinates()) {
            return null;
        }

        return $this->getDistanceFromCoordinates($otherAddress->latitude, $otherAddress->longitude, $unit);
    }

    // Business logic methods
    public function getDisplayName() {
        return $this->name ?: $this->getShortAddress() ?: 'Untitled Address';
    }

    public function isActive() {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isVerified() {
        return $this->is_verified == 1;
    }

    public function isDefault() {
        return $this->is_default == 1;
    }

    public function hasCoordinates() {
        return !empty($this->latitude) && !empty($this->longitude) && $this->validateCoordinates();
    }

    // Status management
    public function activate() {
        $this->status = self::STATUS_ACTIVE;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function deactivate() {
        $this->status = self::STATUS_INACTIVE;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function archive() {
        $this->status = self::STATUS_ARCHIVED;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Verification management
    public function verify($method = self::VERIFICATION_MANUAL) {
        $this->is_verified = 1;
        $this->verification_method = $method;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function unverify() {
        $this->is_verified = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Default address management
    public function makeDefault() {
        $this->is_default = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function removeDefault() {
        $this->is_default = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Address geocoding helper
    public function geocodeAddress() {
        $address = $this->getFormattedAddress(true);
        if (empty($address)) {
            return false;
        }

        // This would typically integrate with a geocoding service
        // For now, returning a placeholder - implementation would depend on chosen service
        return [
            'latitude' => null,
            'longitude' => null,
            'formatted_address' => $address,
            'success' => false,
            'error' => 'Geocoding service not configured'
        ];
    }

    // Map URL generators
    public function getGoogleMapsUrl() {
        if ($this->hasCoordinates()) {
            return "https://maps.google.com/maps?q={$this->latitude},{$this->longitude}";
        }

        $address = urlencode($this->getFormattedAddress(true));
        return "https://maps.google.com/maps?q={$address}";
    }

    public function getAppleMapsUrl() {
        if ($this->hasCoordinates()) {
            return "http://maps.apple.com/?ll={$this->latitude},{$this->longitude}";
        }

        $address = urlencode($this->getFormattedAddress(true));
        return "http://maps.apple.com/?q={$address}";
    }

    public function getOpenStreetMapUrl() {
        if ($this->hasCoordinates()) {
            return "https://www.openstreetmap.org/?mlat={$this->latitude}&mlon={$this->longitude}&zoom=15";
        }

        return "https://www.openstreetmap.org/";
    }

    // Search and query methods
    public static function findByType($type) {
        return static::where('address_type', '=', $type);
    }

    public static function findByCountry($countryId) {
        return static::where('country_id', '=', $countryId);
    }

    public static function findByCity($city) {
        return static::where('city', '=', $city);
    }

    public static function findByPostalCode($postalCode) {
        return static::where('postal_code', '=', $postalCode);
    }

    public static function findDefault() {
        return static::where('is_default', '=', 1);
    }

    public static function findVerified() {
        return static::where('is_verified', '=', 1);
    }

    public static function findActive() {
        return static::where('status', '=', self::STATUS_ACTIVE);
    }

    public static function findWithCoordinates() {
        $addresses = static::all();
        return array_filter($addresses, function($address) {
            return $address->hasCoordinates();
        });
    }

    public static function findNearCoordinates($lat, $lng, $radiusKm = 50) {
        $addresses = static::findWithCoordinates();

        return array_filter($addresses, function($address) use ($lat, $lng, $radiusKm) {
            $distance = $address->getDistanceFromCoordinates($lat, $lng);
            return $distance !== null && $distance <= $radiusKm;
        });
    }

    public static function searchAddresses($query) {
        $addresses = static::all();
        $query = strtolower($query);

        return array_filter($addresses, function($address) use ($query) {
            return strpos(strtolower($address->name ?: ''), $query) !== false ||
                   strpos(strtolower($address->street_address_1 ?: ''), $query) !== false ||
                   strpos(strtolower($address->street_address_2 ?: ''), $query) !== false ||
                   strpos(strtolower($address->city ?: ''), $query) !== false ||
                   strpos(strtolower($address->state_province ?: ''), $query) !== false ||
                   strpos(strtolower($address->postal_code ?: ''), $query) !== false ||
                   strpos(strtolower($address->getCountryName()), $query) !== false;
        });
    }

    // Utility methods
    public function getStatistics() {
        return [
            'id' => $this->id,
            'name' => $this->getDisplayName(),
            'formatted_address' => $this->getFormattedAddress(),
            'short_address' => $this->getShortAddress(),
            'country' => $this->getCountryName(),
            'type' => $this->address_type,
            'status' => $this->status,
            'is_verified' => $this->isVerified(),
            'is_default' => $this->isDefault(),
            'has_coordinates' => $this->hasCoordinates(),
            'coordinates' => $this->hasCoordinates() ? "{$this->latitude}, {$this->longitude}" : null,
            'created_at' => $this->created_at
        ];
    }

    public function toArray() {
        $data = parent::toArray();

        // Add computed fields
        $data['display_name'] = $this->getDisplayName();
        $data['formatted_address'] = $this->getFormattedAddress();
        $data['short_address'] = $this->getShortAddress();
        $data['country_name'] = $this->getCountryName();
        $data['country_code'] = $this->getCountryCode();
        $data['has_coordinates'] = $this->hasCoordinates();
        $data['google_maps_url'] = $this->getGoogleMapsUrl();
        $data['apple_maps_url'] = $this->getAppleMapsUrl();
        $data['openstreetmap_url'] = $this->getOpenStreetMapUrl();

        return $data;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS postal_addresses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                street_address_1 TEXT NOT NULL,
                street_address_2 TEXT,
                city TEXT NOT NULL,
                state_province TEXT,
                postal_code TEXT,
                country_id INTEGER NOT NULL,
                latitude DECIMAL(10,8) NOT NULL,
                longitude DECIMAL(11,8) NOT NULL,
                address_type TEXT DEFAULT 'home',
                is_default INTEGER DEFAULT 0,
                is_verified INTEGER DEFAULT 0,
                verification_method TEXT DEFAULT 'manual',
                formatted_address TEXT,
                timezone TEXT,
                delivery_notes TEXT,
                access_instructions TEXT,
                landmark TEXT,
                building_name TEXT,
                floor TEXT,
                apartment_unit TEXT,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (country_id) REFERENCES countries (id) ON DELETE RESTRICT,
                CHECK (latitude >= -90 AND latitude <= 90),
                CHECK (longitude >= -180 AND longitude <= 180)
            )
        ";
    }
}