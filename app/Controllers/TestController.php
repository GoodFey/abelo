<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Logger;
use App\Models\Post;
use App\Models\Category;

/**
 * TestController - Database testing and debugging
 */
class TestController extends Controller
{
    /**
     * Test database connection and basic queries
     */
    public function index(Request $request, Response $response, array $params = []): string
    {
        $tests = [];

        try {
            // Test 1: Database connection
            $tests['connection'] = [
                'name' => 'Database Connection',
                'status' => 'OK',
                'message' => 'Successfully connected to MySQL',
            ];

            // Test 2: Count posts
            $postModel = new Post();
            $allPosts = $postModel->getAll();
            $tests['posts_count'] = [
                'name' => 'Posts Count',
                'status' => 'OK',
                'message' => count($allPosts) . ' posts found in database',
                'count' => count($allPosts),
            ];

            // Test 3: Count categories
            $categoryModel = new Category();
            $allCategories = $categoryModel->getAll();
            $tests['categories_count'] = [
                'name' => 'Categories Count',
                'status' => 'OK',
                'message' => count($allCategories) . ' categories found in database',
                'count' => count($allCategories),
            ];

            // Test 4: Get first post
            $firstPost = null;
            if (!empty($allPosts)) {
                $firstPost = $allPosts[0];
                $tests['first_post'] = [
                    'name' => 'First Post',
                    'status' => 'OK',
                    'message' => 'Post found: ' . $firstPost->title,
                    'title' => $firstPost->title,
                    'slug' => $firstPost->slug,
                ];
            } else {
                $tests['first_post'] = [
                    'name' => 'First Post',
                    'status' => 'WARNING',
                    'message' => 'No posts found in database',
                ];
            }

            // Test 5: Get first category
            $firstCategory = null;
            if (!empty($allCategories)) {
                $firstCategory = $allCategories[0];
                $tests['first_category'] = [
                    'name' => 'First Category',
                    'status' => 'OK',
                    'message' => 'Category found: ' . $firstCategory->name,
                    'name_cat' => $firstCategory->name,
                    'slug_cat' => $firstCategory->slug,
                ];
            } else {
                $tests['first_category'] = [
                    'name' => 'First Category',
                    'status' => 'WARNING',
                    'message' => 'No categories found in database',
                ];
            }

            // Test 6: Test find by ID
            if ($firstPost !== null && isset($firstPost->id)) {
                $foundPost = $postModel->find($firstPost->id);
                if ($foundPost !== null) {
                    $tests['find_by_id'] = [
                        'name' => 'Find Post by ID',
                        'status' => 'OK',
                        'message' => 'Successfully found post with ID ' . $firstPost->id,
                        'id' => $firstPost->id,
                    ];
                } else {
                    $tests['find_by_id'] = [
                        'name' => 'Find Post by ID',
                        'status' => 'ERROR',
                        'message' => 'Failed to find post with ID ' . $firstPost->id,
                    ];
                }
            }

            // Test 7: Test find by slug
            if ($firstPost !== null && isset($firstPost->slug)) {
                $foundPost = $postModel->findBySlug($firstPost->slug);
                if ($foundPost !== null) {
                    $tests['find_by_slug'] = [
                        'name' => 'Find Post by Slug',
                        'status' => 'OK',
                        'message' => 'Successfully found post with slug: ' . $firstPost->slug,
                        'slug' => $firstPost->slug,
                    ];
                } else {
                    $tests['find_by_slug'] = [
                        'name' => 'Find Post by Slug',
                        'status' => 'ERROR',
                        'message' => 'Failed to find post with slug: ' . $firstPost->slug,
                    ];
                }
            }

        } catch (\Exception $e) {
            Logger::getInstance()->error('Database test failed', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            $tests['error'] = [
                'name' => 'Error',
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }

        return $this->render('test/index.tpl', [
            'tests' => $tests,
        ]);
    }
}

