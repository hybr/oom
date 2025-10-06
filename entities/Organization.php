<?php

namespace Entities;

class Organization extends BaseEntity
{
    protected ?string $short_name = null;
    protected ?string $tag_line = null;
    protected ?string $website = null;
    protected ?string $subdomain = null;
    protected ?int $admin_id = null;
    protected ?int $industry_id = null;
    protected ?int $legal_category_id = null;

    public static function getTableName(): string
    {
        return 'organization';
    }

    protected function getFillableAttributes(): array
    {
        return ['short_name', 'tag_line', 'website', 'subdomain', 'admin_id', 'industry_id', 'legal_category_id'];
    }

    protected function getValidationRules(): array
    {
        return [
            'short_name' => ['required', 'min:2', 'max:200'],
        ];
    }

    public function getOrganizationFullName(): string
    {
        $legalCategory = $this->getLegalCategory();
        return $this->short_name . ($legalCategory ? ' ' . $legalCategory->name : '');
    }

    public function getAdmin(): ?Person
    {
        return Person::find($this->admin_id);
    }

    public function getIndustry(): ?IndustryCategory
    {
        return IndustryCategory::find($this->industry_id);
    }

    public function getLegalCategory(): ?OrganizationLegalCategory
    {
        return OrganizationLegalCategory::find($this->legal_category_id);
    }

    public function getBranches(): array
    {
        return OrganizationBranch::where('organization_id = :org_id', ['org_id' => $this->id]);
    }
}
