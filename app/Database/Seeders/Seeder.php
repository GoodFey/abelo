<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Core\Database;

/**
 * Base Seeder class
 * Provides common methods for all seeders
 */
abstract class Seeder
{
    protected Database $db;

    public function __construct()
    {
        if (Database::getInstance() === null) {
            Database::initialize(
                $_ENV['DB_HOST'] ?? 'localhost',
                $_ENV['DB_NAME'] ?? 'abelo',
                $_ENV['DB_USER'] ?? 'root',
                $_ENV['DB_PASSWORD'] ?? ''
            );
        }
        $this->db = Database::getInstance();
    }

    /**
     * Run the seeder
     */
    abstract public function run(): void;

    /**
     * Get database instance
     */
    protected function getDb(): Database
    {
        return $this->db;
    }

    /**
     * Clear table data
     */
    protected function truncate(string $table): void
    {
        try {
            $this->db->execute("TRUNCATE TABLE {$table}");
            echo "✅ Truncated {$table}\n";
        } catch (\Exception $e) {
            echo "❌ Error truncating {$table}: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Insert multiple rows
     */
    protected function insertMany(string $table, array $rows): int
    {
        $count = 0;
        foreach ($rows as $row) {
            try {
                $this->db->insert($table, $row);
                $count++;
            } catch (\Exception $e) {
                echo "❌ Error inserting into {$table}: " . $e->getMessage() . "\n";
            }
        }
        return $count;
    }
}
