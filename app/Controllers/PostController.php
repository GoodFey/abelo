<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Paginator;
use App\Core\Sorter;
use App\Models\Post;
use App\Models\Category;

/**
 * PostController - handles post-related requests
 */
class PostController extends Controller
{
    /**
     * Display all posts
     */
    public function index(Request $request, Response $response, array $params = []): string
    {
        $postModel = new Post();

        // Get page parameter for pagination
        $page = (int)($request->query('page', 1));
        $perPage = 10;

        // Get sort parameters
        $sortBy = $request->query('sort', 'published_at');
        $sortDir = $request->query('dir', 'desc');

        // Get all published posts
        $allPosts = $postModel->getPublished();

        // Apply sorting
        $sorter = new Sorter($allPosts, $sortBy, $sortDir);
        $sortedPosts = $sorter->getSorted();

        // Create paginator
        $paginator = new Paginator($sortedPosts, $perPage, $page);

        return $this->render('posts/index.tpl', [
            'title' => 'Все статьи',
            'description' => 'Полный список опубликованных статей',
            'posts' => $paginator->getItems(),
            'pagination' => $paginator->toArray(),
            'currentPage' => $paginator->getCurrentPage(),
            'totalPages' => $paginator->getTotalPages(),
            'total' => $paginator->getTotal(),
            'pageNumbers' => $paginator->getPageNumbers(),
            'sortBy' => $sorter->getSortBy(),
            'sortDir' => $sorter->getSortDir(),
            'sortIndicator' => [
                'title' => $sorter->getSortIndicator('title'),
                'published_at' => $sorter->getSortIndicator('published_at'),
                'views' => $sorter->getSortIndicator('views'),
            ],
        ]);
    }

    /**
     * Display a single post
     */
    public function show(Request $request, Response $response, array $params = []): string
    {
        $postModel = new Post();

        // Get post by slug
        $slug = $params['slug'] ?? null;
        if (!$slug) {
            $response->setStatus(404)->setBody('Post not found');
            return 'Post not found';
        }

        $post = $postModel->findBySlug($slug);

        if (!$post) {
            $response->setStatus(404)->setBody('Post not found');
            return 'Post not found';
        }

        // Check if post is published
        if (!$post->is_published) {
            $response->setStatus(403)->setBody('Post is not published');
            return 'Post is not published';
        }

        // Increment views counter with lock to prevent race condition
        $post->incrementViewsWithLock();

        // Get categories for this post
        $categories = $post->getCategories();

        // Get similar/related posts
        $similarPosts = $post->getSimilar(3);
        $recommendedPosts = $post->getRecommended(5);

        return $this->render('posts/show.tpl', [
            'title' => $post->title,
            'description' => $post->excerpt,
            'post' => $post,
            'categories' => $categories,
            'similarPosts' => $similarPosts,
            'recommendedPosts' => $recommendedPosts,
        ]);
    }

    /**
     * Create new post (admin)
     */
    public function create(Request $request, Response $response, array $params = []): string
    {
        if ($request->getMethod() === 'GET') {
            $categoryModel = new Category();
            $categories = $categoryModel->getAll();

            return $this->render('posts/create.tpl', [
                'title' => 'Создать статью',
                'categories' => $categories,
            ]);
        }

        // Handle POST
        $title = $request->input('title');
        $slug = $request->input('slug');
        $content = $request->input('content');
        $excerpt = $request->input('excerpt', '');
        $authorId = $request->input('author_id', 1);
        $isPublished = (bool)$request->input('is_published', false);
        $categoryIds = $request->input('categories', []);

        if (!$title || !$slug || !$content) {
            return json_encode([
                'success' => false,
                'message' => 'Title, slug, and content are required'
            ]);
        }

        $post = new Post();
        $post->title = $title;
        $post->slug = $slug;
        $post->content = $content;
        $post->excerpt = $excerpt;
        $post->author_id = (int)$authorId;
        $post->is_published = $isPublished;

        if ($isPublished) {
            $post->published_at = date('Y-m-d H:i:s');
        }

        if ($post->save()) {
            // Attach categories
            if (is_array($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    $post->attachCategory((int)$categoryId);
                }
            }

            return json_encode([
                'success' => true,
                'message' => 'Post created successfully',
                'post' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                ]
            ]);
        }

        return json_encode([
            'success' => false,
            'message' => 'Failed to create post'
        ]);
    }

    /**
     * Update post (admin)
     */
    public function update(Request $request, Response $response, array $params = []): string
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            return json_encode([
                'success' => false,
                'message' => 'Post ID is required'
            ]);
        }

        $postModel = new Post();
        $post = $postModel->find((int)$id);

        if (!$post) {
            return json_encode([
                'success' => false,
                'message' => 'Post not found'
            ]);
        }

        // Update properties
        $post->title = $request->input('title', $post->title);
        $post->slug = $request->input('slug', $post->slug);
        $post->content = $request->input('content', $post->content);
        $post->excerpt = $request->input('excerpt', $post->excerpt);
        $post->author_id = (int)$request->input('author_id', $post->author_id);

        $isPublished = $request->input('is_published');
        if ($isPublished !== null) {
            $post->is_published = (bool)$isPublished;
            if ($post->is_published && !$post->published_at) {
                $post->published_at = date('Y-m-d H:i:s');
            }
        }

        if ($post->save()) {
            return json_encode([
                'success' => true,
                'message' => 'Post updated successfully',
                'post' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                ]
            ]);
        }

        return json_encode([
            'success' => false,
            'message' => 'Failed to update post'
        ]);
    }

    /**
     * Delete post (admin)
     */
    public function delete(Request $request, Response $response, array $params = []): string
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            return json_encode([
                'success' => false,
                'message' => 'Post ID is required'
            ]);
        }

        $postModel = new Post();
        $post = $postModel->find((int)$id);

        if (!$post) {
            return json_encode([
                'success' => false,
                'message' => 'Post not found'
            ]);
        }

        if ($post->delete()) {
            return json_encode([
                'success' => true,
                'message' => 'Post deleted successfully'
            ]);
        }

        return json_encode([
            'success' => false,
            'message' => 'Failed to delete post'
        ]);
    }

    /**
     * Get post by ID (API)
     */
    public function getById(Request $request, Response $response, array $params = []): string
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            return json_encode([
                'success' => false,
                'message' => 'Post ID is required'
            ]);
        }

        $postModel = new Post();
        $post = $postModel->find((int)$id);

        if (!$post) {
            return json_encode([
                'success' => false,
                'message' => 'Post not found'
            ]);
        }

        return json_encode([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'is_published' => $post->is_published,
                'published_at' => $post->published_at,
                'created_at' => $post->created_at,
            ]
        ]);
    }
}
