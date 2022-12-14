FROM php:fpm

# Postgres
RUN apt-get update && apt-get install -y libpq-dev git && docker-php-ext-install pdo pdo_pgsql \
    && apt-get install -y zlib1g-dev libzip-dev unzip

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./docker/dev/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
