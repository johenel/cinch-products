# Stage 1: Build vendor dependencies
FROM composer:2.7.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-interaction --prefer-dist --optimize-autoloader \
    --no-scripts

# Stage 2: Laravel PHP app
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl libonig-dev libxml2-dev libzip-dev \
    iputils-ping nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath xml zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

COPY . .
COPY --from=vendor /app/vendor /var/www/vendor

RUN cp .env.example .env || true
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
