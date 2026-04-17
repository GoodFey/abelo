<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{$description|default:'Abelo - Блог о веб-разработке'}">
    <title>{$title|default:'Abelo'} - Блог</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #3b82f6;
            --secondary: #1e293b;
            --accent: #f59e0b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-light: #64748b;
        }

        html, body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: var(--text);
            background-color: #ffffff;
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        a:hover {
            color: var(--accent);
        }

        header {
            background-color: var(--secondary);
            color: white;
            padding: 2rem 0;
            margin-bottom: 3rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        header nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        header nav a {
            color: white;
            transition: color 0.2s;
        }

        header nav a:hover {
            color: var(--accent);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        main {
            min-height: calc(100vh - 200px);
            margin-bottom: 3rem;
        }

        footer {
            background-color: var(--secondary);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .breadcrumb a {
            color: var(--primary);
        }

        h1, h2, h3, h4, h5, h6 {
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
        }

        h3 {
            font-size: 1.5rem;
        }

        p {
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .post-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: box-shadow 0.2s;
        }

        .post-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .post-card h3 {
            margin-bottom: 0.5rem;
        }

        .post-meta {
            font-size: 0.875rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .post-excerpt {
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .post-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .badge {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge:hover {
            background-color: var(--accent);
            color: white;
        }

        button, .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        button:hover, .btn:hover {
            background-color: var(--accent);
        }

        .sidebar {
            background-color: var(--light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .sidebar h3 {
            margin-bottom: 1rem;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
        }

        .sidebar ul li:last-child {
            border-bottom: none;
        }

        .sidebar a {
            color: var(--primary);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            text-align: center;
            padding: 1.5rem;
            background: var(--light);
            border-radius: 0.5rem;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
        }

        .stat-card .label {
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.25rem;
            font-size: 0.875rem;
            min-width: 2.5rem;
            text-align: center;
        }

        .pagination a {
            color: var(--primary);
            transition: all 0.2s;
        }

        .pagination a:hover {
            background-color: var(--light);
            border-color: var(--primary);
        }

        .pagination .active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            font-weight: bold;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination .ellipsis {
            padding: 0.5rem 0.25rem;
            border: none;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }

        .category-card {
            background: var(--light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            transition: transform 0.2s;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .category-card h3 {
            margin-bottom: 0.5rem;
        }

        .category-card p {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .content-body {
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .content-body code {
            background-color: var(--light);
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            font-family: 'Courier New', monospace;
        }

        .content-body pre {
            background-color: var(--light);
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            header nav {
                flex-direction: column;
                gap: 1rem;
            }

            header nav ul {
                flex-direction: column;
                gap: 1rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
        {$content}
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Abelo. Все права защищены.</p>
            <p>Блог о веб-разработке на PHP, JavaScript, DevOps</p>
        </div>
    </footer>
</body>
</html>
