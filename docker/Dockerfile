FROM php:8.4-fpm-alpine

# Минимально необходимые системные пакеты
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
    $PHPIZE_DEPS

# Установка необходимых PHP расширений
RUN docker-php-ext-install -j$(nproc) \
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
