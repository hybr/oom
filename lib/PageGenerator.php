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

                // Check if this is an enum_objects field and resolve to label
                if ($attr['data_type'] === 'enum_objects') {
                    $displayValue = $this->resolveEnumObjectLabel($attr, $value);
                    $value = $displayValue !== null ? $displayValue : $value;
                }
                // Check if this is an enum_strings field with multiple values
                elseif ($attr['data_type'] === 'enum_strings') {
                    $value = $this->resolveEnumStringsDisplay($value);
                }
                // Check if this attribute is a foreign key
                else {
                    $fkLabel = $this->resolveForeignKeyLabel($attr['code'], $value);
                    if ($fkLabel !== null) {
                        $value = $fkLabel;
                    }
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

            // Check if this is an enum_objects field and resolve to label
            if ($attr['data_type'] === 'enum_objects') {
                $displayValue = $this->resolveEnumObjectLabel($attr, $value);
                $value = $displayValue !== null ? $displayValue : $value;
            }
            // Check if this is an enum_strings field with multiple values
            elseif ($attr['data_type'] === 'enum_strings') {
                $value = $this->resolveEnumStringsDisplay($value);
            }
            // Check if this attribute is a foreign key
            else {
                $fkLabel = $this->resolveForeignKeyLabel($attr['code'], $value);
                if ($fkLabel !== null) {
                    $value = $fkLabel;
                }
            }

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
                // For ManyToOne relationships, the FK is on the "from" entity
                // When viewing the "to" entity (target), we show "from" entities filtered by FK
                if ($this->entity['id'] === $rel['to_entity_id']) {
                    // This entity is the target - show the source entities that reference it
                    $relatedEntity = EntityManager::getEntityById($rel['from_entity_id']);
                    $filterField = $rel['fk_field'];

                    if (!$relatedEntity) {
                        continue;
                    }

                    $html .= '<div class="card mb-2">';
                    $html .= '<div class="card-header">' . htmlspecialchars($rel['relation_name']) . '</div>';
                    $html .= '<div class="card-body">';
                    $html .= '<a href="/entities/' . strtolower($relatedEntity['code']) . '/list?filter=' . $filterField . '=' . $record['id'] . '" class="btn btn-sm btn-primary">View ' . htmlspecialchars($relatedEntity['name']) . ' Records</a>';
                    $html .= '</div></div>';
                }
                // If this entity is the source (from_entity), the FK is on this entity
                // We could show the parent/target record, but it's just one record (ManyToOne)
                // So we skip showing it in the related records section
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
        $html .= '<form method="POST" action="' . $formAction . '" class="needs-validation" enctype="multipart/form-data" novalidate>';

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

            // Get value: prioritize record data, then pre-populated values, then default
            if ($isEdit && $record) {
                $value = $record[$attr['code']] ?? '';
            } elseif ($record && isset($record[$attr['code']])) {
                // Create mode with pre-populated values
                $value = $record[$attr['code']];
            } else {
                $value = $attr['default_value'] ?? '';
            }
            $required = $attr['is_required'] == 1 ? 'required' : '';

            $html .= '<div class="col-md-6 mb-3">';

            // Check if this is a foreign key to add link to label
            $isForeignKey = false;
            $fkRelationship = null;
            $isEntityIdField = ($attr['code'] === 'entity_id');
            $isPermissionTypeField = ($attr['code'] === 'permission_type_id');
            $isPositionField = ($attr['code'] === 'position_id');

            if ($isEntityIdField || $isPermissionTypeField || $isPositionField) {
                $isForeignKey = true;
                // Special handling for specific foreign key fields - no relationship needed
            } else {
                foreach ($this->relationships as $rel) {
                    if ($rel['fk_field'] === $attr['code']) {
                        $isForeignKey = true;
                        $fkRelationship = $rel;
                        break;
                    }
                }
            }

            // Generate label with link for FK fields
            if ($isEntityIdField || $isPermissionTypeField || $isPositionField) {
                // Special label for specific foreign key fields
                $html .= '<label for="' . $attr['code'] . '" class="form-label">' . htmlspecialchars($attr['name']);
                if ($required) {
                    $html .= ' <span class="text-danger">*</span>';
                }
                $html .= '</label>';
            } elseif ($isForeignKey && $fkRelationship) {
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
            if ($isEntityIdField) {
                $html .= $this->generateEntityIdSelect($value, $required);
            } elseif ($isPermissionTypeField) {
                $html .= $this->generatePermissionTypeSelect($value, $required);
            } elseif ($isPositionField) {
                $html .= $this->generatePositionSelect($value, $required);
            } elseif ($isForeignKey && $fkRelationship) {
                $html .= $this->generateForeignKeySelect($fkRelationship, $value, $required);
            } else {
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
     * Generate only form fields without form wrapper
     * Useful for AJAX form loading or embedding forms in modals
     *
     * @param array|null $record Record data for pre-population
     * @return string HTML for form fields only
     */
    public function generateFormFields($record = null)
    {
        $html = '<div class="row">';

        // Generate form fields
        foreach ($this->attributes as $attr) {
            if ($attr['is_system'] == 1) {
                continue;
            }

            // Get value from record or default
            if ($record && isset($record[$attr['code']])) {
                $value = $record[$attr['code']];
            } else {
                $value = $attr['default_value'] ?? '';
            }
            $required = $attr['is_required'] == 1 ? 'required' : '';

            $html .= '<div class="col-md-6 mb-3">';

            // Check if this is a foreign key
            $isForeignKey = false;
            $fkRelationship = null;
            $isEntityIdField = ($attr['code'] === 'entity_id');
            $isPermissionTypeField = ($attr['code'] === 'permission_type_id');
            $isPositionField = ($attr['code'] === 'position_id');

            if ($isEntityIdField || $isPermissionTypeField || $isPositionField) {
                $isForeignKey = true;
            } else {
                foreach ($this->relationships as $rel) {
                    if ($rel['fk_field'] === $attr['code']) {
                        $isForeignKey = true;
                        $fkRelationship = $rel;
                        break;
                    }
                }
            }

            // Generate label
            if ($isEntityIdField || $isPermissionTypeField || $isPositionField) {
                $html .= '<label for="' . $attr['code'] . '" class="form-label">' . htmlspecialchars($attr['name']);
                if ($required) {
                    $html .= ' <span class="text-danger">*</span>';
                }
                $html .= '</label>';
            } elseif ($isForeignKey && $fkRelationship) {
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
                    $html .= '<label for="' . $attr['code'] . '" class="form-label">' . htmlspecialchars($attr['name']);
                    if ($required) {
                        $html .= ' <span class="text-danger">*</span>';
                    }
                    $html .= '</label>';
                }
            } else {
                $html .= '<label for="' . $attr['code'] . '" class="form-label">' . htmlspecialchars($attr['name']);
                if ($required) {
                    $html .= ' <span class="text-danger">*</span>';
                }
                $html .= '</label>';
            }

            // Generate field
            if ($isEntityIdField) {
                $html .= $this->generateEntityIdSelect($value, $required);
            } elseif ($isPermissionTypeField) {
                $html .= $this->generatePermissionTypeSelect($value, $required);
            } elseif ($isPositionField) {
                $html .= $this->generatePositionSelect($value, $required);
            } elseif ($isForeignKey && $fkRelationship) {
                $html .= $this->generateForeignKeySelect($fkRelationship, $value, $required);
            } else {
                $html .= $this->generateFormField($attr, $value, $required);
            }

            if ($attr['description']) {
                $html .= '<div class="form-text">' . htmlspecialchars($attr['description']) . '</div>';
            }

            $html .= '</div>';
        }

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
            case 'enum_strings':
                // Radio buttons or checkboxes for list of strings
                $html .= $this->generateEnumStringsField($attr, $value, $required);
                break;

            case 'enum_objects':
                // Select dropdown or radio buttons for list of {value, label} objects
                $html .= $this->generateEnumObjectsField($attr, $value, $required);
                break;

            case 'text':
                if ($attr['enum_values']) {
                    // Select dropdown (legacy support)
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

            case 'file':
                $html .= '<input type="file" name="' . $code . '" id="' . $code . '" class="form-control" ' . $required . '>';
                if (!empty($value)) {
                    $html .= '<div class="mt-2"><small class="text-muted">Current file: ' . htmlspecialchars($value) . '</small></div>';
                }
                break;

            default:
                $html .= '<textarea name="' . $code . '" id="' . $code . '" class="form-control" rows="3" ' . $required . '>' . htmlspecialchars($value) . '</textarea>';
        }

        return $html;
    }

    /**
     * Generate enum_strings field (radio for single, checkbox for multiple)
     * enum_values format: ["Option1", "Option2", "Option3"] or {"allow_multiple": true, "options": ["A", "B", "C"]}
     */
    private function generateEnumStringsField($attr, $value, $required)
    {
        $html = '';
        $code = $attr['code'];

        if (!$attr['enum_values']) {
            return '<input type="text" name="' . $code . '" id="' . $code . '" class="form-control" value="' . htmlspecialchars($value) . '" ' . $required . '>';
        }

        $enumData = json_decode($attr['enum_values'], true);

        // Determine if it's multi-select and get options
        $allowMultiple = false;
        $options = [];

        if (is_array($enumData) && isset($enumData['allow_multiple'])) {
            $allowMultiple = $enumData['allow_multiple'];
            $options = $enumData['options'] ?? [];
        } else {
            $options = $enumData;
        }

        // For multiple selection, value is stored as JSON array
        $selectedValues = [];
        if ($allowMultiple && !empty($value)) {
            $selectedValues = json_decode($value, true) ?? [];
            if (!is_array($selectedValues)) {
                $selectedValues = [$value];
            }
        }

        $inputType = $allowMultiple ? 'checkbox' : 'radio';
        $fieldName = $allowMultiple ? $code . '[]' : $code;

        $html .= '<div class="enum-strings-group">';

        foreach ($options as $option) {
            $optionValue = htmlspecialchars($option);
            $fieldId = $code . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $option);

            if ($allowMultiple) {
                $checked = in_array($option, $selectedValues) ? 'checked' : '';
            } else {
                $checked = $value == $option ? 'checked' : '';
            }

            $html .= '<div class="form-check">';
            $html .= '<input type="' . $inputType . '" class="form-check-input" name="' . $fieldName . '" ';
            $html .= 'id="' . $fieldId . '" value="' . $optionValue . '" ' . $checked;

            if (!$allowMultiple && $required) {
                $html .= ' ' . $required;
            }

            $html .= '>';
            $html .= '<label class="form-check-label" for="' . $fieldId . '">';
            $html .= $optionValue;
            $html .= '</label>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate enum_objects field (select dropdown or radio buttons)
     * enum_values format: [{"value": "1", "label": "One"}, {"value": "2", "label": "Two"}]
     * or {"allow_multiple": true, "options": [{...}]}
     */
    private function generateEnumObjectsField($attr, $value, $required)
    {
        $html = '';
        $code = $attr['code'];

        if (!$attr['enum_values']) {
            return '<input type="text" name="' . $code . '" id="' . $code . '" class="form-control" value="' . htmlspecialchars($value) . '" ' . $required . '>';
        }

        $enumData = json_decode($attr['enum_values'], true);

        // Determine if it's multi-select, rendering type, and get options
        $allowMultiple = false;
        $renderAs = 'select'; // 'select' or 'radio'
        $options = [];

        if (is_array($enumData) && isset($enumData['options'])) {
            $allowMultiple = $enumData['allow_multiple'] ?? false;
            $renderAs = $enumData['render_as'] ?? 'select';
            $options = $enumData['options'];
        } else {
            $options = $enumData;
        }

        // For multiple selection, value is stored as JSON array
        $selectedValues = [];
        if ($allowMultiple && !empty($value)) {
            $selectedValues = json_decode($value, true) ?? [];
            if (!is_array($selectedValues)) {
                $selectedValues = [$value];
            }
        }

        // Render as radio buttons or checkboxes
        if ($renderAs === 'radio' || ($renderAs === 'auto' && count($options) < 8)) {
            $inputType = $allowMultiple ? 'checkbox' : 'radio';
            $fieldName = $allowMultiple ? $code . '[]' : $code;

            $html .= '<div class="enum-objects-group">';

            foreach ($options as $option) {
                $optionValue = htmlspecialchars($option['value']);
                $optionLabel = htmlspecialchars($option['label']);
                $fieldId = $code . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $option['value']);

                if ($allowMultiple) {
                    $checked = in_array($option['value'], $selectedValues) ? 'checked' : '';
                } else {
                    $checked = $value == $option['value'] ? 'checked' : '';
                }

                $html .= '<div class="form-check">';
                $html .= '<input type="' . $inputType . '" class="form-check-input" name="' . $fieldName . '" ';
                $html .= 'id="' . $fieldId . '" value="' . $optionValue . '" ' . $checked;

                if (!$allowMultiple && $required) {
                    $html .= ' ' . $required;
                }

                $html .= '>';
                $html .= '<label class="form-check-label" for="' . $fieldId . '">';
                $html .= $optionLabel;
                $html .= '</label>';
                $html .= '</div>';
            }

            $html .= '</div>';
        } else {
            // Render as select dropdown
            if ($allowMultiple) {
                $html .= '<select name="' . $code . '[]" id="' . $code . '" class="form-select" multiple ' . $required . '>';
            } else {
                $html .= '<select name="' . $code . '" id="' . $code . '" class="form-select" ' . $required . '>';
                $html .= '<option value="">-- Select --</option>';
            }

            foreach ($options as $option) {
                $optionValue = htmlspecialchars($option['value']);
                $optionLabel = htmlspecialchars($option['label']);

                if ($allowMultiple) {
                    $selected = in_array($option['value'], $selectedValues) ? 'selected' : '';
                } else {
                    $selected = $value == $option['value'] ? 'selected' : '';
                }

                $html .= '<option value="' . $optionValue . '" ' . $selected . '>';
                $html .= $optionLabel;
                $html .= '</option>';
            }

            $html .= '</select>';
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

            // Special formatting for specific entity types
            if ($targetEntity['code'] === 'POSTAL_ADDRESS') {
                $displayLabel = $this->formatPostalAddressLabel($record);
            } elseif ($targetEntity['code'] === 'VACANCY_APPLICATION') {
                $displayLabel = $this->formatVacancyApplicationLabel($record);
            } else {
                $displayLabel = $this->buildDisplayLabel($record, $labelFields);
            }

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

            // Special formatting for specific entity types
            if ($targetEntity['code'] === 'POSTAL_ADDRESS') {
                $displayLabel = $this->formatPostalAddressLabel($record);
            } elseif ($targetEntity['code'] === 'VACANCY_APPLICATION') {
                $displayLabel = $this->formatVacancyApplicationLabel($record);
            } else {
                $displayLabel = $this->buildDisplayLabel($record, $labelFields);
            }

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
                // Special formatting for specific entity types
                if ($targetEntity['code'] === 'POSTAL_ADDRESS') {
                    $displayValue = $this->formatPostalAddressLabel($record);
                } elseif ($targetEntity['code'] === 'VACANCY_APPLICATION') {
                    $displayValue = $this->formatVacancyApplicationLabel($record);
                } else {
                    $displayValue = $this->buildDisplayLabel($record, $labelFields);
                }
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
     * Resolve enum_objects value to display label
     * Returns null if not found or invalid
     */
    private function resolveEnumObjectLabel($attr, $value)
    {
        if (empty($value) || !$attr['enum_values']) {
            return null;
        }

        $enumData = json_decode($attr['enum_values'], true);

        // Handle wrapped format
        $options = [];
        if (is_array($enumData) && isset($enumData['options'])) {
            $options = $enumData['options'];
            $allowMultiple = $enumData['allow_multiple'] ?? false;
        } else {
            $options = $enumData;
            $allowMultiple = false;
        }

        // Handle multiple values
        if ($allowMultiple) {
            $values = json_decode($value, true);
            if (!is_array($values)) {
                $values = [$value];
            }

            $labels = [];
            foreach ($values as $val) {
                foreach ($options as $option) {
                    if ($option['value'] == $val) {
                        $labels[] = $option['label'];
                        break;
                    }
                }
            }

            return implode(', ', $labels);
        }

        // Single value
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }

        return null;
    }

    /**
     * Resolve enum_strings value to display
     * Handles both single and multiple values
     */
    private function resolveEnumStringsDisplay($value)
    {
        if (empty($value)) {
            return '';
        }

        // Try to decode as JSON array (for multiple values)
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return implode(', ', $decoded);
        }

        // Single value
        return $value;
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

        // Special handling for specific foreign key fields
        if ($fieldCode === 'entity_id') {
            $entity = EntityManager::getEntityById($value);
            if ($entity) {
                return $entity['name'];
            }
            return null;
        }

        if ($fieldCode === 'permission_type_id') {
            $record = EntityManager::read('ENUM_ENTITY_PERMISSION_TYPE', $value);
            if ($record) {
                return $record['name'];
            }
            return null;
        }

        if ($fieldCode === 'position_id') {
            $record = EntityManager::read('POPULAR_ORGANIZATION_POSITION', $value);
            if ($record) {
                return $record['position_name'];
            }
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

        // Special formatting for postal addresses
        if ($targetEntity['code'] === 'POSTAL_ADDRESS') {
            return $this->formatPostalAddressLabel($record);
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

    /**
     * Format postal address as a readable single line with full location hierarchy
     */
    private function formatPostalAddressLabel($record)
    {
        $parts = [];

        // Street address
        if (!empty($record['first_street'])) {
            $parts[] = $record['first_street'];
        }
        if (!empty($record['second_street'])) {
            $parts[] = $record['second_street'];
        }

        // Area/Locality
        if (!empty($record['area'])) {
            $parts[] = $record['area'];
        }

        // Landmark
        if (!empty($record['landmark'])) {
            $parts[] = 'Near ' . $record['landmark'];
        }

        // City, District, State, Country (resolve from FK chain)
        if (!empty($record['city_id'])) {
            $city = EntityManager::read('CITY', $record['city_id']);
            if ($city && !empty($city['name'])) {
                $parts[] = $city['name'];

                // District
                if (!empty($city['district_id'])) {
                    $district = EntityManager::read('DISTRICT', $city['district_id']);
                    if ($district && !empty($district['name'])) {
                        $parts[] = $district['name'];
                    }
                }

                // State
                if (!empty($city['state_id'])) {
                    $state = EntityManager::read('STATE', $city['state_id']);
                    if ($state && !empty($state['name'])) {
                        $parts[] = $state['name'];
                    }
                }

                // Country
                if (!empty($city['country_id'])) {
                    $country = EntityManager::read('COUNTRY', $city['country_id']);
                    if ($country && !empty($country['name'])) {
                        $parts[] = $country['name'];
                    }
                }
            }
        }

        // Postal code
        if (!empty($record['postal_code'])) {
            $parts[] = $record['postal_code'];
        }

        return !empty($parts) ? implode(', ', $parts) : 'Address #' . substr($record['id'], 0, 8);
    }

    /**
     * Format vacancy application label with meaningful information
     */
    private function formatVacancyApplicationLabel($record)
    {
        $parts = [];

        // Get applicant name
        if (!empty($record['applicant_id'])) {
            $applicant = EntityManager::read('PERSON', $record['applicant_id']);
            if ($applicant) {
                $applicantName = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
                if (!empty($applicantName)) {
                    $parts[] = $applicantName;
                }
            }
        }

        // Get vacancy/position information
        if (!empty($record['vacancy_id'])) {
            $vacancy = EntityManager::read('ORGANIZATION_VACANCY', $record['vacancy_id']);
            if ($vacancy) {
                // Get position name (note: column is popular_position_id, not position_id)
                if (!empty($vacancy['popular_position_id'])) {
                    $position = EntityManager::read('POPULAR_ORGANIZATION_POSITION', $vacancy['popular_position_id']);
                    if ($position && !empty($position['position_name'])) {
                        $parts[] = 'for ' . $position['position_name'];
                    }
                }

                // Get organization name
                if (!empty($vacancy['organization_id'])) {
                    $organization = EntityManager::read('ORGANIZATION', $vacancy['organization_id']);
                    if ($organization && !empty($organization['short_name'])) {
                        $parts[] = 'at ' . $organization['short_name'];
                    }
                }
            }
        }

        // Add application date if available
        if (!empty($record['application_date'])) {
            $parts[] = '(' . date('M d, Y', strtotime($record['application_date'])) . ')';
        }

        return !empty($parts) ? implode(' ', $parts) : 'Application #' . substr($record['id'], 0, 8);
    }

    /**
     * Generate entity selection dropdown for entity_id field
     */
    private function generateEntityIdSelect($value, $required)
    {
        $entities = EntityManager::loadEntities();

        $html = '<select name="entity_id" id="entity_id" class="form-select" ' . $required . '>';
        $html .= '<option value="">-- Select Entity --</option>';

        foreach ($entities as $entity) {
            $selected = $value == $entity['id'] ? 'selected' : '';
            $html .= '<option value="' . htmlspecialchars($entity['id']) . '" ' . $selected . '>';
            $html .= htmlspecialchars($entity['name']);
            $html .= '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Generate permission type selection dropdown for permission_type_id field
     */
    private function generatePermissionTypeSelect($value, $required)
    {
        $permissionTypes = EntityManager::search('ENUM_ENTITY_PERMISSION_TYPE', [], 100);

        $html = '<select name="permission_type_id" id="permission_type_id" class="form-select" ' . $required . '>';
        $html .= '<option value="">-- Select Permission Type --</option>';

        foreach ($permissionTypes as $permType) {
            $selected = $value == $permType['id'] ? 'selected' : '';
            $html .= '<option value="' . htmlspecialchars($permType['id']) . '" ' . $selected . '>';
            $html .= htmlspecialchars($permType['name']);
            $html .= '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Generate position selection dropdown for position_id field
     */
    private function generatePositionSelect($value, $required)
    {
        $positions = EntityManager::search('POPULAR_ORGANIZATION_POSITION', [], 100);

        $html = '<select name="position_id" id="position_id" class="form-select" ' . $required . '>';
        $html .= '<option value="">-- Select Position --</option>';

        foreach ($positions as $position) {
            $selected = $value == $position['id'] ? 'selected' : '';
            $html .= '<option value="' . htmlspecialchars($position['id']) . '" ' . $selected . '>';
            $html .= htmlspecialchars($position['position_name']);
            $html .= '</option>';
        }

        $html .= '</select>';

        return $html;
    }
}
