<?php

namespace Entities;

/**
 * Language Entity
 * Represents languages with country association
 */
class Language extends BaseEntity
{
    protected ?string $name = null;
    protected ?int $country_id = null;

    public static function getTableName(): string
    {
        return 'language';
    }

    protected function getFillableAttributes(): array
    {
        return ['name', 'country_id'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:100'],
            'country_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Get the country this language is associated with
     */
    public function getCountry(): ?Country
    {
        return Country::find($this->country_id);
    }

    /**
     * Get language with country name
     */
    public function getFullName(): string
    {
        $country = $this->getCountry();
        return $this->name . ($country ? " ({$country->name})" : '');
    }

    /**
     * Search languages by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name']);
    }

    /**
     * Get languages by country
     */
    public static function getByCountry(int $countryId): array
    {
        return static::where('country_id = :country_id', ['country_id' => $countryId]);
    }
}
