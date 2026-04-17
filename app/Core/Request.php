<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Request class - encapsulates HTTP request data
 */
class Request
{
    private array $query;
    private array $post;
    private array $server;
    private array $cookies;
    private array $files;
    private ?string $rawBody = null;
    private array $parsedBody;

    public function __construct()
    {
        $this->query = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;
        $this->parsedBody = [];
    }

    /**
     * Get a query parameter
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Get all query parameters
     */
    public function getAllQuery(): array
    {
        return $this->query;
    }

    /**
     * Get a POST parameter
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Get all POST parameters
     */
    public function getAllInput(): array
    {
        return $this->post;
    }

    /**
     * Get a cookie value
     */
    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }

    /**
     * Get all cookies
     */
    public function getAllCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Get a server variable
     */
    public function server(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }

    /**
     * Get a file from upload
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get all uploaded files
     */
    public function getAllFiles(): array
    {
        return $this->files;
    }

    /**
     * Get the request method (GET, POST, PUT, DELETE, etc.)
     */
    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Check if request method is GET
     */
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    /**
     * Check if request method is POST
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax(): bool
    {
        return ($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    /**
     * Get the request path
     */
    public function path(): string
    {
        return parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    }

    /**
     * Get the raw request body
     */
    public function getRawBody(): string
    {
        if ($this->rawBody === null) {
            $this->rawBody = file_get_contents('php://input');
        }
        return $this->rawBody;
    }

    /**
     * Get parsed JSON body
     */
    public function json(): array
    {
        if (empty($this->parsedBody)) {
            $this->parsedBody = json_decode($this->getRawBody(), true) ?? [];
        }
        return $this->parsedBody;
    }

    /**
     * Get a JSON parameter
     */
    public function jsonInput(string $key, mixed $default = null): mixed
    {
        return $this->json()[$key] ?? $default;
    }

    /**
     * Get the request URL
     */
    public function url(): string
    {
        $protocol = isset($this->server['HTTPS']) && $this->server['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host . $this->path();
    }

    /**
     * Get the full request URI
     */
    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }
}
