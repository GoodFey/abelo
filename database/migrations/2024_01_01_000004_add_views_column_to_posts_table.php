<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use App\Core\Database;

/**
 * AddViewsColumnToPostsTable Migration
 * Adds views counter to track post popularity
 */
class AddViewsColumnToPostsTable
{
    public function up(Database $db): void
    {
        $sql = "ALTER TABLE posts ADD COLUMN views INT DEFAULT 0 AFTER is_published";
        $db->execute($sql);
    }

    public function down(Database $db): void
    {
        $sql = "ALTER TABLE posts DROP COLUMN views";
        $db->execute($sql);
    }

    public function getName(): string
    {
        return 'AddViewsColumnToPostsTable';
    }

    public function getVersion(): string
    {
        return '2024_01_01_000004';
    }
}
