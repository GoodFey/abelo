<?php

declare(strict_types=1);

/**
 * Migration Tool - migrate.php
 * Handles database migrations: up, down, refresh, status
 *
 * Usage:
 *   php migrate.php up           - Run all pending migrations
 *   php migrate.php down         - Rollback last batch
 *   php migrate.php refresh      - Rollback all and run again
 *   php migrate.php status       - Show migration status
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
$command = $argv[1] ?? 'status';

// Initialize database
$db = new Database();

// Ensure migrations table exists
$db->execute("CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    batch INT NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Get migration files
function getMigrations(): array
{
    $path = BASE_PATH . '/database/migrations';
    $files = [];

    if (is_dir($path)) {
        $items = scandir($path);
        foreach ($items as $item) {
            if (str_ends_with($item, '.php')) {
                $files[] = $item;
            }
        }
    }

    sort($files);
    return $files;
}

// Get migration class name from filename
function getMigrationClass(string $file): string
{
    $name = str_replace(['.php', '_'], ['', ''], basename($file, '.php'));
    // Convert snake_case to CamelCase for the class part
    $parts = explode('_', basename($file, '.php'));
    array_shift($parts); // Remove timestamp

    $className = '';
    foreach ($parts as $part) {
        $className .= ucfirst($part);
    }

    return $className;
}

// Get executed migrations
function getExecutedMigrations(Database $db): array
{
    $result = $db->query("SELECT migration FROM migrations ORDER BY batch DESC, id DESC");
    $executed = [];

    foreach ($result as $row) {
        $executed[] = $row['migration'];
    }

    return $executed;
}

// Get highest batch number
function getHighestBatch(Database $db): int
{
    $result = $db->query("SELECT MAX(batch) as max_batch FROM migrations");
    return $result[0]['max_batch'] ?? 0;
}

// Load and run migration
function runMigration(string $file, Database $db, string $direction): bool
{
    $path = BASE_PATH . '/database/migrations/' . $file;

    if (!file_exists($path)) {
        echo "❌ Migration file not found: {$file}\n";
        return false;
    }

    require_once $path;

    $className = getMigrationClass($file);
    $fullClass = 'App\\Database\\Migrations\\' . $className;

    if (!class_exists($fullClass)) {
        echo "❌ Migration class not found: {$fullClass}\n";
        return false;
    }

    try {
        $migration = new $fullClass();

        if ($direction === 'up') {
            $migration->up($db);
            $db->execute("INSERT INTO migrations (migration, batch) VALUES (?, ?)",
                [basename($file, '.php'), getHighestBatch($db) + 1]);
            echo "✅ {$file}\n";
        } else {
            $migration->down($db);
            $db->execute("DELETE FROM migrations WHERE migration = ?",
                [basename($file, '.php')]);
            echo "✅ Rolled back: {$file}\n";
        }

        return true;
    } catch (\Exception $e) {
        echo "❌ Error in {$file}: " . $e->getMessage() . "\n";
        return false;
    }
}

// Command handlers
switch ($command) {
    case 'up':
        echo "🚀 Running pending migrations...\n\n";

        $executed = getExecutedMigrations($db);
        $migrations = getMigrations();

        $count = 0;
        foreach ($migrations as $file) {
            if (!in_array(basename($file, '.php'), $executed)) {
                if (runMigration($file, $db, 'up')) {
                    $count++;
                }
            }
        }

        echo "\n✅ {$count} migration(s) executed.\n";
        break;

    case 'down':
        echo "⬇️  Rolling back migrations...\n\n";

        $result = $db->query("SELECT migration, batch FROM migrations WHERE batch = (SELECT MAX(batch) FROM migrations) ORDER BY id DESC");

        $count = 0;
        foreach ($result as $row) {
            $file = $row['migration'] . '.php';
            if (runMigration($file, $db, 'down')) {
                $count++;
            }
        }

        echo "\n✅ {$count} migration(s) rolled back.\n";
        break;

    case 'refresh':
        echo "🔄 Refreshing database...\n\n";

        // Rollback all
        echo "Rolling back all migrations...\n";
        $allMigrations = $db->query("SELECT migration FROM migrations ORDER BY batch DESC, id DESC");

        foreach ($allMigrations as $row) {
            $file = $row['migration'] . '.php';
            runMigration($file, $db, 'down');
        }

        echo "\n";

        // Run all
        echo "Running all migrations...\n";
        $migrations = getMigrations();

        foreach ($migrations as $file) {
            runMigration($file, $db, 'up');
        }

        echo "\n✅ Database refreshed.\n";
        break;

    case 'status':
        echo "📋 Migration Status\n";
        echo str_repeat("=", 50) . "\n\n";

        $executed = getExecutedMigrations($db);
        $migrations = getMigrations();

        if (empty($migrations)) {
            echo "No migrations found.\n";
            break;
        }

        foreach ($migrations as $file) {
            $name = basename($file, '.php');
            $status = in_array($name, $executed) ? '✅' : '⏳';
            echo "{$status} {$name}\n";
        }

        echo "\n" . str_repeat("=", 50) . "\n";
        echo "✅ = Executed\n";
        echo "⏳ = Pending\n";
        break;

    default:
        echo "Unknown command: {$command}\n\n";
        echo "Available commands:\n";
        echo "  php migrate.php up       - Run pending migrations\n";
        echo "  php migrate.php down     - Rollback last batch\n";
        echo "  php migrate.php refresh  - Refresh database\n";
        echo "  php migrate.php status   - Show status\n";
        break;
}
