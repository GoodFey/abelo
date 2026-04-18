<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Post Model
 * Handles all post-related database operations
 */
class Post extends Model
{
    protected string $table = 'posts';

    public int $id;
    public string $title;
    public string $slug;
    public string $content;
    public ?string $excerpt = null;
    public ?int $author_id = null;
    public bool $is_published = false;
    public int $views = 0;
    public ?string $published_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    /**
     * Get all posts
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $rows = $this->db->fetchAll($query);

        $posts = [];
        foreach ($rows as $row) {
            $post = new static();
            $posts[] = $post->hydrate($row);
        }

        return $posts;
    }

    /**
     * Find post by ID
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
     * Get posts by category
     */
    public function getByCategory(int $categoryId): array
    {
        $query = "SELECT p.* FROM {$this->table} p
                  INNER JOIN post_category pc ON p.id = pc.post_id
                  WHERE pc.category_id = ?
                  ORDER BY p.created_at DESC";

        $rows = $this->db->fetchAll($query, [$categoryId]);

        $posts = [];
        foreach ($rows as $row) {
            $post = new static();
            $posts[] = $post->hydrate($row);
        }

        return $posts;
    }

    /**
     * Find post by slug
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
     * Get published posts only
     */
    public function getPublished(): array
    {
        $query = "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY published_at DESC";
        $rows = $this->db->fetchAll($query);

        $posts = [];
        foreach ($rows as $row) {
            $post = new static();
            $posts[] = $post->hydrate($row);
        }

        return $posts;
    }

    /**
     * Get post categories
     */
    public function getCategories(): array
    {
        if (!isset($this->id)) {
            return [];
        }

        $query = "SELECT c.* FROM categories c
                  INNER JOIN post_category pc ON c.id = pc.category_id
                  WHERE pc.post_id = ?
                  ORDER BY c.name ASC";

        $rows = $this->db->fetchAll($query, [$this->id]);

        $categories = [];
        foreach ($rows as $row) {
            $category = new Category();
            $categories[] = $category->hydrate($row);
        }

        return $categories;
    }

