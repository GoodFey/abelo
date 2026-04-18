<?php

declare(strict_types=1);

namespace App\Commands;

/**
 * Base Command class
 * All commands should extend this class
 */
abstract class Command
{
    protected array $arguments = [];
    protected array $options = [];

    public function __construct(array $arguments = [], array $options = [])
    {
        $this->arguments = $arguments;
        $this->options = $options;
    }

    /**
     * Execute the command
     */
    abstract public function handle(): int;

    /**
     * Get command name
     */
    abstract public function getName(): string;

    /**
     * Get command description
     */
    abstract public function getDescription(): string;

    /**
     * Print output
     */
    protected function info(string $message): void
    {
        echo "\033[0;32m✅ {$message}\033[0m\n";
    }

    protected function comment(string $message): void
    {
        echo "\033[0;34mℹ️  {$message}\033[0m\n";
    }

    protected function warn(string $message): void
    {
        echo "\033[1;33m⚠️  {$message}\033[0m\n";
    }

    protected function error(string $message): void
    {
        echo "\033[0;31m❌ {$message}\033[0m\n";
    }

    protected function line(string $message = ''): void
    {
        echo "{$message}\n";
    }
}

