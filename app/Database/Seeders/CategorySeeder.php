<?php

declare(strict_types=1);

namespace App\Database\Seeders;

/**
 * CategorySeeder
 * Seeds the categories table with sample data
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Running CategorySeeder...\n";

        // Clear existing data
        $this->truncate('categories');

        // Sample categories data
        $categories = [
            [
                'name' => 'PHP',
                'slug' => 'php',
                'description' => 'Статьи о PHP и веб-разработке',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'Уроки и советы по JavaScript',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Веб-дизайн',
                'slug' => 'web-design',
                'description' => 'Тренды и техники веб-дизайна',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Базы данных',
                'slug' => 'databases',
                'description' => 'MySQL, PostgreSQL и другие БД',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'DevOps',
                'slug' => 'devops',
                'description' => 'Docker, Kubernetes, CI/CD',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert categories
        $count = $this->insertMany('categories', $categories);
        echo "✅ Created {$count} categories\n";
    }
}
