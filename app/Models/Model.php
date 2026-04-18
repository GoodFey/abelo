<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * Base Model class
 * Provides common database operations for all models
 */
abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        // Инициализируем БД если нужно
        if (Database::getInstance() === null) {
            Database::initialize(
                '',  // DSN будет построен из .env
                $_ENV['DB_USER'] ?? 'root',
                $_ENV['DB_PASSWORD'] ?? '',
                []   // опции PDO по умолчанию
            );
        }
        $this->db = Database::getInstance();
    }

    /**
     * Get the table name
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get the primary key column name
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Get database instance
     */
    protected function getDb(): Database
    {
        return $this->db;
    }

    /**
     * Convert database row to model instance
     */
    protected function hydrate(array $data): static
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }
}
