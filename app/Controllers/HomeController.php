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

        // Get categories that have posts
        $categories = $categoryModel->getWithPosts();

        // Get 3 latest posts for each category
        $postsByCategory = [];
        foreach ($categories as $category) {
            $allPosts = $postModel->getByCategory($category->id);
            // Filter published posts only
            $published = array_filter($allPosts, fn($p) => $p->is_published);
            // Get first 3
            $postsByCategory[$category->id] = [
                'category' => $category,
                'posts' => array_slice($published, 0, 3)
            ];
        }

        // Get most viewed/popular posts
        $popularPosts = $postModel->getMostViewed(5);

        return $this->render('home.tpl', [
            'title' => 'Abelo - Блог о веб-разработке',
            'description' => 'Статьи о PHP, JavaScript, веб-дизайне и DevOps',
            'postsByCategory' => $postsByCategory,
            'popularPosts' => $popularPosts,
            'categories' => $categories,
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
