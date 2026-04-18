<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Post;

/**
 * CategoryController - handles category-related requests
 */
class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index(Request $request, Response $response, array $params = []): string
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        return $this->render('categories/index.tpl', [
            'title' => 'Все категории',
            'description' => 'Список всех категорий блога',
            'categories' => $categories,
        ]);
    }

    /**
     * Display posts in a specific category
     */
    public function show(Request $request, Response $response, array $params = []): string
    {
        $categoryModel = new Category();
        $postModel = new Post();

        // Get category by slug
        $slug = $params['slug'] ?? null;
        if (!$slug) {
            $response->setStatus(404)->setBody('Category not found');
            return 'Category not found';
        }

        $category = $categoryModel->findBySlug($slug);

        if (!$category) {
            $response->setStatus(404)->setBody('Category not found');
            return 'Category not found';
        }

        // Get posts in this category
        $posts = $postModel->getByCategory($category->id);

        return $this->render('categories/show.tpl', [
            'title' => 'Категория: ' . $category->name,
            'description' => $category->description,
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    /**
     * Create new category (admin)
     */
    public function create(Request $request, Response $response, array $params = []): string
    {
        if ($request->getMethod() === 'GET') {
            return $this->render('categories/create.tpl', [
                'title' => 'Создать категорию',
            ]);
        }

        // Handle POST
        $name = $request->input('name');
        $slug = $request->input('slug');
        $description = $request->input('description', '');

        if (!$name || !$slug) {
            return json_encode([
                'success' => false,
                'message' => 'Name and slug are required'
            ]);
        }

        $category = new Category();
        $category->name = $name;
        $category->slug = $slug;
        $category->description = $description;

        if ($category->save()) {
            return json_encode([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ]
            ]);
        }

        return json_encode([
            'success' => false,
            'message' => 'Failed to create category'
        ]);
    }

    /**
     * Update category (admin)
     */
    public function update(Request $request, Response $response, array $params = []): string
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            return json_encode([
                'success' => false,
                'message' => 'Category ID is required'
            ]);
        }

        $categoryModel = new Category();
        $category = $categoryModel->find((int)$id);

        if (!$category) {
            return json_encode([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        // Update properties
        $category->name = $request->input('name', $category->name);
        $category->slug = $request->input('slug', $category->slug);
        $category->description = $request->input('description', $category->description);

        if ($category->save()) {
            return json_encode([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ]
            ]);
        }

        return json_encode([
            'success' => false,
            'message' => 'Failed to update category'
        ]);
    }

    /**
     * Delete category (admin)
     */
    public function delete(Request $request, Response $response, array $params = []): string
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            return json_encode([
                'success' => false,
                'message' => 'Category ID is required'
            ]);
        }

        $categoryModel = new Category();
        $category = $categoryModel->find((int)$id);

        if (!$category) {
            return json_encode([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        if ($category->delete()) {
            return json_encode([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }

        return json_encode([
            'success' => false,
            'message' => 'Failed to delete category'
        ]);
    }
}
