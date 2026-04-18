<?php

declare(strict_types=1);

namespace App\Commands;

use App\Core\Database;
use Exception;
use ReflectionClass;

/**
 * SeedCommand - Run database seeders
 */
class SeedCommand extends Command
{
    public function getName(): string
    {
        return 'seed';
    }

    public function getDescription(): string
    {
        return 'Run seeders to populate database';
    }

    public function handle(): int
    {
        try {
            $this->comment('Running seeders...');

            $db = Database::getInstance();
            if ($db === null) {
                $this->error('Database not initialized');
                return 1;
            }

            // Get seeder files
            $seederPath = dirname(__DIR__, 2) . '/database/seeders';
            
            // Load base Seeder class
            require_once $seederPath . '/Seeder.php';
            
            $seeders = $this->getSeederClasses($seederPath);

            if (empty($seeders)) {
                $this->warn('No seeders found');
                return 0;
            }

            $count = 0;
            foreach ($seeders as $seederClass) {
                try {
                    $seeder = new $seederClass();
                    
                    // Check if it has a run method
                    if (!method_exists($seeder, 'run')) {
                        continue;
                    }

                    $seeder->run();
                    $count++;
                } catch (Exception $e) {
                    $this->error("Seeder failed: " . $e->getMessage());
                    return 1;
                }
            }

            $this->info("Completed {$count} seeders");
            return 0;
        } catch (Exception $e) {
            $this->error('Seeding failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function getSeederClasses(string $path): array
    {
        $seeders = [];

        if (!is_dir($path)) {
            return $seeders;
        }

        foreach (scandir($path) as $file) {
            if ($file !== '.' && $file !== '..' && $file !== 'Seeder.php' && str_ends_with($file, '.php')) {
                $filePath = $path . '/' . $file;
                require_once $filePath;
                
                $className = 'Database\\Seeders\\' . basename($file, '.php');
                
                if (class_exists($className)) {
                    // Only include if it extends Seeder
                    $reflection = new ReflectionClass($className);
                    if ($reflection->getParentClass() && $reflection->getParentClass()->getName() === 'Database\\Seeders\\Seeder') {
                        $seeders[] = $className;
                    }
                }
            }
        }

        return $seeders;
    }
}

