<?php

declare(strict_types=1);

namespace App;

use Smarty;

/**
 * Main Application class
 * Handles the bootstrap and routing
 */
class Application
{
    private Smarty $smarty;
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->initializeSmarty();
    }

    private function initializeSmarty(): void
    {
        $smartyFactory = require $this->basePath . '/config/smarty.php';
        $this->smarty = $smartyFactory();
    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function run(): void
    {
        // Заглушка для роутинга
        echo "Application started successfully!";
    }
}
