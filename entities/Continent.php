<?php

namespace Entities;

/**
 * Continent Entity
 * Represents global continents
 */
class Continent extends BaseEntity
{
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'continent';
    }

    protected function getFillableAttributes(): array
    {
        return ['name'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:100'],
        ];
    }

    /**
     * Get all countries in this continent
     */
    public function getCountries(): array
    {
        return Country::where('continent_id = :continent_id', ['continent_id' => $this->id]);
    }

    /**
     * Count countries in this continent
     */
    public function countCountries(): int
    {
        return Country::count('continent_id = :continent_id', ['continent_id' => $this->id]);
    }

    /**
     * Search continents by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name']);
    }
}
