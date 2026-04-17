<?php

declare(strict_types=1);

/**
 * Entry Point - public/index.php
 * This is the main entry point for all requests
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load autoloader
require BASE_PATH . '/vendor/autoload.php';

// Load environment variables (optional)
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

use App\Core\Router;
use App\Core\Response;

// Create router instance
$router = new Router();

// Load routes from routes/web.php
$routeLoader = require BASE_PATH . '/routes/web.php';
$routeLoader($router);

// Dispatch the request
try {
    $response = $router->dispatch();
    $response->send();
} catch (\Exception $e) {
    // Error handling
    http_response_code(500);
    echo 'Internal Server Error';

    // Log error (optional)
    error_log($e->getMessage());
}
