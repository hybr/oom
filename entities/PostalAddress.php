<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * PostalAddress Entity
 * Represents physical addresses with geographic coordinates
 */
class PostalAddress extends BaseEntity {
    protected $table = 'postal_addresses';
    protected $fillable = [
        'first_street', 'second_street', 'area', 'city', 'state', 'pin',
        'latitude', 'longitude', 'country_id'
    ];

    /**
     * Get country for this address
     */
    public function getCountry($addressId) {
        $sql = "SELECT c.* FROM countries c
                JOIN postal_addresses pa ON pa.country_id = c.id
                WHERE pa.id = ? AND c.deleted_at IS NULL";
        return $this->queryOne($sql, [$addressId]);
    }

    /**
     * Get full formatted address
     */
    public function getFormattedAddress($addressId) {
        $address = $this->find($addressId);
        if (!$address) {
            return 'N/A';
        }

        $parts = [];
        if (!empty($address['first_street'])) $parts[] = $address['first_street'];
        if (!empty($address['second_street'])) $parts[] = $address['second_street'];
        if (!empty($address['area'])) $parts[] = $address['area'];
        if (!empty($address['city'])) $parts[] = $address['city'];
        if (!empty($address['state'])) $parts[] = $address['state'];
        if (!empty($address['pin'])) $parts[] = $address['pin'];

        return implode(', ', $parts);
    }

    /**
     * Get addresses by city
     */
    public function getByCity($city, $countryId = null) {
        $sql = "SELECT * FROM postal_addresses WHERE city = ? AND deleted_at IS NULL";
        $params = [$city];

        if ($countryId) {
            $sql .= " AND country_id = ?";
            $params[] = $countryId;
        }

        $sql .= " ORDER BY area, first_street";
        return $this->query($sql, $params);
    }

    /**
     * Get addresses by state
     */
    public function getByState($state, $countryId = null) {
        $sql = "SELECT * FROM postal_addresses WHERE state = ? AND deleted_at IS NULL";
        $params = [$state];

        if ($countryId) {
            $sql .= " AND country_id = ?";
            $params[] = $countryId;
        }

        $sql .= " ORDER BY city, area";
        return $this->query($sql, $params);
    }

    /**
     * Get addresses by postal code
     */
    public function getByPostalCode($pin) {
        return $this->all(['pin' => $pin], 'city ASC, area ASC');
    }

    /**
     * Get addresses within radius (using Haversine formula)
     */
    public function getWithinRadius($latitude, $longitude, $radiusKm = 10) {
        $sql = "SELECT *,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                sin(radians(latitude)))) AS distance
                FROM postal_addresses
                WHERE deleted_at IS NULL
                AND latitude IS NOT NULL
                AND longitude IS NOT NULL
                HAVING distance <= ?
                ORDER BY distance ASC";

        return $this->query($sql, [$latitude, $longitude, $latitude, $radiusKm]);
    }

    /**
     * Get nearest address to coordinates
     */
    public function getNearestAddress($latitude, $longitude) {
        $addresses = $this->getWithinRadius($latitude, $longitude, 50);
        return !empty($addresses) ? $addresses[0] : null;
    }

    /**
     * Get addresses with coordinates
     */
    public function getWithCoordinates($limit = null) {
        $sql = "SELECT * FROM postal_addresses
                WHERE latitude IS NOT NULL AND longitude IS NOT NULL
                AND deleted_at IS NULL
                ORDER BY id DESC";

        if ($limit) {
            $sql .= " LIMIT ?";
            return $this->query($sql, [$limit]);
        }

        return $this->query($sql);
    }

    /**
     * Get addresses without coordinates
     */
    public function getWithoutCoordinates($limit = null) {
        $sql = "SELECT * FROM postal_addresses
                WHERE (latitude IS NULL OR longitude IS NULL)
                AND deleted_at IS NULL
                ORDER BY id DESC";

        if ($limit) {
            $sql .= " LIMIT ?";
            return $this->query($sql, [$limit]);
        }

        return $this->query($sql);
    }

    /**
     * Get unique cities in country
     */
    public function getCitiesByCountry($countryId) {
        $sql = "SELECT DISTINCT city FROM postal_addresses
                WHERE country_id = ? AND city IS NOT NULL AND deleted_at IS NULL
                ORDER BY city ASC";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Get unique states in country
     */
    public function getStatesByCountry($countryId) {
        $sql = "SELECT DISTINCT state FROM postal_addresses
                WHERE country_id = ? AND state IS NOT NULL AND deleted_at IS NULL
                ORDER BY state ASC";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Calculate distance between two addresses
     */
    public function calculateDistance($addressId1, $addressId2) {
        $addr1 = $this->find($addressId1);
        $addr2 = $this->find($addressId2);

        if (!$addr1 || !$addr2 || !$addr1['latitude'] || !$addr2['latitude']) {
            return null;
        }

        $lat1 = deg2rad($addr1['latitude']);
        $lon1 = deg2rad($addr1['longitude']);
        $lat2 = deg2rad($addr2['latitude']);
        $lon2 = deg2rad($addr2['longitude']);

        $earthRadius = 6371; // km

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get address statistics by country
     */
    public function getStatisticsByCountry($countryId) {
        $sql = "SELECT
                    COUNT(*) as total_addresses,
                    COUNT(DISTINCT city) as unique_cities,
                    COUNT(DISTINCT state) as unique_states,
                    COUNT(CASE WHEN latitude IS NOT NULL THEN 1 END) as addresses_with_coordinates
                FROM postal_addresses
                WHERE country_id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$countryId]);
    }

    /**
     * Validate postal address data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'first_street' => 'required|min:3|max:200',
            'second_street' => 'max:200',
            'area' => 'max:100',
            'city' => 'required|min:2|max:100',
            'state' => 'max:100',
            'pin' => 'max:20',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'country_id' => 'required|integer',
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Override getLabel to return formatted address
     */
    public function getLabel($id) {
        return $this->getFormattedAddress($id);
    }
}
