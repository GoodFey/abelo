<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use App\Core\Database;

/**
 * CreatePostsTable Migration
 * Creates the posts table for blog posts
 */
class CreatePostsTable
{
    public function up(Database $db): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content LONGTEXT NOT NULL,
            excerpt VARCHAR(500),
            image VARCHAR(255),
            is_published BOOLEAN DEFAULT FALSE,
            views INT DEFAULT 0,
            published_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->execute($sql);
    }

    public function down(Database $db): void
    {
        $sql = "DROP TABLE IF EXISTS posts";
        $db->execute($sql);
    }

    public function getName(): string
    {
        return 'CreatePostsTable';
    }

    public function getVersion(): string
    {
        return '2024_01_01_000002';
    }
}
