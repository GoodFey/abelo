# Docker для Abelo

Быстрый запуск проекта в Docker.

## Требования

- Docker Desktop (или Docker + Docker Compose)
- Linux/macOS/Windows (WSL2)

## Быстрый старт (3 шага)

### 1. Собрать образы и запустить

```bash
cd /var/www/abelo
./docker/docker.sh rebuild
```

Это займет 2-3 минуты в первый раз.

### 2. Открыть в браузере

```
http://localhost:8000
```

### 3. Выполнить миграции и seeders

```bash
# Открыть shell в контейнере
./docker/docker.sh shell

# Выполнить миграции
php migrate.php up

# Заполнить БД тестовыми данными
php seed.php

# Выход
exit
```

## Основные команды

```bash
# Запуск контейнеров
./docker/docker.sh up

# Остановка контейнеров
./docker/docker.sh down

# Пересборка и запуск
./docker/docker.sh rebuild

# Просмотр логов
./docker/docker.sh logs

# Shell в PHP контейнере
./docker/docker.sh shell

# Подключение к MySQL
./docker/docker.sh mysql

# Статус контейнеров
./docker/docker.sh status

# Справка
./docker/docker.sh help
```

## Структура контейнеров

| Контейнер | Порт | Назначение |
|-----------|------|-----------|
| `abelo-app` | 9000 | PHP-FPM |
| `abelo-nginx` | 8000 | Web сервер |
| `abelo-mysql` | 3306 | База данных |

## Доступ к БД

### Из контейнера
```bash
./docker/docker.sh mysql
```

### С хоста
```bash
mysql -h 127.0.0.1 -u abelo_user -p abelo
# пароль: password
```

### Программой (DBeaver, TablePlus и т.д.)
```
Host: 127.0.0.1
Port: 3306
User: abelo_user
Password: password
Database: abelo
```

## Файловая структура

```
abelo/
├── docker/                 # Docker конфигурация
│   ├── Dockerfile
│   ├── docker-compose.yml
│   ├── docker-entrypoint.sh
│   ├── docker.sh
│   ├── .dockerignore
│   ├── README.md
│   └── nginx/
├── app/                    # PHP приложение
├── public/                 # Web root
├── .env                    # Переменные окружения
├── .env.example           # Пример конфигурации
└── composer.json          # Зависимости PHP
```

## Переменные окружения

Файл `.env` (создается автоматически):

```env
APP_ENV=docker
APP_DEBUG=true
DB_HOST=mysql
DB_PORT=3306
DB_NAME=abelo
DB_USER=abelo_user
DB_PASSWORD=password
```

## Часто задаваемые вопросы

### Q: Как добавить PHP расширение?

A: Отредактируйте `docker/Dockerfile`:

```dockerfile
RUN docker-php-ext-install -j$(nproc) \
    mbstring \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    opcache \
    YOUR_EXTENSION    # Добавьте здесь
```

Затем пересоберите:
```bash
./docker/docker.sh rebuild
```

### Q: Как сохранить данные БД?

A: Данные автоматически сохраняются в volume `mysql_data`. Они будут восстановлены при перезагрузке контейнера.

### Q: Почему медленно загружается при первом запуске?

A: MySQL инициализирует БД. Подождите 10-15 секунд.

### Q: Как очистить данные БД?

A: 
```bash
./docker/docker.sh down  # Остановить контейнеры
docker volume rm abelo_mysql_data  # Удалить данные
./docker/docker.sh up    # Запустить снова (свежая БД)
```

### Q: Как работает код синхронизация?

A: Используется bind mount volume. Файлы доступны одновременно на хосте и в контейнере. Изменения видны сразу.

### Q: Как запустить тесты?

A:
```bash
./docker/docker.sh shell
./vendor/bin/phpunit
```

### Q: Как установить зависимости PHP?

A:
```bash
./docker/docker.sh shell
composer require package/name
```

## Решение проблем

### Ошибка: "Port 8000 already in use"

```bash
# Используйте другой порт в docker-compose.yml
# Найдите строку:
# - "8000:80"
# Измените на:
# - "8001:80"
```

### Ошибка: "Cannot connect to Docker daemon"

```bash
# Убедитесь, что Docker запущен
docker ps

# На Linux может потребоваться sudo
sudo docker ps
```

### MySQL не стартует

```bash
# Проверьте логи
./docker/docker.sh logs

# Убедитесь, что порт 3306 свободен
lsof -i :3306
```

## Дополнительная информация

- Подробнее: `docker/README.md`
- Docker Compose: https://docs.docker.com/compose/
- PHP Docker: https://hub.docker.com/_/php
- MySQL Docker: https://hub.docker.com/_/mysql
