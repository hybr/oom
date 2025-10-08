# Foreign Key Label Implementation

## Summary
Implemented a reusable component that makes foreign key field labels clickable links to the corresponding entity's detail page.

## What Was Done

### 1. Created FK Label Component
**File:** `views/components/fk-label.php`

This component creates clickable labels with:
- Icon support (Bootstrap Icons)
- Links to entity list or detail pages
- Required field indicator (*)
- Opens in new tab (preserves form state)
- Tooltip on hover

### 2. Updated Sample Forms
**Updated Files:**
- `public/pages/entities/credentials/create.php`
- `public/pages/entities/person_skills/create.php`

These now use the FK label component for all foreign key fields.

### 3. Created Documentation
**File:** `docs/FK_LABEL_COMPONENT.md`

Comprehensive guide including:
- Usage examples
- Parameter reference
- Common entity/icon mappings
- Migration guide

### 4. Created Analysis Script
**File:** `scripts/update-fk-labels.php`

This script:
- Scans all create/edit forms
- Identifies FK fields
- Provides ready-to-use code snippets
- Shows summary statistics

**Run:** `php scripts/update-fk-labels.php`

## Usage Example

### Before
```php
<label for="person_id" class="form-label">Person <span class="text-danger">*</span></label>
<select id="person_id" name="person_id" required>
    <!-- options -->
</select>
```

### After
```php
<?php
$fk_label = 'Person';
$fk_for = 'person_id';
$fk_entity = 'persons';
$fk_required = true;
$fk_icon = 'bi-person';
include __DIR__ . '/../../../../views/components/fk-label.php';
?>
<select id="person_id" name="person_id" required>
    <!-- options -->
</select>
```

### Result
The label "Person *" becomes a clickable link with an icon:
- Create form: Links to `/persons` (entity list)
- Edit form: Links to `/persons/5` (specific entity) when `$fk_id = 5` is set

## Current Status

### Completed
- ✅ FK label component created
- ✅ Component documentation written
- ✅ Analysis script created
- ✅ 2 forms updated as examples (credentials, person_skills)

### Pending (17 forms total)
Run `php scripts/update-fk-labels.php` to see which forms need updating.

**Forms identified:**
- countries/create.php (continent_id)
- employment_contracts/create.php (organization_id)
- interview_stages/create.php (organization_id)
- languages/create.php (country_id)
- organization_branches/create.php (organization_id)
- organization_vacancies/create.php (organization_id)
- person_education/create.php (person_id)
- person_education_subjects/create.php (subject_id)
- postal_addresses/create.php (country_id)
- vacancy_applications/create.php (vacancy_id)
- workstations/create.php (building_id)
- And more...

### Recommendation
The script provides ready-to-use code snippets for each form. Forms can be updated incrementally as needed.

## Benefits

1. **Better Navigation**: Users can click labels to view related entities
2. **Preserved Context**: Links open in new tab, keeping form state
3. **Visual Consistency**: Standard icons and styling
4. **Maintainability**: Single component to update
5. **User Experience**: Quick access to foreign entity details

## Common FK Mappings

| Field | Entity Route | Label | Icon |
|-------|-------------|-------|------|
| person_id | persons | Person | bi-person |
| organization_id | organizations | Organization | bi-building |
| country_id | countries | Country | bi-flag |
| continent_id | continents | Continent | bi-globe-americas |
| language_id | languages | Language | bi-translate |
| skill_id | popular_skills | Skill | bi-lightbulb |
| subject_id | popular_skills | Skill | bi-lightbulb |
| department_id | popular_organization_departments | Department | bi-diagram-2 |
| branch_id | organization_branches | Branch | bi-diagram-3 |
| building_id | organization_buildings | Building | bi-house |
| workstation_id | workstations | Workstation | bi-pc-display |
| vacancy_id | organization_vacancies | Vacancy | bi-megaphone |

## Next Steps

To update remaining forms:

1. Run analysis script:
   ```bash
   php scripts/update-fk-labels.php
   ```

2. For each form, replace old label with component code provided

3. Test the form to ensure links work correctly

4. For edit forms, add `$fk_id = $entity->field_name` to link to specific entity
