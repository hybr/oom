<?php

namespace Entities;

/**
 * Person Skill Entity
 * Represents skills possessed by a person
 */
class PersonSkill extends BaseEntity
{
    protected ?int $person_id = null;
    protected ?int $subject_id = null; // references PopularSkill
    protected ?string $institution = null;
    protected ?string $level = null; // e.g., 'beginner', 'intermediate', 'expert'
    protected ?string $start_date = null;
    protected ?string $complete_date = null;
    protected ?string $marks_type = null;
    protected ?string $marks = null;

    public static function getTableName(): string
    {
        return 'person_skill';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'person_id', 'subject_id', 'institution', 'level',
            'start_date', 'complete_date', 'marks_type', 'marks'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'person_id' => ['required', 'numeric'],
            'subject_id' => ['required', 'numeric'],
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
     * Get the skill
     */
    public function getSkill(): ?PopularSkill
    {
        return PopularSkill::find($this->subject_id);
    }

    /**
     * Get skill name
     */
    public function getSkillName(): string
    {
        $skill = $this->getSkill();
        return $skill ? $skill->name : 'Unknown';
    }

    /**
     * Get formatted level
     */
    public function getFormattedLevel(): string
    {
        return ucfirst($this->level ?? 'Not specified');
    }

    /**
     * Check if skill is certified
     */
    public function isCertified(): bool
    {
        return $this->institution !== null && $this->complete_date !== null;
    }

    /**
     * Get skills by person
     */
    public static function getByPerson(int $personId): array
    {
        return static::where('person_id = :person_id', ['person_id' => $personId]);
    }

    /**
     * Get skills by level
     */
    public static function getByLevel(string $level): array
    {
        return static::where('level = :level', ['level' => $level]);
    }
}
