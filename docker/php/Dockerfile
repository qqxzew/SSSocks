FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html