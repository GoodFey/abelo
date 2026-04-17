# Abelo Docker Setup

Минимальная конфигурация Docker для PHP проекта Abelo.

## Структура

```
docker/
├── Dockerfile              # PHP 8.2-FPM с необходимыми расширениями
├── docker-compose.yml      # Сервисы: PHP-FPM, MySQL, Nginx
├── docker-entrypoint.sh    # Инициализация приложения
├── docker.sh               # Скрипт управления контейнерами
├── nginx/
│   ├── nginx.conf          # Главная конфигурация Nginx
│   └── conf.d/
│       └── app.conf        # Конфиг приложения
└── mysql/
    └── init.sql/           # SQL скрипты инициализации (опционально)
```

## Сервисы

### app
- **Образ**: PHP 8.2-FPM (Alpine Linux)
- **Зависимости**: MySQLi, PDO MySQL, Zip, OPCache
- **Рабочая директория**: `/var/www/html`
- **Порт**: 9000 (FPM)

### mysql
- **Образ**: MySQL 8.0 (Alpine)
- **База данных**: `abelo`
- **Пользователь**: `abelo_user` / `password`
- **Порт**: 3306
- **Данные**: Сохраняются в volume `mysql_data`

### nginx
- **Образ**: Nginx Alpine
- **Порт**: 8000 (доступ: `http://localhost:8000`)
- **Документ-рут**: `/var/www/html/public`

## Установка

1. **Сборка образов**:
   ```bash
   ./docker/docker.sh build
   ```

2. **Запуск контейнеров**:
   ```bash
   ./docker/docker.sh up
   ```

3. **Проверить статус**:
   ```bash
   ./docker/docker.sh status
   ```

## Команды

### Основные команды

```bash
# Запустить контейнеры
./docker/docker.sh up

# Остановить контейнеры
./docker/docker.sh down

# Собрать и запустить
./docker/docker.sh rebuild

# Статус контейнеров
./docker/docker.sh status

# Логи приложения
./docker/docker.sh logs

# Открыть shell в PHP контейнере
./docker/docker.sh shell

# Подключиться к MySQL
./docker/docker.sh mysql
```

## Быстрый старт

```bash
# 1. Собрать образы
cd /var/www/abelo
./docker/docker.sh build

# 2. Запустить контейнеры
./docker/docker.sh up

# 3. Открыть в браузере
# http://localhost:8000

# 4. Выполнить миграции (в shell контейнера)
./docker/docker.sh shell
php migrate.php up

# 5. Запустить seeders
php seed.php
```

## Переменные окружения

Переменные передаются через файл `.env` в корневой директории проекта:

```env
APP_ENV=docker
APP_DEBUG=true
APP_NAME=Abelo

DB_HOST=mysql
DB_PORT=3306
DB_NAME=abelo
DB_USER=abelo_user
DB_PASSWORD=password
```

## Доступ к сервисам

| Сервис | Адрес | Порт |
|--------|-------|------|
| Приложение | `http://localhost:8000` | 8000 |
| MySQL | `localhost:3306` | 3306 |

### MySQL подключение

**От хоста:**
```bash
mysql -h 127.0.0.1 -u abelo_user -p abelo
# пароль: password
```

**Из контейнера:**
```bash
./docker/docker.sh mysql
```

## Разработка

### Редактирование кода

Код доступен через volume в контейнер. Изменения отражаются в реальном времени.

```bash
# Файлы видны в обоих местах:
# - /var/www/abelo (на хосте)
# - /var/www/html (в контейнере)
```

### Логи

```bash
# Логи приложения
./docker/docker.sh logs

# Логи в реальном времени
./docker/docker.sh logs -f
```

### Shell доступ

```bash
# Открыть shell в PHP контейнере
./docker/docker.sh shell

# Команды PHP
php -v
composer --version
```

## Проблемы и решения

### Контейнер не запускается

```bash
# Проверьте логи
./docker/docker.sh logs

# Пересоберите образы
./docker/docker.sh rebuild
```

### MySQL не подключается

```bash
# Проверьте статус MySQL
./docker/docker.sh status

# Подождите 10-15 секунд, если контейнер только что запущен
# MySQL нужно время на инициализацию
```

### Permission denied

```bash
# Исправьте права доступа
chmod +x docker/docker.sh
```

## Удаление

```bash
# Остановить контейнеры
./docker/docker.sh down

# Полная очистка (включая данные БД)
docker compose -f docker/docker-compose.yml down -v
```

## Дополнительная информация

- **PHP расширения**: MySQLi, PDO MySQL, Zip, OPCache
- **Образ Alpine**: Легкий, быстрый, минимальный размер
- **Volume** `mysql_data`: Сохраняет данные БД между запусками
- **Network** `abelo-network`: Внутренняя сеть контейнеров
