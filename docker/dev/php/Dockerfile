FROM php:fpm

# Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    && docker-php-ext-install pdo \
    pdo_pgsql

#Redis
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN apt clean

#RabbitMQ
RUN apt-get install -y librabbitmq-dev libssl-dev && pecl install amqp && docker-php-ext-enable amqp

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./docker/dev/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
