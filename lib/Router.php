<?php

/**
 * Router class - handles URL routing
 */
class Router {
    private $routes = [];
    private $baseUrl;

    public function __construct() {
        $this->baseUrl = config('app.url');
    }

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler) {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler) {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path if present
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $requestUri = substr($requestUri, strlen($scriptName));
        }
        $requestUri = '/' . trim($requestUri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertPathToRegex($route['path']);
            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                return $this->callHandler($route['handler'], $matches);
            }
        }

        // No route matched
        http_response_code(404);
        require __DIR__ . '/../public/pages/errors/404.php';
        exit;
    }

    private function convertPathToRegex($path) {
        // Convert {id} to named capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function callHandler($handler, $params) {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_string($handler)) {
            require $handler;
            return;
        }
    }

    public function url($path) {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }
}
