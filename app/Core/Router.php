<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Router class - handles URL routing and dispatch
 */
class Router
{
    private array $routes = [];
    private string $currentMethod = '';
    private string $currentPath = '';

    public function __construct()
    {
        $this->currentMethod = $_SERVER['REQUEST_METHOD'];
        $this->currentPath = $this->parseUri();
    }

    /**
     * Parse the request URI
     */
    private function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $basePath = dirname($_SERVER['SCRIPT_NAME']);

        if ($basePath !== '/') {
            $uri = str_replace($basePath, '', $uri);
        }

        return '/' . trim($uri, '/');
    }

    /**
     * Register a GET route
     */
    public function get(string $path, callable|string $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    /**
     * Register a POST route
     */
    public function post(string $path, callable|string $handler): self
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    /**
     * Register a route for any method
     */
    public function any(string $path, callable|string $handler): self
    {
        $this->addRoute(['GET', 'POST', 'PUT', 'DELETE'], $path, $handler);
        return $this;
    }

    /**
     * Add route to registry
     */
    private function addRoute(string|array $methods, string $path, callable|string $handler): void
    {
        $methods = is_array($methods) ? $methods : [$methods];

        $this->routes[] = [
            'methods' => $methods,
            'path' => $path,
            'pattern' => $this->pathToRegex($path),
            'handler' => $handler,
        ];
    }

    /**
     * Convert path with parameters to regex pattern
     * Example: /users/{id} -> /users/(\d+)
     */
    private function pathToRegex(string $path): string
    {
        $pattern = preg_replace(
            '/{(\w+)}/',
            '(?P<$1>[^/]+)',
            $path
        );
        return '^' . $pattern . '$';
    }

    /**
     * Match the current request to a route
     */
    public function match(): ?array
    {
        foreach ($this->routes as $route) {
            if (!in_array($this->currentMethod, $route['methods'])) {
                continue;
            }

            if (preg_match('#' . $route['pattern'] . '#', $this->currentPath, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'handler' => $route['handler'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    /**
     * Dispatch the request
     */
    public function dispatch(): Response
    {
        $match = $this->match();

        if ($match === null) {
            $response = new Response();
            $response->setStatus(404)->setBody('Route not found');
            return $response;
        }

        $handler = $match['handler'];
        $params = $match['params'];
        $request = new Request();
        $response = new Response();

        if (is_string($handler)) {
            [$controllerClass, $method] = explode('@', $handler);
            $controllerClass = 'App\\Controllers\\' . $controllerClass;
            $controller = new $controllerClass();
            $result = $controller->{$method}($request, $response, $params);

            // Handle both Response objects and string returns
            if ($result instanceof Response) {
                return $result;
            }

            $response->setBody((string)$result);
            return $response;
        }

        if (is_callable($handler)) {
            $result = $handler($request, $response, $params);

            // Handle both Response objects and string returns
            if ($result instanceof Response) {
                return $result;
            }

            $response->setBody((string)$result);
            return $response;
        }

        return $response;
    }

    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get current request path
     */
    public function getCurrentPath(): string
    {
        return $this->currentPath;
    }

    /**
     * Get current request method
     */
    public function getCurrentMethod(): string
    {
        return $this->currentMethod;
    }
}
