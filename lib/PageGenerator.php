<?php

/**
 * PageGenerator class - generates CRUD pages for entities
 */
class PageGenerator {

    /**
     * Generate table HTML for list view
     */
    public static function generateTable($columns, $data, $entityName, $actions = ['view', 'edit', 'delete']) {
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-striped table-hover">';
        $html .= '<thead class="table-dark"><tr>';

        // Checkbox for bulk actions
        $html .= '<th><input type="checkbox" id="select-all"></th>';

        // Column headers
        foreach ($columns as $key => $label) {
            $html .= '<th>' . escape($label) . '</th>';
        }

        $html .= '<th>Actions</th>';
        $html .= '</tr></thead><tbody>';

        // Data rows
        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td><input type="checkbox" class="select-row" value="' . escape($row['id']) . '"></td>';

            foreach ($columns as $key => $label) {
                $value = $row[$key] ?? '';

                // Format different data types
                if (strpos($key, '_date') !== false || strpos($key, '_at') !== false) {
                    $value = $value ? date('Y-m-d H:i', strtotime($value)) : '';
                } elseif (is_bool($value)) {
                    $value = $value ? 'Yes' : 'No';
                } elseif (strlen($value) > 100) {
                    $value = substr($value, 0, 100) . '...';
                }

                $html .= '<td>' . escape($value) . '</td>';
            }

            // Action buttons
            $html .= '<td class="text-nowrap">';

            if (in_array('view', $actions)) {
                $html .= '<a href="detail.php?id=' . $row['id'] . '" class="btn btn-sm btn-info me-1" title="View"><i class="bi bi-eye"></i></a>';
            }

            if (in_array('edit', $actions)) {
                $html .= '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
            }

            if (in_array('delete', $actions)) {
                $html .= '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="bi bi-trash"></i></a>';
            }

            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Generate pagination HTML
     */
    public static function generatePagination($currentPage, $totalPages, $baseUrl) {
        if ($totalPages <= 1) {
            return '';
        }

        $html = '<nav><ul class="pagination justify-content-center">';

        // Previous button
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        // Page numbers
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);

        if ($start > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $totalPages . '">' . $totalPages . '</a></li>';
        }

        // Next button
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }

    /**
     * Generate form field HTML
     */
    public static function generateFormField($name, $label, $type = 'text', $value = '', $required = false, $options = []) {
        $html = '<div class="mb-3">';
        $html .= '<label for="' . $name . '" class="form-label">' . escape($label);
        if ($required) {
            $html .= ' <span class="text-danger">*</span>';
        }
        $html .= '</label>';

        $oldValue = old($name, $value);
        $error = error($name);
        $inputClass = 'form-control' . ($error ? ' is-invalid' : '');

        switch ($type) {
            case 'textarea':
                $html .= '<textarea name="' . $name . '" id="' . $name . '" class="' . $inputClass . '" rows="4"';
                if ($required) $html .= ' required';
                $html .= '>' . escape($oldValue) . '</textarea>';
                break;

            case 'select':
                $html .= '<select name="' . $name . '" id="' . $name . '" class="' . $inputClass . '"';
                if ($required) $html .= ' required';
                $html .= '>';
                $html .= '<option value="">-- Select --</option>';
                foreach ($options as $optValue => $optLabel) {
                    $selected = $oldValue == $optValue ? ' selected' : '';
                    $html .= '<option value="' . escape($optValue) . '"' . $selected . '>' . escape($optLabel) . '</option>';
                }
                $html .= '</select>';
                break;

            case 'checkbox':
                $checked = $oldValue ? ' checked' : '';
                $html .= '<div class="form-check">';
                $html .= '<input type="checkbox" name="' . $name . '" id="' . $name . '" class="form-check-input" value="1"' . $checked . '>';
                $html .= '<label class="form-check-label" for="' . $name . '">' . escape($label) . '</label>';
                $html .= '</div>';
                break;

            case 'date':
            case 'datetime-local':
            case 'email':
            case 'number':
            case 'url':
                $html .= '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" class="' . $inputClass . '" value="' . escape($oldValue) . '"';
                if ($required) $html .= ' required';
                $html .= '>';
                break;

            default:
                $html .= '<input type="text" name="' . $name . '" id="' . $name . '" class="' . $inputClass . '" value="' . escape($oldValue) . '"';
                if ($required) $html .= ' required';
                $html .= '>';
        }

        if ($error) {
            $html .= '<div class="invalid-feedback">' . escape($error) . '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate detail view HTML
     */
    public static function generateDetailView($fields, $data) {
        $html = '<div class="card"><div class="card-body">';

        foreach ($fields as $key => $label) {
            $value = $data[$key] ?? '';

            // Format different data types
            if (strpos($key, '_date') !== false || strpos($key, '_at') !== false) {
                $value = $value ? date('Y-m-d H:i:s', strtotime($value)) : 'N/A';
            } elseif (is_bool($value)) {
                $value = $value ? 'Yes' : 'No';
            } elseif (empty($value) && $value !== '0') {
                $value = 'N/A';
            }

            $html .= '<div class="row mb-2">';
            $html .= '<div class="col-md-4"><strong>' . escape($label) . ':</strong></div>';
            $html .= '<div class="col-md-8">' . escape($value) . '</div>';
            $html .= '</div>';
        }

        $html .= '</div></div>';

        return $html;
    }
}
