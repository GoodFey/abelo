FROM php:8.4-fpm-alpine

# Системные пакеты + Node.js для сборщика
RUN apk add --no-cache \
    curl \
    wget \
    git \
    zip \
    unzip \
    mysql-client \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    linux-headers \
    nodejs \
    npm \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    $PHPIZE_DEPS

# Установка необходимых PHP расширений
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    mbstring \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    opcache

# Установить Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установить рабочую директорию
WORKDIR /var/www/html

# Копировать entrypoint скрипт
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Пользователь www-data
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
