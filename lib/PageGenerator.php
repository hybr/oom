<?php
/**
 * Page Generator - Auto-generates CRUD pages from metadata
 */

class PageGenerator
{
    private $entity;
    private $attributes;
    private $relationships;

    public function __construct($entityCode)
    {
        $this->entity = EntityManager::getEntity($entityCode);
        if (!$this->entity) {
            throw new Exception("Entity not found: {$entityCode}");
        }

        $this->attributes = EntityManager::getAttributes($this->entity['id']);
        $this->relationships = EntityManager::getRelationships($this->entity['id']);
    }

    /**
     * Generate list view HTML
     */
    public function generateListView($records, $totalCount, $page = 1, $perPage = 25)
    {
        $entityName = $this->entity['name'];
        $entityCode = $this->entity['code'];

        $html = '<div class="container-fluid mt-4">';
        $html .= '<div class="d-flex justify-content-between align-items-center mb-3">';
        $html .= "<h2>{$entityName} List</h2>";
        $html .= '<a href="/entities/' . strtolower($entityCode) . '/create" class="btn btn-primary">Create New</a>';
        $html .= '</div>';

        // Table
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-striped table-hover">';
        $html .= '<thead class="table-dark"><tr>';

        // Show first 5 attributes (no ID column)
        $displayAttrs = array_slice($this->attributes, 0, 5);
        foreach ($displayAttrs as $attr) {
            $html .= '<th>' . htmlspecialchars($attr['name']) . '</th>';
        }

        $html .= '<th>Actions</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($records as $record) {
            $html .= '<tr>';

            foreach ($displayAttrs as $attr) {
                $value = $record[$attr['code']] ?? '';

                // Check if this attribute is a foreign key
                $fkLabel = $this->resolveForeignKeyLabel($attr['code'], $value);
                if ($fkLabel !== null) {
                    $value = $fkLabel;
                }

                if (strlen($value) > 50) {
                    $value = substr($value, 0, 50) . '...';
                }
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }

            $html .= '<td>';
            $html .= '<a href="/entities/' . strtolower($entityCode) . '/detail/' . $record['id'] . '" class="btn btn-sm btn-info">View</a> ';
            $html .= '<a href="/entities/' . strtolower($entityCode) . '/edit/' . $record['id'] . '" class="btn btn-sm btn-warning">Edit</a> ';
            $html .= '<button onclick="deleteRecord(\'' . $record['id'] . '\')" class="btn btn-sm btn-danger">Delete</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '</div>';

        // Pagination
        $totalPages = ceil($totalCount / $perPage);
        if ($totalPages > 1) {
            $html .= '<nav><ul class="pagination">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i == $page ? 'active' : '';
                $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }
            $html .= '</ul></nav>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate detail view HTML
     */
    public function generateDetailView($record)
    {
        $entityName = $this->entity['name'];
        $entityCode = $this->entity['code'];

        $html = '<div class="container-fluid mt-4">';
        $html .= '<div class="d-flex justify-content-between align-items-center mb-3">';
        $html .= "<h2>{$entityName} Details</h2>";
        $html .= '<div>';
        $html .= '<a href="/entities/' . strtolower($entityCode) . '/edit/' . $record['id'] . '" class="btn btn-warning">Edit</a> ';
        $html .= '<a href="/entities/' . strtolower($entityCode) . '/list" class="btn btn-secondary">Back to List</a>';
        $html .= '</div></div>';

        $html .= '<div class="card">';
        $html .= '<div class="card-body">';
        $html .= '<dl class="row">';

        // Display all attributes
        foreach ($this->attributes as $attr) {
            $value = $record[$attr['code']] ?? '';
            $html .= '<dt class="col-sm-3">' . htmlspecialchars($attr['name']) . ':</dt>';
            $html .= '<dd class="col-sm-9">' . htmlspecialchars($value) . '</dd>';
        }

        // System fields
        $html .= '<dt class="col-sm-3">Created At:</dt>';
        $html .= '<dd class="col-sm-9">' . htmlspecialchars($record['created_at']) . '</dd>';
        $html .= '<dt class="col-sm-3">Updated At:</dt>';
        $html .= '<dd class="col-sm-9">' . htmlspecialchars($record['updated_at']) . '</dd>';

        $html .= '</dl>';
        $html .= '</div></div>';

        // Related entities
        if (!empty($this->relationships)) {
            $html .= '<div class="mt-4">';
            $html .= '<h4>Related Records</h4>';
            foreach ($this->relationships as $rel) {
                $toEntity = EntityManager::getEntityById($rel['to_entity_id']);
                $html .= '<div class="card mb-2">';
                $html .= '<div class="card-header">' . htmlspecialchars($rel['relation_name']) . '</div>';
                $html .= '<div class="card-body">';
                $html .= '<a href="/entities/' . strtolower($toEntity['code']) . '/list?filter=' . $rel['fk_field'] . '=' . $record['id'] . '" class="btn btn-sm btn-primary">View ' . htmlspecialchars($toEntity['name']) . ' Records</a>';
                $html .= '</div></div>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate form HTML for create/edit
     */
    public function generateForm($record = null, $action = 'create')
    {
        $entityName = $this->entity['name'];
        $entityCode = $this->entity['code'];
        $isEdit = $action === 'edit';

        $html = '<div class="container-fluid mt-4">';
        $html .= '<h2>' . ($isEdit ? 'Edit' : 'Create') . ' ' . $entityName . '</h2>';

        $formAction = $isEdit ? "/entities/" . strtolower($entityCode) . "/update" : "/entities/" . strtolower($entityCode) . "/store";
        $html .= '<form method="POST" action="' . $formAction . '" class="needs-validation" novalidate>';

        // CSRF token
        $html .= '<input type="hidden" name="csrf_token" value="' . Auth::generateCsrfToken() . '">';

        if ($isEdit && $record) {
            $html .= '<input type="hidden" name="id" value="' . htmlspecialchars($record['id']) . '">';
        }

        $html .= '<div class="row">';

        // Generate form fields
        foreach ($this->attributes as $attr) {
            if ($attr['is_system'] == 1) {
                continue;
            }

            $value = $isEdit && $record ? ($record[$attr['code']] ?? '') : ($attr['default_value'] ?? '');
            $required = $attr['is_required'] == 1 ? 'required' : '';

            $html .= '<div class="col-md-6 mb-3">';

            // Check if this is a foreign key to add link to label
            $isForeignKey = false;
            $fkRelationship = null;
            foreach ($this->relationships as $rel) {
                if ($rel['fk_field'] === $attr['code']) {
                    $isForeignKey = true;
                    $fkRelationship = $rel;
                    break;
                }
            }

            // Generate label with link for FK fields
            if ($isForeignKey && $fkRelationship) {
                // Determine target entity
                $targetEntityId = ($this->entity['id'] === $fkRelationship['from_entity_id'])
                    ? $fkRelationship['to_entity_id']
                    : $fkRelationship['from_entity_id'];

                $targetEntity = EntityManager::getEntityById($targetEntityId);
                if ($targetEntity) {
                    $targetEntityUrl = '/entities/' . strtolower($targetEntity['code']) . '/list';
                    $html .= '<label for="' . $attr['code'] . '" class="form-label">';
                    $html .= '<a href="' . $targetEntityUrl . '" class="text-decoration-none" target="_blank" title="View ' . htmlspecialchars($targetEntity['name']) . ' list">';
                    $html .= htmlspecialchars($attr['name']);
                    $html .= ' <i class="bi bi-box-arrow-up-right" style="font-size: 0.8em;"></i>';
                    $html .= '</a>';
                    if ($required) {
                        $html .= ' <span class="text-danger">*</span>';
                    }
                    $html .= '</label>';
                } else {
                    // Fallback if target entity not found
                    $html .= '<label for="' . $attr['code'] . '" class="form-label">' . htmlspecialchars($attr['name']);
                    if ($required) {
                        $html .= ' <span class="text-danger">*</span>';
                    }
                    $html .= '</label>';
                }
            } else {
                // Regular label for non-FK fields
                $html .= '<label for="' . $attr['code'] . '" class="form-label">' . htmlspecialchars($attr['name']);
                if ($required) {
                    $html .= ' <span class="text-danger">*</span>';
                }
                $html .= '</label>';
            }

            // Generate field
            if ($isForeignKey && $fkRelationship) {
                $html .= $this->generateForeignKeySelect($fkRelationship, $value, $required);
            }

            if (!$isForeignKey) {
                $html .= $this->generateFormField($attr, $value, $required);
            }

            if ($attr['description']) {
                $html .= '<div class="form-text">' . htmlspecialchars($attr['description']) . '</div>';
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        $html .= '<div class="mt-4">';
        $html .= '<button type="submit" class="btn btn-primary">' . ($isEdit ? 'Update' : 'Create') . '</button> ';
        $html .= '<a href="/entities/' . strtolower($entityCode) . '/list" class="btn btn-secondary">Cancel</a>';
        $html .= '</div>';

        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Generate form field based on attribute type
     */
    private function generateFormField($attr, $value, $required)
    {
        $html = '';
        $code = $attr['code'];

        switch ($attr['data_type']) {
            case 'text':
                if ($attr['enum_values']) {
                    // Select dropdown
                    $options = json_decode($attr['enum_values'], true);
                    $html .= '<select name="' . $code . '" id="' . $code . '" class="form-select" ' . $required . '>';
                    $html .= '<option value="">-- Select --</option>';
                    foreach ($options as $option) {
                        $selected = $value == $option ? 'selected' : '';
                        $html .= '<option value="' . htmlspecialchars($option) . '" ' . $selected . '>' . htmlspecialchars($option) . '</option>';
                    }
                    $html .= '</select>';
                } else {
                    $html .= '<input type="text" name="' . $code . '" id="' . $code . '" class="form-control" value="' . htmlspecialchars($value) . '" ' . $required . '>';
                }
                break;

            case 'number':
            case 'integer':
                $html .= '<input type="number" name="' . $code . '" id="' . $code . '" class="form-control" value="' . htmlspecialchars($value) . '" ' . $required;
                if ($attr['min_value']) {
                    $html .= ' min="' . $attr['min_value'] . '"';
                }
                if ($attr['max_value']) {
                    $html .= ' max="' . $attr['max_value'] . '"';
                }
                $html .= '>';
                break;

            case 'boolean':
                $checked = $value ? 'checked' : '';
                $html .= '<div class="form-check">';
                $html .= '<input type="checkbox" name="' . $code . '" id="' . $code . '" class="form-check-input" value="1" ' . $checked . '>';
                $html .= '<label class="form-check-label" for="' . $code . '">Yes</label>';
                $html .= '</div>';
                break;

            case 'date':
                $html .= '<input type="date" name="' . $code . '" id="' . $code . '" class="form-control" value="' . htmlspecialchars($value) . '" ' . $required . '>';
                break;

            case 'datetime':
                $html .= '<input type="datetime-local" name="' . $code . '" id="' . $code . '" class="form-control" value="' . htmlspecialchars($value) . '" ' . $required . '>';
                break;

            default:
                $html .= '<textarea name="' . $code . '" id="' . $code . '" class="form-control" rows="3" ' . $required . '>' . htmlspecialchars($value) . '</textarea>';
        }

        return $html;
    }

    /**
     * Generate foreign key choice field (radio, select, or autocomplete based on count)
     */
    private function generateForeignKeySelect($relationship, $value, $required)
    {
        // Determine which entity the FK points to
        $targetEntityId = ($this->entity['id'] === $relationship['from_entity_id'])
            ? $relationship['to_entity_id']
            : $relationship['from_entity_id'];

        $targetEntity = EntityManager::getEntityById($targetEntityId);

        // Get total count of records
        $totalCount = EntityManager::count($targetEntity['code'], []);

        // Get label fields from target entity attributes
        $targetAttributes = EntityManager::getAttributes($targetEntityId);
        $labelFields = [];
        foreach ($targetAttributes as $attr) {
            if (isset($attr['is_label']) && $attr['is_label'] == 1) {
                $labelFields[] = $attr['code'];
            }
        }

        // If no label fields defined, fallback to name, code
        if (empty($labelFields)) {
            $labelFields = ['name', 'code'];
        }

        // Choice field logic based on count
        if ($totalCount < 8) {
            // Radio buttons for < 8 options
            return $this->generateRadioField($relationship, $targetEntity, $value, $required, $labelFields);
        } elseif ($totalCount < 50) {
            // HTML Select for 8-49 options
            return $this->generateSelectField($relationship, $targetEntity, $value, $required, $labelFields);
        } else {
            // Autocomplete for 50+ options
            return $this->generateAutocompleteField($relationship, $targetEntity, $value, $required, $labelFields);
        }
    }

    /**
     * Generate radio button field for FK (< 8 options)
     */
    private function generateRadioField($relationship, $targetEntity, $value, $required, $labelFields)
    {
        $records = EntityManager::search($targetEntity['code'], [], 100);
        $fieldName = $relationship['fk_field'];

        $html = '<div class="fk-radio-group">';

        foreach ($records as $record) {
            $checked = $value == $record['id'] ? 'checked' : '';
            $displayLabel = $this->buildDisplayLabel($record, $labelFields);

            $html .= '<div class="form-check">';
            $html .= '<input type="radio" class="form-check-input" name="' . $fieldName . '" ';
            $html .= 'id="' . $fieldName . '_' . htmlspecialchars($record['id']) . '" ';
            $html .= 'value="' . htmlspecialchars($record['id']) . '" ' . $checked . ' ' . $required . '>';
            $html .= '<label class="form-check-label" for="' . $fieldName . '_' . htmlspecialchars($record['id']) . '">';
            $html .= htmlspecialchars($displayLabel);
            $html .= '</label>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate select dropdown field for FK (8-49 options)
     */
    private function generateSelectField($relationship, $targetEntity, $value, $required, $labelFields)
    {
        $records = EntityManager::search($targetEntity['code'], [], 1000);
        $fieldName = $relationship['fk_field'];

        $html = '<select name="' . $fieldName . '" id="' . $fieldName . '" class="form-select" ' . $required . '>';
        $html .= '<option value="">-- Select ' . htmlspecialchars($targetEntity['name']) . ' --</option>';

        foreach ($records as $record) {
            $selected = $value == $record['id'] ? 'selected' : '';
            $displayLabel = $this->buildDisplayLabel($record, $labelFields);

            $html .= '<option value="' . htmlspecialchars($record['id']) . '" ' . $selected . '>';
            $html .= htmlspecialchars($displayLabel);
            $html .= '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Generate autocomplete field for FK (50+ options)
     */
    private function generateAutocompleteField($relationship, $targetEntity, $value, $required, $labelFields)
    {
        $fieldName = $relationship['fk_field'];
        $displayValue = '';

        // If there's a selected value, get its display label
        if (!empty($value)) {
            $record = EntityManager::read($targetEntity['code'], $value);
            if ($record) {
                $displayValue = $this->buildDisplayLabel($record, $labelFields);
            }
        }

        $html = '<div class="fk-autocomplete-wrapper">';
        $html .= '<input type="text" ';
        $html .= 'class="form-control fk-autocomplete" ';
        $html .= 'id="' . $fieldName . '_display" ';
        $html .= 'value="' . htmlspecialchars($displayValue) . '" ';
        $html .= 'placeholder="Type to search ' . htmlspecialchars($targetEntity['name']) . '..." ';
        $html .= 'data-fk-field="' . $fieldName . '" ';
        $html .= 'data-target-entity="' . htmlspecialchars($targetEntity['code']) . '" ';
        $html .= 'autocomplete="off" ';
        $html .= $required . '>';

        // Hidden field to store actual ID
        $html .= '<input type="hidden" ';
        $html .= 'name="' . $fieldName . '" ';
        $html .= 'id="' . $fieldName . '" ';
        $html .= 'value="' . htmlspecialchars($value) . '">';

        // Suggestions dropdown
        $html .= '<ul class="fk-autocomplete-suggestions list-group position-absolute w-100" ';
        $html .= 'id="' . $fieldName . '_suggestions" ';
        $html .= 'style="display: none; z-index: 1000;"></ul>';

        $html .= '</div>';

        return $html;
    }

    /**
     * Build display label from record and label fields
     */
    private function buildDisplayLabel($record, $labelFields)
    {
        $displayParts = [];
        foreach ($labelFields as $field) {
            if (isset($record[$field]) && !empty($record[$field])) {
                $displayParts[] = $record[$field];
            }
        }

        // Fallback if no label fields have values
        if (empty($displayParts)) {
            return $record['name'] ?? $record['code'] ?? substr($record['id'], 0, 8);
        }

        return implode(' - ', $displayParts);
    }

    /**
     * Resolve foreign key value to display label
     * Returns null if not a foreign key or value is empty
     */
    private function resolveForeignKeyLabel($fieldCode, $value)
    {
        // If value is empty, return null
        if (empty($value)) {
            return null;
        }

        // Check if this field is a foreign key
        $relationship = null;
        foreach ($this->relationships as $rel) {
            if ($rel['fk_field'] === $fieldCode) {
                $relationship = $rel;
                break;
            }
        }

        // Not a foreign key
        if (!$relationship) {
            return null;
        }

        // Determine target entity
        $targetEntityId = ($this->entity['id'] === $relationship['from_entity_id'])
            ? $relationship['to_entity_id']
            : $relationship['from_entity_id'];

        $targetEntity = EntityManager::getEntityById($targetEntityId);
        if (!$targetEntity) {
            return null;
        }

        // Fetch the related record
        $record = EntityManager::read($targetEntity['code'], $value);
        if (!$record) {
            return null;
        }

        // Get label fields from target entity
        $targetAttributes = EntityManager::getAttributes($targetEntityId);
        $labelFields = [];
        foreach ($targetAttributes as $attr) {
            if (isset($attr['is_label']) && $attr['is_label'] == 1) {
                $labelFields[] = $attr['code'];
            }
        }

        // If no label fields defined, fallback to name or code
        if (empty($labelFields)) {
            $labelFields = ['name', 'code'];
        }

        // Build display label from label fields
        $displayParts = [];
        foreach ($labelFields as $field) {
            if (isset($record[$field]) && !empty($record[$field])) {
                $displayParts[] = $record[$field];
            }
        }

        // Fallback if no label fields have values
        if (empty($displayParts)) {
            return $record['name'] ?? $record['code'] ?? substr($record['id'], 0, 8);
        }

        return implode(' - ', $displayParts);
    }
}
