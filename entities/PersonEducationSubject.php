<?php

namespace Entities;

/**
 * Person Education Subject Entity
 * Links education records with subjects and grades
 */
class PersonEducationSubject extends BaseEntity
{
    protected ?int $person_education_id = null;
    protected ?int $subject_id = null;
    protected ?string $marks_type = null; // e.g., 'percentage', 'grade', 'gpa'
    protected ?string $marks = null;

    public static function getTableName(): string
    {
        return 'person_education_subject';
    }

    protected function getFillableAttributes(): array
    {
        return ['person_education_id', 'subject_id', 'marks_type', 'marks'];
    }

    protected function getValidationRules(): array
    {
        return [
            'person_education_id' => ['required', 'numeric'],
            'subject_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Get the education record
     */
    public function getEducation(): ?PersonEducation
    {
        return PersonEducation::find($this->person_education_id);
    }

    /**
     * Get the subject
     */
    public function getSubject(): ?PopularEducationSubject
    {
        return PopularEducationSubject::find($this->subject_id);
    }

    /**
     * Get formatted marks
     */
    public function getFormattedMarks(): string
    {
        if (!$this->marks) {
            return 'N/A';
        }

        switch ($this->marks_type) {
            case 'percentage':
                return $this->marks . '%';
            case 'gpa':
                return 'GPA: ' . $this->marks;
            case 'grade':
                return 'Grade: ' . $this->marks;
            default:
                return $this->marks;
        }
    }
}
