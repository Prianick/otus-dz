FROM php:8.0-fpm

#cat /etc/os-release \
#lsb_release -a \
#hostnamectl

#php -m

RUN apt-get update && apt-get install -y git zip unzip wget htop nmap iputils-ping

RUN pecl install xdebug \
    && docker-php-ext-enable  xdebug

RUN apt install -y lsb-release ca-certificates apt-transport-https software-properties-common gnupg2 \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list \
    && wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add - \
    && apt update \
    && docker-php-ext-install bcmath pcntl

RUN curl -sS https://getcomposer.org/download/latest-2.x/composer.phar > /usr/bin/composer && chmod a+x /usr/bin/composer

WORKDIR /var/app

