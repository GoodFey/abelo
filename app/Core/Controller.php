<?php

declare(strict_types=1);

namespace App\Core;
use Smarty;

/**
 * Base Controller class
 * All controllers should extend this class
 */
abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected Smarty $smarty;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->smarty = $this->getSmarty();
    }

    /**
     * Get Smarty instance from config
     */
    protected function getSmarty(): Smarty
    {
        $smartyFactory = require dirname(__DIR__, 2) . '/config/smarty.php';
        return $smartyFactory();
    }

    /**
     * Get the request object
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the response object
     */
    protected function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Render a template with data
     */
    protected function render(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty->fetch($template);
    }

    /**
     * Render a template and send it via response
     */
    protected function view(string $template, array $data = []): Response
    {
        $content = $this->render($template, $data);
        $this->response->setBody($content);
        return $this->response;
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): Response
    {
        return $this->response->json($data, $statusCode);
    }

    /**
     * Return success JSON response
     */
    protected function success(array $data = [], int $statusCode = 200): Response
    {
        return $this->response->success($data, $statusCode);
    }

    /**
     * Return error JSON response
     */
    protected function error(string $message, int $statusCode = 400): Response
    {
        return $this->response->error($message, $statusCode);
    }

    /**
     * Redirect to URL
     */
    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return $this->response->redirect($url, $statusCode);
    }

    /**
     * Get input from request
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $this->request->input($key, $default);
    }

    /**
     * Get query parameter from request
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return $this->request->query($key, $default);
    }
}
