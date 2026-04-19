<?php

declare(strict_types=1);

namespace Database\Seeders;

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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
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
                'image_path' => $this->getRandomImage(),
                'is_published' => 0,
                'views' => 0,
                'published_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'SOLID принципы простым языком',
                'slug' => 'solid-principles',
                'content' => 'SOLID — это не страшная аббревиатура, а набор простых правил, которые делают код поддерживаемым. Если коротко: каждый класс должен делать одну вещь, зависимости должны быть гибкими, а код — расширяемым без переписывания. В реальных проектах это спасает от ситуации, когда любое изменение ломает половину системы.',
                'excerpt' => 'Пишем код, который не стыдно поддерживать',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 77,
                'published_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
            ],
            [
                'title' => 'Что такое REST API на практике',
                'slug' => 'rest-api-practice',
                'content' => 'REST — это не просто набор правил, а подход к проектированию API. Правильные URL, HTTP методы и статусы позволяют сделать API понятным без документации. Если ты видишь /users/1 — ты уже примерно понимаешь, что произойдет.',
                'excerpt' => 'REST без лишней теории',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 83,
                'published_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
            ],
            [
                'title' => 'Почему важно разделять слои в приложении',
                'slug' => 'separation-of-concerns',
                'content' => 'Когда логика, база данных и отображение смешаны — проект быстро превращается в хаос. Разделение на слои (Controller, Model, View) позволяет изолировать изменения и упрощает поддержку. Это особенно заметно, когда проект начинает расти.',
                'excerpt' => 'Как не превратить проект в спагетти',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 69,
                'published_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
            ],
            [
                'title' => 'Пагинация: как правильно реализовать',
                'slug' => 'pagination-best-practice',
                'content' => 'Пагинация — это не просто LIMIT и OFFSET. Важно учитывать производительность, UX и корректность работы на больших объемах данных. Иногда лучше использовать курсоры вместо offset, чтобы избежать проблем с производительностью.',
                'excerpt' => 'Делаем списки удобными',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 58,
                'published_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            ],
            [
                'title' => 'Работа с формами и валидация',
                'slug' => 'form-validation',
                'content' => 'Любые данные от пользователя — потенциальная проблема. Валидация на сервере обязательна, даже если есть проверка на клиенте. Это защищает от ошибок и атак.',
                'excerpt' => 'Не доверяй пользователю',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 74,
                'published_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'title' => 'Как работает HTTP',
                'slug' => 'how-http-works',
                'content' => 'HTTP — это основа веба. Запрос, ответ, заголовки, статусы — всё это важно понимать, чтобы писать корректные приложения. Без этого сложно нормально работать с API и браузером.',
                'excerpt' => 'База веба',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 81,
                'published_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
            [
                'title' => 'SQL-инъекции и как от них защититься',
                'slug' => 'sql-injection',
                'content' => 'SQL-инъекция — одна из самых распространенных уязвимостей. Решение простое: использовать prepared statements. Но на практике многие до сих пор делают конкатенацию строк.',
                'excerpt' => 'Безопасность 101',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 99,
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'title' => 'Логирование в приложении',
                'slug' => 'application-logging',
                'content' => 'Без логов ты слепой. Ошибки, действия пользователей, системные события — всё это нужно записывать. Даже простой файл логов уже сильно помогает в отладке.',
                'excerpt' => 'Видеть, что происходит',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 52,
                'published_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
            [
                'title' => 'Почему важно писать читаемый код',
                'slug' => 'clean-code',
                'content' => 'Код читается чаще, чем пишется. Понятные имена, простые функции и отсутствие лишней магии — это то, что отличает хороший код от плохого.',
                'excerpt' => 'Код для людей',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 88,
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'title' => 'Ошибки начинающих разработчиков',
                'slug' => 'junior-mistakes',
                'content' => 'Самые частые ошибки — это копирование кода без понимания, отсутствие структуры и игнорирование архитектуры. Это нормально, через это проходят все. Важно — быстро это осознать.',
                'excerpt' => 'Учимся на чужих ошибках',
                'image_path' => $this->getRandomImage(),
                'is_published' => 1,
                'views' => 120,
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'Кто я?',
                'slug' => 'who-am-i',
                'content' => '# Кто я?

Привет, меня зовут **Илья**.

## О себе

Я - **Backend-разработчик (PHP / Laravel)** с опытом **3+ лет** проектирования систем с нуля и глубокой оптимизации legacy-проектов.

Мой подход базируется на принципах **SOLID** и других паттернов проектирования, а главные цели:

- Создавать **масштабируемые решения**, которые экономят ресурсы бизнеса
- Писать код, который **стабильно работает под нагрузкой**
- Создавать **красивый и функциональный код**, на который приятно смотреть

## Мой пет-проект

Сейчас работаю над проектом - **сервис дневник для трейдинга**

**Стек технологий:**
- Backend: Laravel 12, PHP 8.4
- Frontend: VueJS 3.5, Inertia
- Database: MySQL, Redis
- Message Queue: RabbitMQ
- Окружение: Docker

Вы можете посмотреть проект здесь: [http://85.198.69.79:8000/](http://85.198.69.79:8000/)

## Контакты

Свяжитесь со мной в Telegram: [@evs_ilya](https://t.me/evs_ilya)',
                'excerpt' => 'Знакомьтесь - Илья, Backend-разработчик с опытом 3+',
                'image_path' => 'images/doge1.png',
                'is_published' => 1,
                'views' => 45,
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Как я использую нейросети в разработке',
                'slug' => 'how-i-use-ai',
                'content' => '# Как я использую нейросети в разработке

## Зачем я использую нейросети

Я использую нейросети в разработке не потому, что "лень писать код", а потому что это **реально ускоряет рутину**.

Всё, что повторяется — контроллеры, модели, базовая логика — можно скинуть на LLM и не тратить на это время.

### Но ключевой момент

**Нейросеть не думает за тебя.** Она не принимает архитектурные решения и не понимает контекст проекта так, как это делает разработчик.

Поэтому я использую её как **инструмент** — что-то вроде **очень быстрого джуна**, который пишет код по чётким инструкциям.

> Чем точнее задача и жестче рамки, тем лучше результат

В итоге я трачу:
- ✅ **меньше времени** на механическую работу
- ✅ **больше времени** на то, что действительно важно: архитектуру, логику и качество кода

## Как я использую агентов

С нейросетями у меня всё довольно просто: я не пытаюсь заставить их "сделать всё за меня".

**Вместо этого я:**

1. Разбиваю задачу на **маленькие куски**
2. Даю их по одному
3. Всегда задаю **контекст**:
   - Как устроен проект
   - Какие есть ограничения
   - Что вообще можно делать, а что нет

Без этого нейросеть начинает фантазировать и придумывать своё.

Дальше я просто двигаюсь **шаг за шагом**:
- Сделал кусок
- Проверил
- Если что-то не так — поправил и пошёл дальше
- Без попыток сразу сделать идеально или исправить всё разом

**Такой подход работает гораздо стабильнее:**
- меньше сюрпризов
- меньше хаоса
- код получается намного аккуратнее

### Работа с контекстом

У любой нейросети есть ограничение на **"контекстное окно"** — сколько информации она может держать в голове одновременно.

Поэтому я не пытаюсь запихнуть туда весь проект целиком, а даю только нужные файлы и правила под конкретную задачу.

По сути, это и есть **контекст-инжиниринг**: ты сам решаешь, какую информацию дать модели, чтобы она выдала максимально точный результат, а не что-то "в среднем по больнице".

## Ошибки не пойдут в прод

Отдельно я уделяю внимание **проверке кода**: ни одна фича не считается готовой, пока я её не прогнал отдельно.

Я беру конкретный кусок (например, страницу категории):
- Проверяю логику
- Граничные случаи
- Базовые ошибки
- Только потом двигаюсь дальше

Плюс я использую нейросеть как **дополнительный слой ревью** — она хорошо ловит очевидные косяки, которые можно пропустить.

**За счёт такого пошагового подхода:**
- Баги не накапливаются, а отлавливаются сразу
- В прод не улетает "сюрприз"
- Каждая часть уже была проверена изолированно
- Не "надеюсь, всё вместе как-нибудь заработает"

> И естественно не забываем про тесты',
                'excerpt' => 'Нейросети как инструмент, а не замена разработчику',
                'image_path' => 'images/doge1.png',
                'is_published' => 1,
                'views' => 38,
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Как я построил план создания проекта',
                'slug' => 'how-i-planned-project',
                'content' => 'Как я построил план создания проекта. Поскольку я хорошо разбираюсь в Laravel я решил сделать ларавель-подобный мини фреймворк для решения этой задачи.

В проекте реализован MVC, миграции, сидеры, модели, контроллеры, роутинг web, подобие artisan команд, окружение env (config), хэлпер (dd - для отладки).

По ходу разработки я придерживался SOLID и YAGNI (kiss и dry - тоже). Но в сервисы разносить тут было нечего поэтому вся логика в контроллерах.

Окружение реализовано через Docker.

## План проекта

1. **Структура проекта**
   - /app (Controllers, Models, Services, Core)
   - /config (app.php, database.php)
   - /database (migrations, seeders)
   - /public (index.php)
   - /resources (views, scss)
   - /routes (web.php)
   - /storage (logs, cache)
   - /vendor, composer.json

2. **Ядро (Core)**
   - Router (GET/POST, параметры, dispatch)
   - Controller (логика + передача данных в View)
   - Model (PDO, CRUD)
   - View (Smarty)
   - Database (Singleton PDO)

3. **Composer**
   - установка Smarty
   - настройка PSR-4 автозагрузки

4. **Миграции**
   - таблицы: categories, posts, post_category
   - свои классы с up/down
   - запуск через migrate.php

5. **База данных**
   - categories (id, name, description)
   - posts (id, title, description, content, image, views, created_at)
   - post_category (post_id, category_id)

6. **Сидинг**
   - CategorySeeder
   - PostSeeder
   - запуск через seed.php

7. **Роутинг**
   - /
   - /category/{id}
   - /post/{id}

8. **Страницы**
   - Главная: категории + 3 поста
   - Категория: список, сортировка, пагинация
   - Пост: контент + просмотры + похожие статьи

9. **View (Smarty)**
   - layout
   - home.tpl
   - category.tpl
   - post.tpl

10. **Стили**
    - SCSS (через npm или Vite)

11. **Docker**
    - php-fpm
    - nginx
    - mysql

12. **Поток запроса**
    - index.php → Router → Controller → Model → View

13. **Bootstrap**
    - подключение autoload
    - загрузка роутов
    - dispatch запроса',
                'excerpt' => 'Как спланировать и реализовать Laravel-подобный фреймворк',
                'image_path' => 'images/doge1.png',
                'is_published' => 1,
                'views' => 52,
                'published_at' => date('Y-m-d H:i:s'),
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
            // Post 8 (Future of Web Development - Draft) - skip
            // Post 9 (SOLID Principles) -> PHP
            [9, 1],
            // Post 10 (REST API) -> PHP, JavaScript
            [10, 1],
            [10, 2],
            // Post 11 (Separation of Concerns) -> PHP
            [11, 1],
            // Post 12 (Pagination) -> PHP, Databases
            [12, 1],
            [12, 4],
            // Post 13 (Form Validation) -> PHP, JavaScript
            [13, 1],
            [13, 2],
            // Post 14 (HTTP) -> JavaScript, PHP
            [14, 2],
            [14, 1],
            // Post 15 (SQL Injection) -> Databases, PHP
            [15, 4],
            [15, 1],
            // Post 16 (Logging) -> PHP, DevOps
            [16, 1],
            [16, 5],
            // Post 17 (Clean Code) -> PHP
            [17, 1],
            // Post 18 (Junior Mistakes) -> PHP, JavaScript
            [18, 1],
            [18, 2],
            // Post 19 (Who am I) -> About Project
            [19, 6],
            // Post 20 (How I use AI) -> About Project
            [20, 6],
            // Post 21 (How I planned the project) -> About Project
            [21, 6],
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

    /**
     * Get a random image path or null
     * Randomly assigns images to posts, some posts stay without images (null)
     */
    private function getRandomImage(): ?string
    {
        // Available images in public/images/
        $images = [
            'images/1.jpg',
            'images/2.png',
            'images/3.jpg',
            'images/4.jpg',
            'images/5.jpg',
            'images/6.jpg',
            'images/7.jpg',
        ];

        return $images[array_rand($images)];
    }
}

