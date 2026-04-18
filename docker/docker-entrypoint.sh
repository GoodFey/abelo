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

git config --global --add safe.directory /var/www/html

# Composer
if [ ! -d "vendor" ]; then
    echo "📦 Installing PHP dependencies..."
    composer install --no-interaction --optimize-autoloader
else
    echo "📦 PHP dependencies already installed"
fi

echo ""
echo "✅ Application initialization complete!"
echo ""

# Запуск PHP-FPM
exec php-fpm

