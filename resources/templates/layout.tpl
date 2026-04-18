<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{$description|default:'Abelo - Блог о веб-разработке'}">
    <title>{$title|default:'Abelo'} - Блог</title>

    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="/">🚀 Abelo</a>
            </div>
            <ul>
                <li><a href="/">Главная</a></li>
                <li><a href="/posts">Статьи</a></li>
                <li><a href="/categories">Категории</a></li>
                <li><a href="/about">О блоге</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        {block name="content"}{/block}
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Abelo. Все права защищены.</p>
            <p>Блог о веб-разработке на PHP, JavaScript, DevOps</p>
        </div>
    </footer>
</body>
</html>
