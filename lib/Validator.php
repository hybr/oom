<?php

/**
 * Validator class - handles input validation
 */
class Validator {
    private $data = [];
    private $rules = [];
    private $errors = [];
    private $messages = [];

    public function __construct($data = []) {
        $this->data = $data;
    }

    public function validate($rules, $messages = []) {
        $this->rules = $rules;
        $this->messages = $messages;
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $this->validateField($field, $ruleString);
        }

        return empty($this->errors);
    }

    private function validateField($field, $ruleString) {
        $rules = explode('|', $ruleString);
        $value = $this->data[$field] ?? null;

        foreach ($rules as $rule) {
            $this->applyRule($field, $value, $rule);
        }
    }

    private function applyRule($field, $value, $rule) {
        // Parse rule with parameters
        $params = [];
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule, 2);
            $params = explode(',', $paramString);
        }

        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, 'required');
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'email');
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < $params[0]) {
                    $this->addError($field, 'min', ['min' => $params[0]]);
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > $params[0]) {
                    $this->addError($field, 'max', ['max' => $params[0]]);
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'numeric');
                }
                break;

            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, 'integer');
                }
                break;

            case 'alpha':
                if (!empty($value) && !ctype_alpha($value)) {
                    $this->addError($field, 'alpha');
                }
                break;

            case 'alphanumeric':
                if (!empty($value) && !ctype_alnum($value)) {
                    $this->addError($field, 'alphanumeric');
                }
                break;

            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, 'date');
                }
                break;

            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, 'url');
                }
                break;

            case 'unique':
                // Format: unique:table,column,except_id
                if (!empty($value)) {
                    $table = $params[0];
                    $column = $params[1] ?? $field;
                    $exceptId = $params[2] ?? null;

                    $sql = "SELECT id FROM $table WHERE $column = ?";
                    $sqlParams = [$value];

                    if ($exceptId) {
                        $sql .= " AND id != ?";
                        $sqlParams[] = $exceptId;
                    }

                    $result = db()->selectOne($sql, $sqlParams);
                    if ($result) {
                        $this->addError($field, 'unique');
                    }
                }
                break;

            case 'confirmed':
                // Check if {field}_confirmation matches
                $confirmField = $field . '_confirmation';
                if (!isset($this->data[$confirmField]) || $value !== $this->data[$confirmField]) {
                    $this->addError($field, 'confirmed');
                }
                break;

            case 'in':
                // Check if value is in allowed list
                if (!empty($value) && !in_array($value, $params)) {
                    $this->addError($field, 'in');
                }
                break;
        }
    }

    private function addError($field, $rule, $params = []) {
        $message = $this->messages[$field . '.' . $rule] ?? $this->getDefaultMessage($field, $rule, $params);
        $this->errors[$field] = $message;
    }

    private function getDefaultMessage($field, $rule, $params = []) {
        $fieldName = ucfirst(str_replace('_', ' ', $field));

        $messages = [
            'required' => "$fieldName is required.",
            'email' => "$fieldName must be a valid email address.",
            'min' => "$fieldName must be at least {$params['min']} characters.",
            'max' => "$fieldName must not exceed {$params['max']} characters.",
            'numeric' => "$fieldName must be a number.",
            'integer' => "$fieldName must be an integer.",
            'alpha' => "$fieldName must contain only letters.",
            'alphanumeric' => "$fieldName must contain only letters and numbers.",
            'date' => "$fieldName must be a valid date.",
            'url' => "$fieldName must be a valid URL.",
            'unique' => "$fieldName already exists.",
            'confirmed' => "$fieldName confirmation does not match.",
            'in' => "$fieldName is not valid.",
        ];

        return $messages[$rule] ?? "$fieldName is invalid.";
    }

    public function errors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    public static function make($data) {
        return new self($data);
    }
}
