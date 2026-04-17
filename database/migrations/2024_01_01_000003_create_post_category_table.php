<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use App\Core\Database;

/**
 * CreatePostCategoryTable Migration
 * Creates the pivot table for many-to-many relationship between posts and categories
 */
class CreatePostCategoryTable
{
    public function up(Database $db): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS post_category (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            category_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_post_category (post_id, category_id),
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->execute($sql);
    }

    public function down(Database $db): void
    {
        $sql = "DROP TABLE IF EXISTS post_category";
        $db->execute($sql);
    }

    public function getName(): string
    {
        return 'CreatePostCategoryTable';
    }

    public function getVersion(): string
    {
        return '2024_01_01_000003';
    }
}
