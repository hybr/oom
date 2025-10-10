<?php
/**
 * Input Validation Class
 */

class Validator
{
    private $data = [];
    private $rules = [];
    private $errors = [];

    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Validate the data
     */
    public function validate()
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Apply a validation rule
     */
    private function applyRule($field, $value, $rule)
    {
        // Parse rule with parameters (e.g., max:255)
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $params = isset($parts[1]) ? explode(',', $parts[1]) : [];

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, 'The ' . $field . ' field is required.');
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'The ' . $field . ' must be a valid email address.');
                }
                break;

            case 'min':
                $min = (int) $params[0];
                if (!empty($value) && strlen($value) < $min) {
                    $this->addError($field, "The {$field} must be at least {$min} characters.");
                }
                break;

            case 'max':
                $max = (int) $params[0];
                if (!empty($value) && strlen($value) > $max) {
                    $this->addError($field, "The {$field} must not exceed {$max} characters.");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'The ' . $field . ' must be a number.');
                }
                break;

            case 'alpha':
                if (!empty($value) && !ctype_alpha($value)) {
                    $this->addError($field, 'The ' . $field . ' must contain only letters.');
                }
                break;

            case 'alphanumeric':
                if (!empty($value) && !ctype_alnum($value)) {
                    $this->addError($field, 'The ' . $field . ' must contain only letters and numbers.');
                }
                break;

            case 'regex':
                $pattern = $params[0];
                if (!empty($value) && !preg_match($pattern, $value)) {
                    $this->addError($field, 'The ' . $field . ' format is invalid.');
                }
                break;

            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, 'The ' . $field . ' must be a valid URL.');
                }
                break;

            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, 'The ' . $field . ' must be a valid date.');
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if (isset($this->data[$confirmField]) && $value !== $this->data[$confirmField]) {
                    $this->addError($field, 'The ' . $field . ' confirmation does not match.');
                }
                break;

            case 'unique':
                // Format: unique:table,column
                $table = $params[0];
                $column = $params[1] ?? $field;
                if (!empty($value)) {
                    $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$column} = ?";
                    $result = Database::fetchOne($sql, [$value]);
                    if ($result['cnt'] > 0) {
                        $this->addError($field, "The {$field} has already been taken.");
                    }
                }
                break;

            case 'in':
                // Format: in:value1,value2,value3
                if (!empty($value) && !in_array($value, $params)) {
                    $this->addError($field, "The {$field} must be one of: " . implode(', ', $params));
                }
                break;
        }
    }

    /**
     * Add an error message
     */
    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Get all errors
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get errors for a specific field
     */
    public function error($field)
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Check if validation failed
     */
    public function fails()
    {
        return !empty($this->errors);
    }

    /**
     * Static validation helper
     */
    public static function make($data, $rules)
    {
        return new self($data, $rules);
    }

    /**
     * Sanitize string input
     */
    public static function sanitize($input)
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize array of inputs
     */
    public static function sanitizeArray($inputs)
    {
        $sanitized = [];
        foreach ($inputs as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitize($value);
            }
        }
        return $sanitized;
    }
}
