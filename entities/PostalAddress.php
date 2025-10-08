<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PostalAddress Entity
 *
 * Represents physical addresses for organizations, buildings, and locations.
 * Includes geocoding support for mapping and distance calculations.
 */
class PostalAddress extends BaseEntity {
    protected $table = 'postal_addresses';
    protected $fillable = [
        'first_street',
        'second_street',
        'area',
        'city',
        'state',
        'pin',
        'latitude',
        'longitude',
        'country_id',
        'address_type',
        'building_name',
        'floor_number',
        'apartment_unit',
        'landmark',
        'postal_code_extension',
        'delivery_instructions',
        'access_code',
        'is_verified',
        'verified_date',
        'verification_source',
        'is_primary',
        'is_billing',
        'is_shipping',
        'is_public',
        'timezone',
        'elevation',
        'plus_code',
        'what3words',
        'geocoding_accuracy',
        'geocoding_source',
        'last_geocoded_at',
        'address_hash',
        'normalized_address',
        'county',
        'region',
        'district',
        'suburb',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the country for this address
     */
    public function getCountry($addressId) {
        $address = $this->find($addressId);
        if (!$address || !$address['country_id']) {
            return null;
        }

        $sql = "SELECT * FROM countries WHERE id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$address['country_id']]);
    }

    /**
     * Get full formatted address
     */
    public function getFullAddress($addressId) {
        $address = $this->find($addressId);
        if (!$address) {
            return '';
        }

        $parts = [];

        if (!empty($address['building_name'])) {
            $parts[] = $address['building_name'];
        }
        if (!empty($address['apartment_unit'])) {
            $parts[] = $address['apartment_unit'];
        }
        if (!empty($address['first_street'])) {
            $parts[] = $address['first_street'];
        }
        if (!empty($address['second_street'])) {
            $parts[] = $address['second_street'];
        }
        if (!empty($address['area'])) {
            $parts[] = $address['area'];
        }
        if (!empty($address['city'])) {
            $parts[] = $address['city'];
        }
        if (!empty($address['state'])) {
            $parts[] = $address['state'];
        }
        if (!empty($address['pin'])) {
            $parts[] = $address['pin'];
        }

        $country = $this->getCountry($addressId);
        if ($country) {
            $parts[] = $country['name'];
        }

        return implode(', ', $parts);
    }

    /**
     * Get single line address
     */
    public function getSingleLineAddress($addressId) {
        $address = $this->find($addressId);
        if (!$address) {
            return '';
        }

        $parts = array_filter([
            $address['first_street'],
            $address['area'],
            $address['city'],
            $address['state'],
            $address['pin']
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get addresses by country
     */
    public function getAddressesByCountry($countryId) {
        $sql = "SELECT * FROM {$this->table} WHERE country_id = ? AND deleted_at IS NULL ORDER BY city, area";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Get addresses by city
     */
    public function getAddressesByCity($city, $countryId = null) {
        if ($countryId) {
            $sql = "SELECT * FROM {$this->table} WHERE city = ? AND country_id = ? AND deleted_at IS NULL ORDER BY area";
            return $this->query($sql, [$city, $countryId]);
        } else {
            $sql = "SELECT * FROM {$this->table} WHERE city = ? AND deleted_at IS NULL ORDER BY area";
            return $this->query($sql, [$city]);
        }
    }

    /**
     * Get addresses by state/province
     */
    public function getAddressesByState($state, $countryId = null) {
        if ($countryId) {
            $sql = "SELECT * FROM {$this->table} WHERE state = ? AND country_id = ? AND deleted_at IS NULL ORDER BY city, area";
            return $this->query($sql, [$state, $countryId]);
        } else {
            $sql = "SELECT * FROM {$this->table} WHERE state = ? AND deleted_at IS NULL ORDER BY city, area";
            return $this->query($sql, [$state]);
        }
    }

    /**
     * Get verified addresses
     */
    public function getVerifiedAddresses() {
        $sql = "SELECT * FROM {$this->table} WHERE is_verified = 1 AND deleted_at IS NULL ORDER BY city, area";
        return $this->query($sql);
    }

    /**
     * Get addresses by type
     */
    public function getAddressesByType($type) {
        $sql = "SELECT * FROM {$this->table} WHERE address_type = ? AND deleted_at IS NULL ORDER BY city, area";
        return $this->query($sql, [$type]);
    }

    /**
     * Search addresses
     */
    public function searchAddresses($term, $limit = 50) {
        $sql = "SELECT * FROM {$this->table}
                WHERE (first_street LIKE ? OR second_street LIKE ? OR area LIKE ? OR city LIKE ? OR state LIKE ? OR pin LIKE ?)
                AND deleted_at IS NULL
                ORDER BY city, area
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", "%$term%", "%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Calculate distance between two addresses (in kilometers)
     */
    public function calculateDistance($address1Id, $address2Id) {
        $address1 = $this->find($address1Id);
        $address2 = $this->find($address2Id);

        if (!$address1 || !$address2 || !$address1['latitude'] || !$address1['longitude'] ||
            !$address2['latitude'] || !$address2['longitude']) {
            return null;
        }

        $lat1 = deg2rad($address1['latitude']);
        $lon1 = deg2rad($address1['longitude']);
        $lat2 = deg2rad($address2['latitude']);
        $lon2 = deg2rad($address2['longitude']);

        $earthRadius = 6371; // kilometers

        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos($lat1) * cos($lat2) *
             sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Find addresses within radius (in kilometers)
     */
    public function findAddressesWithinRadius($latitude, $longitude, $radiusKm, $limit = 50) {
        // Simple bounding box calculation for performance
        $latRange = $radiusKm / 111; // 1 degree latitude ≈ 111 km
        $lonRange = $radiusKm / (111 * cos(deg2rad($latitude)));

        $minLat = $latitude - $latRange;
        $maxLat = $latitude + $latRange;
        $minLon = $longitude - $lonRange;
        $maxLon = $longitude + $lonRange;

        $sql = "SELECT * FROM {$this->table}
                WHERE latitude BETWEEN ? AND ?
                AND longitude BETWEEN ? AND ?
                AND deleted_at IS NULL
                LIMIT ?";

        return $this->query($sql, [$minLat, $maxLat, $minLon, $maxLon, $limit]);
    }

    /**
     * Geocode address (set latitude/longitude)
     */
    public function geocodeAddress($addressId, $latitude, $longitude, $accuracy = null, $source = 'manual') {
        $sql = "UPDATE {$this->table}
                SET latitude = ?, longitude = ?, geocoding_accuracy = ?, geocoding_source = ?,
                    last_geocoded_at = datetime('now'), updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$latitude, $longitude, $accuracy, $source, $addressId]);
    }

    /**
     * Mark address as verified
     */
    public function markAsVerified($addressId, $source = 'manual') {
        $sql = "UPDATE {$this->table}
                SET is_verified = 1, verified_date = datetime('now'), verification_source = ?, updated_at = datetime('now')
                WHERE id = ?";
        return $this->db->update($sql, [$source, $addressId]);
    }

    /**
     * Set as primary address
     */
    public function setAsPrimary($addressId) {
        $sql = "UPDATE {$this->table} SET is_primary = 1, updated_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [$addressId]);
    }

    /**
     * Generate address hash for deduplication
     */
    public function generateAddressHash($addressId) {
        $address = $this->find($addressId);
        if (!$address) {
            return null;
        }

        $normalized = strtolower(trim(implode('|', [
            $address['first_street'],
            $address['area'],
            $address['city'],
            $address['state'],
            $address['pin'],
            $address['country_id']
        ])));

        $hash = hash('sha256', $normalized);

        $sql = "UPDATE {$this->table} SET address_hash = ?, normalized_address = ?, updated_at = datetime('now') WHERE id = ?";
        $this->db->update($sql, [$hash, $normalized, $addressId]);

        return $hash;
    }

    /**
     * Find duplicate addresses
     */
    public function findDuplicates($addressId) {
        $address = $this->find($addressId);
        if (!$address || !$address['address_hash']) {
            return [];
        }

        $sql = "SELECT * FROM {$this->table}
                WHERE address_hash = ? AND id != ? AND deleted_at IS NULL";
        return $this->query($sql, [$address['address_hash'], $addressId]);
    }

    /**
     * Get addresses by timezone
     */
    public function getAddressesByTimezone($timezone) {
        $sql = "SELECT * FROM {$this->table} WHERE timezone = ? AND deleted_at IS NULL ORDER BY city";
        return $this->query($sql, [$timezone]);
    }

    /**
     * Get public addresses
     */
    public function getPublicAddresses() {
        $sql = "SELECT * FROM {$this->table} WHERE is_public = 1 AND deleted_at IS NULL ORDER BY city, area";
        return $this->query($sql);
    }

    /**
     * Get billing addresses
     */
    public function getBillingAddresses() {
        $sql = "SELECT * FROM {$this->table} WHERE is_billing = 1 AND deleted_at IS NULL ORDER BY city, area";
        return $this->query($sql);
    }

    /**
     * Get shipping addresses
     */
    public function getShippingAddresses() {
        $sql = "SELECT * FROM {$this->table} WHERE is_shipping = 1 AND deleted_at IS NULL ORDER BY city, area";
        return $this->query($sql);
    }

    /**
     * Get coordinates
     */
    public function getCoordinates($addressId) {
        $address = $this->find($addressId);
        if (!$address || !$address['latitude'] || !$address['longitude']) {
            return null;
        }

        return [
            'latitude' => $address['latitude'],
            'longitude' => $address['longitude']
        ];
    }

    /**
     * Validate address data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'country_id' => 'required|exists:countries,id',
            'city' => 'required|min:2|max:100',
            'state' => 'max:100',
            'pin' => 'max:20',
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        return $this->getSingleLineAddress($id);
    }
}
