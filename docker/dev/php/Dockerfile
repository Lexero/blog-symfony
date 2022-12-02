FROM php:fpm

# Postgres
RUN apt-get update && apt-get install -y libpq-dev git && docker-php-ext-install pdo pdo_pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
