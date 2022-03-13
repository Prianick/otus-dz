FROM php:8.0.12-fpm

RUN apt-get update && apt-get install -y git

RUN pecl install xdebug \
    && docker-php-ext-enable  xdebug

RUN curl -sS https://getcomposer.org/download/latest-2.x/composer.phar > /usr/bin/composer && chmod a+x /usr/bin/composer

WORKDIR /var/app

