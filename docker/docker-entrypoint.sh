#!/bin/sh

echo "🚀 Initializing Abelo application..."
echo ""

# Проверка .env
if [ ! -f ".env" ]; then
    echo "⚠️  .env file not found, creating from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
    else
        cat > .env << 'EOF'
APP_ENV=docker
APP_DEBUG=true
APP_NAME=Abelo

DB_HOST=mysql
DB_PORT=3306
DB_NAME=abelo
DB_USER=abelo_user
DB_PASSWORD=password
EOF
    fi
    echo "✅ .env file created"
else
    echo "✅ .env file exists"
fi
echo ""

# Composer
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install --no-interaction --optimize-autoloader
else
    echo "📦 PHP dependencies already installed"
fi

# Permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage 2>/dev/null || true
chmod -R 775 /var/www/html/storage 2>/dev/null || true

echo ""
echo "✅ Application initialization complete!"
echo ""

# Запуск PHP-FPM
exec php-fpm

# Storage link (без спама ошибок)
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

echo ""
echo "✨ Initialization complete!"
echo ""

# 👇 КЛЮЧЕВОЕ: режим контейнера
if [ "$CONTAINER_ROLE" = "queue" ]; then
    echo "🎯 Starting queue worker..."
    exec php artisan queue:work rabbitmq --queue=balance,image --sleep=3 --tries=3 --timeout=90 -vvv
else
    echo "🌐 Starting PHP-FPM..."
    exec php-fpm
fi
