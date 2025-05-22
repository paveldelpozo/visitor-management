FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Comando de arranque: instalar dependencias y lanzar servidor + migraciones
CMD composer install && php artisan migrate --seed && php artisan serve --host=0.0.0.0 --port=8000