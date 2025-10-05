<?php

namespace Entities;

/**
 * Popular Education Subject Entity
 * Reference data for common education subjects
 */
class PopularEducationSubject extends BaseEntity
{
    protected ?string $name = null;

    public static function getTableName(): string
    {
        return 'popular_education_subject';
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
     * Search subjects by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['name']);
    }
}
