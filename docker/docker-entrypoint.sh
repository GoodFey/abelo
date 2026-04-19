#!/bin/sh

echo "🚀 Initializing Abelo application..."
echo ""

# Директории
echo "📁 Preparing directories..."
mkdir -p storage/logs storage/cache public/cache/images

chown -R www-data:www-data storage public/cache
chmod -R 775 storage public/cache

echo "✅ Directories ready"
echo ""

# .env
if [ ! -f ".env" ]; then
    echo "⚠️ .env not found, creating..."

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

    echo "✅ .env created"
else
    echo "✅ .env exists"
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

# Node
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Node dependencies..."
    npm install
else
    echo "📦 Node dependencies already installed"
fi

echo "🎨 Building assets..."
npm run build

echo ""

echo "✅ Application initialization complete!"
echo ""
echo "🌐 http://localhost:8000"
echo ""

# запуск PHP-FPM (ОБЯЗАТЕЛЬНО последняя строка)
exec php-fpm