<?php

declare(strict_types=1);

namespace App\Database\Seeders;

/**
 * PostSeeder
 * Seeds the posts table with sample data
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Running PostSeeder...\n";

        // Clear existing data
        $this->truncate('post_category');
        $this->truncate('posts');

        // Sample posts data
        $posts = [
            [
                'title' => 'Введение в PHP 8',
                'slug' => 'introduction-to-php-8',
                'content' => 'PHP 8 принес много новых возможностей и улучшений производительности. В этой статье мы рассмотрим основные нововведения.',
                'excerpt' => 'Что нового в PHP 8?',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 45,
                'published_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
            ],
            [
                'title' => 'Основы MySQL для начинающих',
                'slug' => 'mysql-basics-for-beginners',
                'content' => 'MySQL - это одна из самых популярных систем управления базами данных. Она используется в большинстве веб-приложений.',
                'excerpt' => 'Учимся работать с MySQL',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 78,
                'published_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-25 days')),
            ],
            [
                'title' => 'JavaScript для веб-разработчиков',
                'slug' => 'javascript-for-web-developers',
                'content' => 'JavaScript стал неотъемлемой частью современной веб-разработки. От фронтенда до бэкенда (Node.js).',
                'excerpt' => 'Всё о JavaScript',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 92,
                'published_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
            ],
            [
                'title' => 'Docker: контейнеризация приложений',
                'slug' => 'docker-containerization',
                'content' => 'Docker позволяет упаковать приложение со всеми зависимостями в контейнер и запустить его где угодно.',
                'excerpt' => 'Начинаем работать с Docker',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 56,
                'published_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
            ],
            [
                'title' => 'Современный веб-дизайн 2024',
                'slug' => 'modern-web-design-2024',
                'content' => 'Тренды в веб-дизайне постоянно меняются. Рассмотрим самые актуальные из них.',
                'excerpt' => 'Что в моде в дизайне',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 34,
                'published_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            [
                'title' => 'Оптимизация производительности веб-сайта',
                'slug' => 'web-performance-optimization',
                'content' => 'Быстрый веб-сайт - это лучший веб-сайт. Узнайте как оптимизировать скорость загрузки.',
                'excerpt' => 'Делаем сайт быстрее',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 123,
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'title' => 'API дизайн: лучшие практики',
                'slug' => 'api-design-best-practices',
                'content' => 'Хороший API дизайн - это ключ к успеху вашего приложения. Рассмотрим основные принципы.',
                'excerpt' => 'Учимся проектировать API',
                'author_id' => 1,
                'is_published' => 1,
                'views' => 67,
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'title' => 'Черновик: Будущее веб-разработки',
                'slug' => 'future-of-web-development',
                'content' => 'Эта статья в процессе написания. Скоро здесь будет интересный контент.',
                'excerpt' => 'Что ждет веб-разработку?',
                'author_id' => 1,
                'is_published' => 0,
                'views' => 0,
                'published_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert posts
        $count = $this->insertMany('posts', $posts);
        echo "✅ Created {$count} posts\n";

        // Attach categories to posts
        $this->attachCategoriesToPosts();
    }

    /**
     * Attach categories to posts
     */
    private function attachCategoriesToPosts(): void
    {
        $associations = [
            // Post 1 (Introduction to PHP 8) -> PHP
            [1, 1],
            // Post 2 (MySQL basics) -> Databases
            [2, 4],
            // Post 3 (JavaScript) -> JavaScript
            [3, 2],
            // Post 4 (Docker) -> DevOps
            [4, 5],
            // Post 5 (Web Design 2024) -> Web Design
            [5, 3],
            // Post 6 (Performance Optimization) -> PHP, Databases
            [6, 1],
            [6, 4],
            // Post 7 (API Design) -> PHP, JavaScript
            [7, 1],
            [7, 2],
        ];

        foreach ($associations as [$postId, $categoryId]) {
            try {
                $this->db->insert('post_category', [
                    'post_id' => $postId,
                    'category_id' => $categoryId,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                echo "❌ Error attaching category: " . $e->getMessage() . "\n";
            }
        }

        echo "✅ Attached categories to posts\n";
    }
}
