FROM php:8.4-fpm-alpine

# Пакеты
RUN apk add --no-cache \
    curl \
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

# PHP расширения
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

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# EntryPoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Права
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]