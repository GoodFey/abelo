<?php

declare(strict_types=1);

/**
 * Database Seeder Tool - seed.php
 * Runs seeders to populate the database with sample data
 *
 * Usage:
 *   php seed.php              - Run all seeders
 *   php seed.php CategorySeeder - Run specific seeder
 *   php seed.php list         - List all seeders
 */

define('BASE_PATH', __DIR__);

// Load autoloader
require BASE_PATH . '/vendor/autoload.php';

// Load environment variables
if (file_exists(BASE_PATH . '/.env')) {
    $env = file_get_contents(BASE_PATH . '/.env');
    foreach (explode("\n", $env) as $line) {
        $line = trim($line);
        if ($line && !str_starts_with($line, '#')) {
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }
}

use App\Core\Database;

// Get command argument
$command = $argv[1] ?? 'all';

// Initialize database connection
if (Database::getInstance() === null) {
    Database::initialize(
        "mysql:host=" . ($_ENV['DB_HOST'] ?? 'localhost') . ":" . ($_ENV['DB_PORT'] ?? 3306) . ";dbname=" . ($_ENV['DB_NAME'] ?? 'abelo'),
        $_ENV['DB_USER'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
}

/**
 * Get all seeder files
 */
function getSeederFiles(): array
{
    $path = BASE_PATH . '/app/Database/Seeders';
    $files = [];

    if (is_dir($path)) {
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..' && $item !== 'Seeder.php' && str_ends_with($item, '.php')) {
                $files[] = $item;
            }
        }
    }

    sort($files);
    return $files;
}

/**
 * Get seeder class name from filename
 */
function getSeederClassName(string $file): string
{
    return 'App\\Database\\Seeders\\' . basename($file, '.php');
}

/**
 * Run a single seeder
 */
function runSeeder(string $file): bool
{
    try {
        $className = getSeederClassName($file);

        if (!class_exists($className)) {
            echo "❌ Seeder class not found: {$className}\n";
            return false;
        }

        $seeder = new $className();
        $seeder->run();

        return true;
    } catch (\Exception $e) {
        echo "❌ Error running seeder {$file}: " . $e->getMessage() . "\n";
        return false;
    }
}

// Handle commands
switch ($command) {
    case 'list':
        echo "📋 Available Seeders\n";
        echo str_repeat("=", 50) . "\n\n";

        $files = getSeederFiles();
        if (empty($files)) {
            echo "No seeders found.\n";
            break;
        }

        foreach ($files as $file) {
            echo "  • " . basename($file, '.php') . "\n";
        }

        echo "\nRun with: php seed.php {SeederName}\n";
        break;

    case 'all':
        echo "🌱 Running all seeders...\n\n";

        $files = getSeederFiles();
        $count = 0;
        $errors = 0;

        foreach ($files as $file) {
            echo str_repeat("-", 50) . "\n";
            if (runSeeder($file)) {
                $count++;
            } else {
                $errors++;
            }
            echo "\n";
        }

        echo str_repeat("=", 50) . "\n";
        echo "✅ Seeders completed: {$count} successful\n";
        if ($errors > 0) {
            echo "❌ Errors: {$errors}\n";
        }
        break;

    default:
        // Try to run specific seeder
        $file = $command . '.php';
        $path = BASE_PATH . '/app/Database/Seeders/' . $file;

        if (!file_exists($path)) {
            echo "❌ Seeder not found: {$command}\n\n";
            echo "Available seeders:\n";
            $files = getSeederFiles();
            foreach ($files as $f) {
                echo "  • " . basename($f, '.php') . "\n";
            }
            break;
        }

        echo "🌱 Running {$command}...\n\n";
        if (runSeeder($file)) {
            echo "\n✅ {$command} completed successfully.\n";
        } else {
            echo "\n❌ {$command} failed.\n";
        }
        break;
}
