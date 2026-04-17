<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use App\Core\Database;

/**
 * CreateCategoriesTable Migration
 * Creates the categories table for blog categories
 */
class CreateCategoriesTable
{
    public function up(Database $db): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->execute($sql);
    }

    public function down(Database $db): void
    {
        $sql = "DROP TABLE IF EXISTS categories";
        $db->execute($sql);
    }

    public function getName(): string
    {
        return 'CreateCategoriesTable';
    }

    public function getVersion(): string
    {
        return '2024_01_01_000001';
    }
}
