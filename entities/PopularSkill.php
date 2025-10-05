<?php

namespace Entities;

/**
 * Popular Skill Entity
 * Reference data for common skills
 */
class PopularSkill extends BaseEntity
{
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'popular_skill';
    }

    protected function getFillableAttributes(): array
    {
        return ['name'];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:200'],
        ];
    }

    /**
     * Search skills by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name']);
    }
}
