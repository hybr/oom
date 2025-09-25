<?php

class TestRunner {
    private $tests = [];
    private $passed = 0;
    private $failed = 0;

    public function addTest($name, $callback) {
        $this->tests[] = ['name' => $name, 'callback' => $callback];
    }

    public function run() {
        echo "Running tests...\n";
        echo str_repeat('=', 50) . "\n";

        foreach ($this->tests as $test) {
            try {
                $result = call_user_func($test['callback']);
                if ($result === true) {
                    echo "✓ {$test['name']}\n";
                    $this->passed++;
                } else {
                    echo "✗ {$test['name']} - Failed\n";
                    $this->failed++;
                }
            } catch (Exception $e) {
                echo "✗ {$test['name']} - Error: {$e->getMessage()}\n";
                $this->failed++;
            }
        }

        echo str_repeat('=', 50) . "\n";
        echo "Results: {$this->passed} passed, {$this->failed} failed\n";
        return $this->failed === 0;
    }
}

class Assert {
    public static function equals($expected, $actual, $message = '') {
        if ($expected !== $actual) {
            throw new Exception($message ?: "Expected '$expected', got '$actual'");
        }
        return true;
    }

    public static function true($value, $message = '') {
        if ($value !== true) {
            throw new Exception($message ?: "Expected true, got " . var_export($value, true));
        }
        return true;
    }

    public static function false($value, $message = '') {
        if ($value !== false) {
            throw new Exception($message ?: "Expected false, got " . var_export($value, true));
        }
        return true;
    }

    public static function notNull($value, $message = '') {
        if ($value === null) {
            throw new Exception($message ?: "Expected non-null value");
        }
        return true;
    }

    public static function throws($callback, $expectedExceptionClass = 'Exception', $message = '') {
        try {
            call_user_func($callback);
            throw new Exception($message ?: "Expected exception to be thrown");
        } catch (Exception $e) {
            if ($e instanceof $expectedExceptionClass || get_class($e) === $expectedExceptionClass) {
                return true;
            }
            throw new Exception($message ?: "Expected {$expectedExceptionClass}, got " . get_class($e));
        }
    }
}