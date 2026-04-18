<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Logger - Centralized logging system
 * Logs all errors, warnings, and info to storage/logs/app.log
 */
class Logger
{
    private static ?self $instance = null;
    private string $logPath;
    private string $currentDate;

    private function __construct()
    {
        $basePath = dirname(__FILE__, 3);
        $this->logPath = $basePath . '/storage/logs';
        $this->currentDate = date('Y-m-d');

        // Create logs directory if it doesn't exist
        if (!is_dir($this->logPath)) {
            if (!mkdir($concurrentDirectory = $this->logPath, 0755, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
    }

    /**
     * Get Logger singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get current log file path
     */
    private function getLogFile(): string
    {
        $filename = 'app-' . $this->currentDate . '.log';
        return $this->logPath . '/' . $filename;
    }

    /**
     * Write message to log file
     */
    private function write(string $level, string $message, ?array $context = null): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logFile = $this->getLogFile();

        $logMessage = "[{$timestamp}] {$level}: {$message}";

        if ($context !== null && !empty($context)) {
            $logMessage .= " | Context: " . json_encode($context, JSON_UNESCAPED_SLASHES);
        }

        $logMessage .= PHP_EOL;

        // Append to log file
        @file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log an info message
     */
    public function info(string $message, ?array $context = null): void
    {
        $this->write('INFO', $message, $context);
    }

    /**
     * Log a warning message
     */
    public function warning(string $message, ?array $context = null): void
    {
        $this->write('WARNING', $message, $context);
    }

    /**
     * Log an error message
     */
    public function error(string $message, ?array $context = null): void
    {
        $this->write('ERROR', $message, $context);
    }

    /**
     * Log a critical error
     */
    public function critical(string $message, ?array $context = null): void
    {
        $this->write('CRITICAL', $message, $context);
    }

    /**
     * Log a debug message
     */
    public function debug(string $message, ?array $context = null): void
    {
        $this->write('DEBUG', $message, $context);
    }

    /**
     * Log database query
     */
    public function query(string $query, array $params = []): void
    {
        $context = !empty($params) ? ['params' => $params] : null;
        $this->write('QUERY', $query, $context);
    }

    /**
     * Get all log files
     */
    public function getLogFiles(): array
    {
        if (!is_dir($this->logPath)) {
            return [];
        }

        $files = glob($this->logPath . '/app-*.log');
        return array_reverse(array_map('basename', $files));
    }

    /**
     * Get log file content
     */
    public function getLogContent(?string $filename = null): string
    {
        if ($filename === null) {
            $filename = 'app-' . $this->currentDate . '.log';
        }

        $filepath = $this->logPath . '/' . basename($filename);

        if (!file_exists($filepath)) {
            return 'Log file not found';
        }

        return file_get_contents($filepath);
    }

    /**
     * Clear all logs
     */
    public function clearLogs(): bool
    {
        if (!is_dir($this->logPath)) {
            return false;
        }

        $files = glob($this->logPath . '/app-*.log');
        foreach ($files as $file) {
            @unlink($file);
        }

        return true;
    }

    /**
     * Get log file size in MB
     */
    public function getLogSize(): float
    {
        $logFile = $this->getLogFile();

        if (!file_exists($logFile)) {
            return 0;
        }

        return round(filesize($logFile) / 1024 / 1024, 2);
    }
}

