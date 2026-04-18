<?php

declare(strict_types=1);

namespace App\Commands;

use App\Core\Database;
use Exception;

/**
 * MigrateCommand - Manage database migrations
 */
class MigrateCommand extends Command
{
    public function getName(): string
    {
        return 'migrate';
    }

    public function getDescription(): string
    {
        return 'Run pending migrations';
    }

    public function handle(): int
    {
        try {
            $db = Database::getInstance();
            if ($db === null) {
                $this->error('Database not initialized');
                return 1;
            }

            // Check for --fresh flag
            global $argv;
            $fresh = in_array('--fresh', $argv);

            if ($fresh) {
                $this->comment('Running migrate:fresh...');
                $this->fresh($db);
            } else {
                $this->comment('Running migrations...');
                $this->runMigrations($db);
            }

            return 0;
        } catch (Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
    }

    public function fresh(Database $db): int
    {
        try {
            $this->comment('Dropping all tables...');
            
            // Drop tables in reverse order of dependencies
            $tables = ['post_category', 'posts', 'categories', 'migrations'];
            foreach ($tables as $table) {
                $db->execute("DROP TABLE IF EXISTS {$table}");
                $this->info("Dropped table: {$table}");
            }

            $this->comment('Running migrations...');
            return $this->runMigrations($db);
        } catch (Exception $e) {
            $this->error('Fresh migration failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function runMigrations(Database $db): int
    {
        // Ensure migrations table exists
        $db->execute("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            batch INT NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Get all migration files
        $migrationPath = dirname(__DIR__, 2) . '/database/migrations';
        $files = $this->getMigrationFiles($migrationPath);

        if (empty($files)) {
            $this->warn('No migrations found');
            return 0;
        }

        // Get latest batch number
        $result = $db->fetchOne("SELECT MAX(batch) as latest_batch FROM migrations");
        $latestBatch = $result['latest_batch'] ?? 0;
        $nextBatch = $latestBatch + 1;

        $count = 0;
        foreach ($files as $file) {
            $version = basename($file, '.php');
            
            // Check if migration already executed
            $exists = $db->fetchOne("SELECT * FROM migrations WHERE migration = ?", [$version]);
            if ($exists !== null) {
                continue;
            }

            // Include and run migration
            require_once $file;
            $className = $this->getClassName($file);
            $migration = new $className();

            try {
                $migration->up($db);
                $db->execute("INSERT INTO migrations (migration, batch) VALUES (?, ?)", [$version, $nextBatch]);
                $this->info("Migrated: {$version}");
                $count++;
            } catch (Exception $e) {
                $this->error("Failed to migrate {$version}: " . $e->getMessage());
                return 1;
            }
        }

        if ($count === 0) {
            $this->comment('Nothing to migrate');
        } else {
            $this->info("Completed {$count} migrations");
        }

        return 0;
    }

    private function getMigrationFiles(string $path): array
    {
        $files = [];
        if (!is_dir($path)) {
            return $files;
        }

        foreach (scandir($path) as $file) {
            if ($file !== '.' && $file !== '..' && str_ends_with($file, '.php')) {
                $files[] = $path . '/' . $file;
            }
        }

        sort($files);
        return $files;
    }

    private function getClassName(string $file): string
    {
        $basename = basename($file, '.php');
        $parts = explode('_', $basename);
        
        // Remove timestamp parts and convert to camelcase
        array_splice($parts, 0, 4);
        $className = implode('', array_map('ucfirst', $parts));
        
        return 'App\\Database\\Migrations\\' . $className;
    }
}

