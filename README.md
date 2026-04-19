# Abelo - Простой блог на PHP

Минималистичный PHP-проект блога с собственным MVC фреймворком, MySQL базой данных и шаблонизатором Smarty.

## 🚀 Быстрый старт

### Запуск проекта

```bash
./docker/docker.sh build-up
```

Эта команда:
- Собирает Docker образы
- Запускает контейнеры (PHP-FPM, Nginx, MySQL)
- Создаёт необходимые структуры

После этого приложение будет доступно по адресу: **http://localhost:8000**

## 📋 Основные команды управления (docker.sh)

### Контейнеры
```bash
./docker/docker.sh up           # Запустить контейнеры
./docker/docker.sh down         # Остановить контейнеры
./docker/docker.sh build-up     # Пересобрать и запустить контейнеры
./docker/docker.sh restart      # Перезапустить контейнеры
```

### Доступ
```bash
./docker/docker.sh exec-app     # Подключиться к контейнеру приложения (shell)
./docker/docker.sh exec-mysql   # Подключиться к MySQL
```

### Логирование
```bash
./docker/docker.sh logs         # Просмотр логов всех контейнеров
./docker/docker.sh logs-app     # Логи приложения
./docker/docker.sh logs-mysql   # Логи MySQL
```

### База данных
```bash
./docker/docker.sh migrate                # Запустить миграции
./docker/docker.sh seed                   # Заполнить БД тестовыми данными
./docker/docker.sh migrate-seed           # Миграции + сидеры
./docker/docker.sh migrate-fresh-seed     # Пересоздать БД и заполнить её
```

### Зависимости
```bash
./docker/docker.sh composer [команда]     # Запустить Composer
./docker/docker.sh npm [команда]          # Запустить npm
```

### Другое
```bash
./docker/docker.sh permissions            # Установить права доступа
./docker/docker.sh clean                  # Удалить все контейнеры и тома (⚠️ опасно!)
```
## Дополнительную информацию о проекте, использовании нейросетей можно на сайте в категории "О проекте"

## 🏗️ Структура проекта

### Роутинг (Routing)

Маршруты определяются в **`routes/web.php`** с использованием простого API:

```php
$router->get('/posts', 'PostController@index');
$router->post('/posts', 'PostController@create');
$router->get('/posts/{slug}', 'PostController@show');
```

**Как это работает:**
1. Запрос приходит в **`public/index.php`**
2. **`Router`** анализирует URL и HTTP метод
3. Ищет соответствующий маршрут в регистре
4. Вызывает нужный контроллер и метод
5. Параметры пути (например `{slug}`) автоматически извлекаются

**Файлы:**
- `app/Core/Router.php` - основная логика роутинга
- `routes/web.php` - определение маршрутов

---

### База данных (Database)

**Архитектура:**
- **СУБД:** MySQL
- **Драйвер:** PDO (PHP Data Objects)
- **Паттерн:** Singleton + Active Record

**Как это работает:**
1. **`Database`** класс управляет одним подключением PDO (Singleton)
2. **`Model`** базовый класс для работы с таблицами
3. Модели (`Post`, `Category`) наследуют `Model` и имеют методы для CRUD операций
4. Подключение настраивается через переменные окружения:
   - `DB_HOST` - хост БД
   - `DB_USER` - пользователь
   - `DB_PASSWORD` - пароль
   - `DB_NAME` - имя БД

**Файлы:**
- `app/Core/Database.php` - управление подключением
- `app/Models/Model.php` - базовый класс модели
- `app/Models/Post.php` - модель поста
- `app/Models/Category.php` - модель категории
- `database/migrations/` - схема БД
- `database/seeders/` - тестовые данные

---

### Представления (Views) и Smarty

**Что это:**
- **Smarty** - мощный шаблонизатор с синтаксисом `{переменная}`
- **View** - обёртка над Smarty для удобной работы с шаблонами

**Как это работает:**
1. Контроллер готовит данные и передаёт их в View
2. View передаёт данные в Smarty
3. Smarty рендерит шаблон с переменными и логикой
4. Шаблоны находятся в `resources/templates/`

**Примеры синтаксиса:**
```smarty
{* Вывести переменную *}
{$title}

{* Цикл *}
{foreach $posts as $post}
    {$post.title}
{/foreach}

{* Условие *}
{if $isAdmin}
    <p>Admin panel</p>
{/if}

{* Фильтры (модификаторы) *}
{$image|thumb:300:200}   {* Уменьшить изображение *}
{$text|markdown}          {* Преобразовать markdown в HTML *}
```

**Конфигурация:**
- `config/smarty.php` - настройки Smarty
- `resources/templates/` - директория шаблонов
- `storage/cache/smarty_compile/` - скомпилированные шаблоны
- `storage/cache/smarty_cache/` - кэш шаблонов

**Файлы:**
- `app/Core/View.php` - класс для работы с представлениями
- `config/smarty.php` - конфигурация Smarty
- `resources/templates/` - шаблоны приложения

---

### Переменные окружения (.env)

Проект использует переменные окружения для конфигурации:

```env
APP_DEBUG=true              # Режим отладки
DB_HOST=mysql               # Хост базы данных
DB_PORT=3306                # Порт БД
DB_NAME=abelo               # Имя БД
DB_USER=abelo_user          # Пользователь БД
DB_PASSWORD=password        # Пароль БД
```

Переменные загружаются в контейнере Docker через `docker-compose.yml`

**Файлы:**
- `docker/docker-compose.yml` - настройки переменных для Docker

---

## 📁 Основные директории

```
app/
├── Controllers/    - контроллеры (обработка запросов)
├── Models/        - модели (работа с БД)
├── Core/          - ядро (Router, Database, View и т.д.)
└── Commands/      - консольные команды (миграции, сидеры)

resources/
├── templates/     - Smarty шаблоны
└── scss/         - стили

database/
├── migrations/    - миграции (структура БД)
└── seeders/      - сидеры (тестовые данные)

public/
├── index.php     - точка входа приложения
└── cache/        - кэшированные изображения

routes/
└── web.php       - определение маршрутов
```

---

## 🛠️ Техстек

- **PHP 8.1+**
- **MySQL 8.0**
- **Nginx**
- **Smarty** - шаблонизатор
- **PDO** - работа с БД
- **Docker** - контейнеризация

---