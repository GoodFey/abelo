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
            if (!is_string($key) || !property_exists($this, $key)) {
                continue;
            }

            // Get the type from reflection to cast properly
            $reflection = new \ReflectionClass($this);
            $property = $reflection->getProperty($key);
            $type = $property->getType();

            if ($type !== null) {
                $typeName = $type->getName();
                
                if ($typeName === 'bool' && $value !== null) {
                    $value = (bool) $value;
                } elseif ($typeName === 'int' && $value !== null) {
                    $value = (int) $value;
                } elseif ($typeName === 'string' && $value !== null) {
                    $value = (string) $value;
                } elseif ($typeName === 'float' && $value !== null) {
                    $value = (float) $value;
                }
            }

            $this->$key = $value;
        }
        return $this;
    }
}
