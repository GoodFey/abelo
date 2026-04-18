<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Category Model
 * Handles all category-related database operations
 */
class Category extends Model
{
    protected string $table = 'categories';

    public int $id;
    public string $name;
    public string $slug;
    public ?string $description = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public int $posts_count = 0;

    /**
     * Get all categories
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $rows = $this->db->fetchAll($query);

        $categories = [];
        foreach ($rows as $row) {
            $category = new static();
            $categories[] = $category->hydrate($row);
        }

        return $categories;
    }

    /**
     * Get categories that have at least one published post
     */
    public function getWithPosts(): array
    {
        $query = "SELECT c.*, COUNT(p.id) as posts_count FROM {$this->table} c
                  INNER JOIN post_category pc ON c.id = pc.category_id
                  INNER JOIN posts p ON p.id = pc.post_id
                  WHERE p.is_published = 1
                  GROUP BY c.id
                  ORDER BY c.name ASC";

        $rows = $this->db->fetchAll($query);

        $categories = [];
        foreach ($rows as $row) {
            $category = new static();
            $category = $category->hydrate($row);
            $category->posts_count = (int)$row['posts_count'];
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * Find category by ID
     */
    public function find(int $id): ?static
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $row = $this->db->fetchOne($query, [$id]);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * Find category by slug
     */
    public function findBySlug(string $slug): ?static
    {
        $query = "SELECT * FROM {$this->table} WHERE slug = ?";
        $row = $this->db->fetchOne($query, [$slug]);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * Get total count of categories
     */
    public function count(): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetchOne($query);
        return $result['total'] ?? 0;
    }

    /**
     * Save category (insert or update)
     */
    public function save(): bool
    {
        if (isset($this->id)) {
            // Update existing
            return $this->db->update($this->table, [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'updated_at' => date('Y-m-d H:i:s'),
            ], ['id' => $this->id]) > 0;
        } else {
            // Insert new
            $this->id = $this->db->insert($this->table, [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->id > 0;
        }
    }

    /**
     * Delete category
     */
    public function delete(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        return $this->db->delete($this->table, ['id' => $this->id]) > 0;
    }
}
