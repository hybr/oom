<?php
/**
 * Request Helper
 *
 * Handles incoming HTTP requests
 */

namespace V4L\Core;

class Request
{
    private array $data;
    private string $method;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->data = $this->parseInput();
    }

    /**
     * Parse input data from request
     */
    private function parseInput(): array
    {
        $data = [];

        // Parse GET parameters
        if ($this->method === 'GET') {
            $data = $_GET;
        }

        // Parse POST parameters
        if ($this->method === 'POST') {
            $data = $_POST;

            // If content type is JSON, parse JSON body
            if ($this->isJson()) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true) ?? [];
            }
        }

        // Parse PUT/PATCH/DELETE parameters
        if (in_array($this->method, ['PUT', 'PATCH', 'DELETE'])) {
            if ($this->isJson()) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true) ?? [];
            } else {
                parse_str(file_get_contents('php://input'), $data);
            }
        }

        return $data;
    }

    /**
     * Check if request is JSON
     */
    private function isJson(): bool
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        return strpos($contentType, 'application/json') !== false;
    }

    /**
     * Get request method
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get all input data
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get specific input value
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if input has key
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Get only specific keys from input
     */
    public function only(array $keys): array
    {
        return array_intersect_key($this->data, array_flip($keys));
    }

    /**
     * Get all except specific keys
     */
    public function except(array $keys): array
    {
        return array_diff_key($this->data, array_flip($keys));
    }

    /**
     * Get query string parameter
     */
    public function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get uploaded file
     */
    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Get all uploaded files
     */
    public function files(): array
    {
        return $_FILES;
    }

    /**
     * Sanitize input
     */
    public function sanitize(string $key, string $filter = FILTER_SANITIZE_STRING)
    {
        $value = $this->get($key);
        return $value ? filter_var($value, $filter) : null;
    }

    /**
     * Validate input
     */
    public function validate(array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $this->get($field);
            $ruleArray = explode('|', $ruleSet);

            foreach ($ruleArray as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleParam = $ruleParts[1] ?? null;

                $error = $this->applyValidationRule($field, $value, $ruleName, $ruleParam);
                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }

        return $errors;
    }

    /**
     * Apply validation rule
     */
    private function applyValidationRule(string $field, $value, string $rule, $param): ?string
    {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    return ucfirst($field) . ' is required';
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return ucfirst($field) . ' must be a valid email';
                }
                break;
            case 'min':
                if (strlen($value) < $param) {
                    return ucfirst($field) . " must be at least $param characters";
                }
                break;
            case 'max':
                if (strlen($value) > $param) {
                    return ucfirst($field) . " must not exceed $param characters";
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    return ucfirst($field) . ' must be a number';
                }
                break;
        }

        return null;
    }

    /**
     * Get request header
     */
    public function header(string $key): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$key] ?? null;
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Get client IP address
     */
    public function ip(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }
}
