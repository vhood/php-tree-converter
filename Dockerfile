FROM php:5.6-cli

RUN apt update && apt install -y git zip

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /app
