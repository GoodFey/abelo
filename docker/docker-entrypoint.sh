#!/bin/sh

echo "🚀 Initializing Abelo application..."
echo ""

# Создание папки storage и logs
echo "📁 Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/cache
chmod -R 755 storage
chmod -R 777 storage/logs
chmod -R 777 storage/cache
echo "✅ Storage directories created with correct permissions"

# Создание папки для кэша картинок (должна быть в public/)
echo "📁 Creating public cache directory for images..."
mkdir -p public/cache/images
chmod -R 777 public/cache
echo "✅ Public cache directory created with correct permissions"
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

# Node.js dependencies and SCSS build
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node.js dependencies..."
    npm install
else
    echo "📦 Node.js dependencies already installed"
fi

echo "🎨 Building SCSS..."
npm run build

echo ""
echo "✅ Application initialization complete!"
echo ""

# Запуск PHP-FPM
exec php-fpm

