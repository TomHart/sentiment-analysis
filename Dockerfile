FROM php:8.1.0RC6-zts-bullseye

RUN apt update && \
    apt install -y wget git zip unzip && \
    wget https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    pecl install xdebug &&  \
    docker-php-ext-enable xdebug