    /**
     * Attach category to post
     */
    public function attachCategory(int $categoryId): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        return $this->db->insert('post_category', [
            'post_id' => $this->id,
            'category_id' => $categoryId,
        ]) > 0;
    }

    /**
     * Detach category from post
     */
    public function detachCategory(int $categoryId): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        return $this->db->delete('post_category', [
            'post_id' => $this->id,
            'category_id' => $categoryId,
        ]) > 0;
    }

    /**
     * Get total count of posts
     */
    public function count(): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetchOne($query);
        return $result['total'] ?? 0;
    }

    /**
     * Save post (insert or update)
     */
    public function save(): bool
    {
        if (isset($this->id)) {
            // Update existing
            return $this->db->update($this->table, [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'author_id' => $this->author_id,
                'is_published' => $this->is_published ? 1 : 0,
                'published_at' => $this->published_at,
                'updated_at' => date('Y-m-d H:i:s'),
            ], ['id' => $this->id]) > 0;
        } else {
            // Insert new
            $this->id = $this->db->insert($this->table, [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'author_id' => $this->author_id,
                'is_published' => $this->is_published ? 1 : 0,
                'published_at' => $this->published_at,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->id > 0;
        }
    }

    /**
     * Delete post
     */
    public function delete(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        return $this->db->delete($this->table, ['id' => $this->id]) > 0;
    }

    /**
     * Increment views counter
     * 
     * @deprecated Use incrementViewsWithLock() instead to prevent race conditions
     */
    public function incrementViews(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        return $this->db->execute(
            "UPDATE {$this->table} SET views = views + 1 WHERE id = ?",
            [$this->id]
        );
    }

    /**
     * Increment views counter with row-level lock (prevents race condition)
     * Uses SELECT ... FOR UPDATE to lock the row during transaction
     */
    public function incrementViewsWithLock(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        try {
            // Start transaction
            $this->db->beginTransaction();

            // Lock the row for update
            $query = "SELECT views FROM {$this->table} WHERE id = ? FOR UPDATE";
            $this->db->fetchOne($query, [$this->id]);

            // Increment views
            $updated = $this->db->execute(
                "UPDATE {$this->table} SET views = views + 1 WHERE id = ?",
                [$this->id]
            );

            // Commit transaction
            $this->db->commit();

            return $updated;
        } catch (\Exception $e) {
            // Rollback on error
            try {
                $this->db->rollback();
            } catch (\Exception) {
                // Ignore rollback error
            }

            return false;
        }
    }

    /**
     * Get views count
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * Set views count
     */
    public function setViews(int $count): void
    {
        $this->views = max(0, $count);
    }

    /**
     * Get most viewed posts
     */
    public function getMostViewed(int $limit = 5): array
    {
        $query = "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY views DESC LIMIT {$limit}";
        $rows = $this->db->fetchAll($query);

        $posts = [];
        foreach ($rows as $row) {
            $post = new static();
            $posts[] = $post->hydrate($row);
        }

        return $posts;
    }

    /**
     * Get similar posts by category
     */
    public function getSimilar(int $limit = 3): array
    {
        if (!isset($this->id)) {
            return [];
        }

        // Get categories for this post
        $categories = $this->getCategories();

        if (empty($categories)) {
            return [];
        }

        $categoryIds = array_map(function ($cat) {
            return $cat->id;
        }, $categories);

        // Get posts from same categories
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $query = "SELECT DISTINCT p.* FROM {$this->table} p
                  INNER JOIN post_category pc ON p.id = pc.post_id
                  WHERE pc.category_id IN ({$placeholders})
                  AND p.id != ?
                  AND p.is_published = 1
                  ORDER BY p.published_at DESC
                  LIMIT {$limit}";

        $params = array_merge($categoryIds, [$this->id]);
        $rows = $this->db->fetchAll($query, $params);

        $posts = [];
        foreach ($rows as $row) {
            $post = new static();
            $posts[] = $post->hydrate($row);
        }

        return $posts;
    }

    /**
     * Get related posts by tags/keywords
     */
    public function getRelatedByKeywords(int $limit = 5): array
    {
        if (!isset($this->id) || empty($this->title)) {
            return [];
        }

        // Simple keyword extraction from title
        $keywords = preg_split('/\s+/', strtolower($this->title));
        $keywords = array_filter($keywords, function ($k) {
            return strlen($k) > 3; // Only words longer than 3 chars
        });

        if (empty($keywords)) {
            return [];
        }

        // Search for posts with similar titles
        $like_conditions = [];
        $params = [];

        foreach ($keywords as $keyword) {
            $like_conditions[] = "p.title LIKE ?";
            $params[] = "%{$keyword}%";
        }

        $where = implode(' OR ', $like_conditions);

        $query = "SELECT p.* FROM {$this->table} p
                  WHERE ({$where})
                  AND p.id != ?
                  AND p.is_published = 1
                  ORDER BY p.published_at DESC
                  LIMIT {$limit}";

        $params[] = $this->id;
        $rows = $this->db->fetchAll($query, $params);

        $posts = [];
        foreach ($rows as $row) {
            $post = new static();
            $posts[] = $post->hydrate($row);
        }

        return $posts;
    }

    /**
     * Get recommended posts (similar + popular)
     */
    public function getRecommended(int $limit = 5): array
    {
        $similar = $this->getSimilar($limit);

        // If not enough similar posts, add popular ones
        if (count($similar) < $limit) {
            $remaining = $limit - count($similar);
            $popular = $this->getMostViewed($remaining);

            // Merge and remove duplicates
            $similarIds = array_map(function ($p) {
                return $p->id;
            }, $similar);

            foreach ($popular as $post) {
                if (!in_array($post->id, $similarIds)) {
                    $similar[] = $post;
                    if (count($similar) >= $limit) {
                        break;
                    }
                }
            }
        }

        return array_slice($similar, 0, $limit);
    }
}
