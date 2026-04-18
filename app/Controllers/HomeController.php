<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Post;
use App\Models\Category;

/**
 * HomeController - handles home page requests
 */
class HomeController extends Controller
{
    /**
     * Display home page with latest posts
     */
    public function index(Request $request, Response $response, array $params = []): string
    {
        $postModel = new Post();
        $categoryModel = new Category();

        // Get latest published posts
        $latestPosts = $postModel->getPublished();

        // Get first 5 posts for homepage
        $posts = array_slice($latestPosts, 0, 5);

        // Get most viewed/popular posts
        $popularPosts = $postModel->getMostViewed(5);

        // Get categories
        $categories = $categoryModel->getAll();

        // Get statistics
        $totalPosts = $postModel->count();
        $totalCategories = $categoryModel->count();

        return $this->render('home.tpl', [
            'title' => 'Abelo - Блог о веб-разработке',
            'description' => 'Статьи о PHP, JavaScript, веб-дизайне и DevOps',
            'posts' => $posts,
            'popularPosts' => $popularPosts,
            'categories' => $categories,
            'totalPosts' => $totalPosts,
            'totalCategories' => $totalCategories,
        ]);
    }

    /**
     * Display about page
     */
    public function about(Request $request, Response $response, array $params = []): string
    {
        return $this->render('about.tpl', [
            'title' => 'О блоге',
            'description' => 'Информация о проекте Abelo'
        ]);
    }
}
