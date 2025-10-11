<?php
/**
 * Router - URL Routing and Request Handling
 */

class Router
{
    private $routes = [];
    private $currentRoute = null;

    /**
     * Add a GET route
     */
    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Add a POST route
     */
    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Add a route
     */
    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    /**
     * Dispatch the current request
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove query string
        $uri = strtok($uri, '?');

        // Remove base path if running in subdirectory
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $uri = substr($uri, strlen($scriptName));
        }

        // Ensure URI starts with /
        if (empty($uri) || $uri[0] !== '/') {
            $uri = '/' . $uri;
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $pattern = $this->convertToRegex($route['path']);
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remove full match
                    $this->currentRoute = $route;
                    return $this->callHandler($route['handler'], $matches);
                }
            }
        }

        // No route found - 404
        http_response_code(404);
        $this->renderError(404, 'Page not found');
    }

    /**
     * Convert route path to regex pattern
     */
    private function convertToRegex($path)
    {
        // Convert :param to named capture group
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Call the route handler
     */
    private function callHandler($handler, $params)
    {
        // Filter out named keys (keep only numeric indexes for positional args)
        $positionalParams = [];
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $positionalParams[] = $value;
            }
        }

        if (is_callable($handler)) {
            return call_user_func_array($handler, $positionalParams);
        } elseif (is_string($handler)) {
            // Handler is a file path
            $filePath = PUBLIC_PATH . '/' . $handler;
            if (file_exists($filePath)) {
                // Extract params to variables
                extract($params);
                require $filePath;
            } else {
                http_response_code(500);
                $this->renderError(500, 'Handler file not found: ' . $handler);
            }
        }
    }

    /**
     * Redirect to a URL
     */
    public static function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    /**
     * Get current URL
     */
    public static function currentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Render error page
     */
    private function renderError($code, $message)
    {
        $title = $code . ' Error';
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$title}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: #dc3545; }
    </style>
</head>
<body>
    <h1>{$title}</h1>
    <p>{$message}</p>
    <a href='/'>Go to Homepage</a>
</body>
</html>";
        exit;
    }
}
