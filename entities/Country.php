<?php

namespace Entities;

/**
 * Country Entity
 * Represents countries with continent association
 */
class Country extends BaseEntity
{
    protected ?string $name = null;
    protected ?int $continent_id = null;

    public static function getTableName(): string
    {
        return 'country';
    }

    protected function getFillableAttributes(): array
    {
        return ['name', 'continent_id'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:100'],
            'continent_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Get the continent this country belongs to
     */
    public function getContinent(): ?Continent
    {
        return Continent::find($this->continent_id);
    }

    /**
     * Get all languages spoken in this country
     */
    public function getLanguages(): array
    {
        return Language::where('country_id = :country_id', ['country_id' => $this->id]);
    }

    /**
     * Get all postal addresses in this country
     */
    public function getPostalAddresses(): array
    {
        return PostalAddress::where('country_id = :country_id', ['country_id' => $this->id]);
    }

    /**
     * Count languages in this country
     */
    public function countLanguages(): int
    {
        return Language::count('country_id = :country_id', ['country_id' => $this->id]);
    }

    /**
     * Search countries by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name']);
    }

    /**
     * Get countries by continent
     */
    public static function getByContinent(int $continentId): array
    {
        return static::where('continent_id = :continent_id', ['continent_id' => $continentId]);
    }
}
