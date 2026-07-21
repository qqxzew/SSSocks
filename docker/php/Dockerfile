FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis



COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN { \
        echo 'display_errors=Off'; \
        echo 'log_errors=On'; \
        echo 'error_log=/var/www/html/log/php_errors.log'; \
    } > /usr/local/etc/php/conf.d/error-handling.ini

WORKDIR /var/www/html