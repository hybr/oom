<?php

namespace Entities;

/**
 * Person Education Entity
 * Represents education history of a person
 */
class PersonEducation extends BaseEntity
{
    protected ?int $person_id = null;
    protected ?string $institution = null;
    protected ?string $start_date = null;
    protected ?string $complete_date = null;
    protected ?string $education_level = null; // ENUM_EDUCATION_LEVELS

    public static function getTableName(): string
    {
        return 'person_education';
    }

    protected function getFillableAttributes(): array
    {
        return ['person_id', 'institution', 'start_date', 'complete_date', 'education_level'];
    }

    protected function getValidationRules(): array
    {
        return [
            'person_id' => ['required', 'numeric'],
            'institution' => ['required', 'min:2', 'max:200'],
            'education_level' => ['required'],
        ];
    }

    /**
     * Get the person
     */
    public function getPerson(): ?Person
    {
        return Person::find($this->person_id);
    }

    /**
     * Get education subjects
     */
    public function getSubjects(): array
    {
        return PersonEducationSubject::where('person_education_id = :id', ['id' => $this->id]);
    }

    /**
     * Check if education is completed
     */
    public function isCompleted(): bool
    {
        return $this->complete_date !== null && strtotime($this->complete_date) <= time();
    }

    /**
     * Get duration in years
     */
    public function getDurationYears(): ?float
    {
        if (!$this->start_date) {
            return null;
        }

        $endDate = $this->complete_date ?? date('Y-m-d');
        $start = new \DateTime($this->start_date);
        $end = new \DateTime($endDate);
        $diff = $start->diff($end);

        return $diff->y + ($diff->m / 12);
    }

    /**
     * Get education by person
     */
    public static function getByPerson(int $personId): array
    {
        return static::where('person_id = :person_id', ['person_id' => $personId]);
    }

    /**
     * Get education by level
     */
    public static function getByLevel(string $level): array
    {
        return static::where('education_level = :level', ['level' => $level]);
    }
}
