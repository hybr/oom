# Foreign Key Label Component

## Overview
The FK Label component creates clickable labels for foreign key fields that link to the corresponding entity's detail page. This improves navigation and user experience by allowing users to quickly view related records.

## Location
`views/components/fk-label.php`

## Usage

### Basic Usage (Create Form)
```php
<div class="mb-3">
    <?php
    $fk_label = 'Person';           // Display name
    $fk_for = 'person_id';          // Field ID
    $fk_entity = 'persons';         // Entity route name
    $fk_required = true;            // Show asterisk
    $fk_icon = 'bi-person';         // Bootstrap icon
    include __DIR__ . '/../../../../views/components/fk-label.php';
    ?>
    <select class="form-select" id="person_id" name="person_id" required>
        <!-- options -->
    </select>
</div>
```

### Advanced Usage (Edit Form)
When editing, you can link directly to the current foreign entity:

```php
<div class="mb-3">
    <?php
    $fk_label = 'Person';
    $fk_for = 'person_id';
    $fk_entity = 'persons';
    $fk_id = $entity->person_id;    // Link to specific person
    $fk_required = true;
    $fk_icon = 'bi-person';
    include __DIR__ . '/../../../../views/components/fk-label.php';
    ?>
    <select class="form-select" id="person_id" name="person_id" required>
        <!-- options -->
    </select>
</div>
```

## Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `$fk_label` | string | Yes | 'Foreign Key' | The display text for the label |
| `$fk_for` | string | Yes | 'fk_id' | The `for` attribute (matches input/select ID) |
| `$fk_entity` | string | No | null | The entity route name (e.g., 'persons', 'organizations') |
| `$fk_id` | int | No | null | Specific entity ID to link to (for edit forms) |
| `$fk_required` | bool | No | false | Whether to show red asterisk |
| `$fk_icon` | string | No | 'bi-link-45deg' | Bootstrap icon class |

## Examples

### Example 1: Person FK in Credential Form
```php
<?php
$fk_label = 'Person';
$fk_for = 'person_id';
$fk_entity = 'persons';
$fk_required = true;
$fk_icon = 'bi-person';
include __DIR__ . '/../../../../views/components/fk-label.php';
?>
```

**Output (Create):** `Person *` (links to `/persons`)
**Output (Edit with ID=5):** `Person *` (links to `/persons/5`)

### Example 2: Organization FK
```php
<?php
$fk_label = 'Organization';
$fk_for = 'organization_id';
$fk_entity = 'organizations';
$fk_id = $entity->organization_id ?? null;
$fk_required = true;
$fk_icon = 'bi-building';
include __DIR__ . '/../../../../views/components/fk-label.php';
?>
```

### Example 3: Optional FK (No Asterisk)
```php
<?php
$fk_label = 'Manager';
$fk_for = 'manager_id';
$fk_entity = 'persons';
$fk_icon = 'bi-person-badge';
// $fk_required defaults to false
include __DIR__ . '/../../../../views/components/fk-label.php';
?>
```

## Common FK Entities and Icons

| Entity | Route Name | Icon |
|--------|-----------|------|
| Person | `persons` | `bi-person` |
| Organization | `organizations` | `bi-building` |
| Skill | `popular_skills` | `bi-lightbulb` |
| Department | `popular_organization_departments` | `bi-diagram-2` |
| Country | `countries` | `bi-flag` |
| Language | `languages` | `bi-translate` |
| Continent | `continents` | `bi-globe-americas` |
| Vacancy | `organization_vacancies` | `bi-megaphone` |
| Workstation | `workstations` | `bi-pc-display` |

## Features

1. **Clickable Link**: Opens the entity list or detail page in a new tab
2. **Icon Support**: Uses Bootstrap Icons for visual consistency
3. **Required Indicator**: Shows red asterisk when field is required
4. **Tooltip**: "View [Entity] details" on hover
5. **New Tab**: Opens in new tab to preserve form state
6. **Variable Cleanup**: Clears all variables after use to prevent pollution

## Benefits

1. **Better UX**: Users can quickly view related records without losing form context
2. **Navigation**: Easy access to foreign entity details
3. **Consistency**: Standardized appearance across all forms
4. **Maintainability**: Single component to update styling/behavior

## Migration Guide

### Before (Old Way)
```php
<label for="person_id" class="form-label">Person <span class="text-danger">*</span></label>
```

### After (New Way)
```php
<?php
$fk_label = 'Person';
$fk_for = 'person_id';
$fk_entity = 'persons';
$fk_required = true;
$fk_icon = 'bi-person';
include __DIR__ . '/../../../../views/components/fk-label.php';
?>
```
