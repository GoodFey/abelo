<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Response class - encapsulates HTTP response data
 */
class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';
    private array $jsonData = [];
    private bool $jsonMode = false;

    public function __construct()
    {
        $this->headers['Content-Type'] = 'text/html; charset=utf-8';
    }

    /**
     * Set the HTTP status code
     */
    public function setStatus(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Get the HTTP status code
     */
    public function getStatus(): int
    {
        return $this->statusCode;
    }

    /**
     * Set a response header
     */
    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Get a response header
     */
    public function getHeader(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * Get all response headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the response body
     */
    public function setBody(string $content): self
    {
        $this->body = $content;
        return $this;
    }

    /**
     * Append to the response body
     */
    public function appendBody(string $content): self
    {
        $this->body .= $content;
        return $this;
    }

    /**
     * Get the response body
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Send JSON response
     */
    public function json(array $data, int $statusCode = 200): self
    {
        $this->statusCode = $statusCode;
        $this->jsonData = $data;
        $this->jsonMode = true;
        $this->setHeader('Content-Type', 'application/json');
        return $this;
    }

    /**
     * Get JSON data
     */
    public function getJsonData(): array
    {
        return $this->jsonData;
    }

    /**
     * Send error JSON response
     */
    public function error(string $message, int $statusCode = 400): self
    {
        return $this->json(['error' => $message], $statusCode);
    }

    /**
     * Send success JSON response
     */
    public function success(array $data = [], int $statusCode = 200): self
    {
        return $this->json(['success' => true, ...$data], $statusCode);
    }

    /**
     * Redirect to a URL
     */
    public function redirect(string $url, int $statusCode = 302): self
    {
        $this->statusCode = $statusCode;
        $this->setHeader('Location', $url);
        return $this;
    }

    /**
     * Set a cookie
     */
    public function setCookie(string $name, string $value, array $options = []): self
    {
        $defaultOptions = [
            'expires' => time() + (86400 * 7),
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ];

        $options = array_merge($defaultOptions, $options);
        setcookie($name, $value, $options);

        return $this;
    }

    /**
     * Delete a cookie
     */
    public function deleteCookie(string $name): self
    {
        setcookie($name, '', ['expires' => time() - 3600]);
        return $this;
    }

    /**
     * Send the response to client
     */
    public function send(): void
    {
        // Send status code
        http_response_code($this->statusCode);

        // Send headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        // Send body
        if ($this->jsonMode) {
            echo json_encode($this->jsonData);
        } else {
            echo $this->body;
        }
    }

    /**
     * Check if response is JSON
     */
    public function isJson(): bool
    {
        return $this->jsonMode;
    }
}
