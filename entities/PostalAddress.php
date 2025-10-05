<?php

namespace Entities;

/**
 * Postal Address Entity
 * Represents physical addresses with geographic coordinates
 */
class PostalAddress extends BaseEntity
{
    protected ?string $first_street = null;
    protected ?string $second_street = null;
    protected ?string $area = null;
    protected ?string $city = null;
    protected ?string $state = null;
    protected ?string $pin = null;
    protected ?float $latitude = null;
    protected ?float $longitude = null;
    protected ?int $country_id = null;

    public static function getTableName(): string
    {
        return 'postal_address';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'first_street', 'second_street', 'area', 'city',
            'state', 'pin', 'latitude', 'longitude', 'country_id'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'city' => ['required', 'min:2', 'max:100'],
            'state' => ['max:100'],
            'country_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Get the country for this address
     */
    public function getCountry(): ?Country
    {
        return Country::find($this->country_id);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddress(bool $includeCountry = true): string
    {
        $parts = array_filter([
            $this->first_street,
            $this->second_street,
            $this->area,
            $this->city,
            $this->state,
            $this->pin,
        ]);

        $address = implode(', ', $parts);

        if ($includeCountry) {
            $country = $this->getCountry();
            if ($country) {
                $address .= ', ' . $country->name;
            }
        }

        return $address;
    }

    /**
     * Get one-line address
     */
    public function getOneLine(): string
    {
        return $this->getFormattedAddress();
    }

    /**
     * Get multi-line address
     */
    public function getMultiLine(): array
    {
        $lines = [];

        if ($this->first_street) $lines[] = $this->first_street;
        if ($this->second_street) $lines[] = $this->second_street;
        if ($this->area) $lines[] = $this->area;

        $cityLine = array_filter([$this->city, $this->state, $this->pin]);
        if (!empty($cityLine)) {
            $lines[] = implode(', ', $cityLine);
        }

        $country = $this->getCountry();
        if ($country) {
            $lines[] = $country->name;
        }

        return $lines;
    }

    /**
     * Check if address has coordinates
     */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinates(): ?array
    {
        if (!$this->hasCoordinates()) {
            return null;
        }

        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    /**
     * Calculate distance to another address (in kilometers)
     */
    public function distanceTo(PostalAddress $other): ?float
    {
        if (!$this->hasCoordinates() || !$other->hasCoordinates()) {
            return null;
        }

        $earthRadius = 6371; // km

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($other->latitude);
        $lon2 = deg2rad($other->longitude);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Find addresses near coordinates
     */
    public static function findNear(float $latitude, float $longitude, float $radiusKm = 10, int $limit = 50): array
    {
        // Simple bounding box search (can be improved with spatial indexes)
        $latDelta = $radiusKm / 111; // Approximate km per degree latitude
        $lonDelta = $radiusKm / (111 * cos(deg2rad($latitude)));

        $sql = "SELECT * FROM " . static::getTableName() . "
                WHERE latitude BETWEEN :lat1 AND :lat2
                AND longitude BETWEEN :lon1 AND :lon2
                AND deleted_at IS NULL
                LIMIT :limit";

        $data = \App\Database::fetchAll($sql, [
            'lat1' => $latitude - $latDelta,
            'lat2' => $latitude + $latDelta,
            'lon1' => $longitude - $lonDelta,
            'lon2' => $longitude + $lonDelta,
            'limit' => $limit,
        ]);

        return array_map(fn($row) => static::hydrate($row), $data);
    }

    /**
     * Search addresses
     */
    public static function searchByLocation(string $query): array
    {
        return static::search($query, ['first_street', 'city', 'state', 'area']);
    }
}
