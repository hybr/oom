<?php

namespace Entities;

/**
 * Person Entity
 * Represents an individual with personal information
 */
class Person extends BaseEntity
{
    protected ?string $first_name = null;
    protected ?string $middle_name = null;
    protected ?string $last_name = null;
    protected ?string $date_of_birth = null;

    public static function getTableName(): string
    {
        return 'person';
    }

    protected function getFillableAttributes(): array
    {
        return ['first_name', 'middle_name', 'last_name', 'date_of_birth'];
    }

    protected function getValidationRules(): array
    {
        return [
            'first_name' => ['required', 'min:2', 'max:100'],
            'last_name' => ['required', 'min:2', 'max:100'],
        ];
    }

    /**
     * Get full name
     */
    public function getFullName(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Get initials
     */
    public function getInitials(): string
    {
        $initials = substr($this->first_name, 0, 1);

        if ($this->middle_name) {
            $initials .= substr($this->middle_name, 0, 1);
        }

        if ($this->last_name) {
            $initials .= substr($this->last_name, 0, 1);
        }

        return strtoupper($initials);
    }

    /**
     * Get age
     */
    public function getAge(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        $dob = new \DateTime($this->date_of_birth);
        $now = new \DateTime();
        $diff = $now->diff($dob);

        return $diff->y;
    }

    /**
     * Get credential for this person
     */
    public function getCredential(): ?Credential
    {
        $credentials = Credential::where('person_id = :person_id', ['person_id' => $this->id], 1);
        return $credentials[0] ?? null;
    }

    /**
     * Get all education records
     */
    public function getEducation(): array
    {
        return PersonEducation::where('person_id = :person_id', ['person_id' => $this->id]);
    }

    /**
     * Get all skills
     */
    public function getSkills(): array
    {
        return PersonSkill::where('person_id = :person_id', ['person_id' => $this->id]);
    }

    /**
     * Check if person has credential
     */
    public function hasCredential(): bool
    {
        return $this->getCredential() !== null;
    }

    /**
     * Search persons by name
     */
    public static function searchByName(string $query): array
    {
        return static::search($query, ['first_name', 'middle_name', 'last_name']);
    }

    /**
     * Get persons by birth year
     */
    public static function getByBirthYear(int $year): array
    {
        return static::where('strftime("%Y", date_of_birth) = :year', ['year' => (string)$year]);
    }

    /**
     * Get persons by age range
     */
    public static function getByAgeRange(int $minAge, int $maxAge): array
    {
        $maxDate = date('Y-m-d', strtotime("-{$minAge} years"));
        $minDate = date('Y-m-d', strtotime("-{$maxAge} years"));

        return static::where(
            'date_of_birth BETWEEN :min_date AND :max_date',
            ['min_date' => $minDate, 'max_date' => $maxDate]
        );
    }
}
