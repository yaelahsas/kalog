FROM php:7.4-fpm

WORKDIR /var/www/html/kalog

RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev zip unzip \
    libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ubah permission agar CI bisa tulis ke folder cache/logs
RUN chown -R www-data:www-data /app/application/cache /app/application/logs
