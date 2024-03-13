FROM php:8.3-fpm-alpine

#RUN apt-get update && apt-get install -y git zip unzip

# see: https://github.com/mlocati/docker-php-extension-installer
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd xdebug pdo_mysql

# install configure file
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# settings
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

COPY --from=composer /usr/bin/composer /usr/bin/composer

# app sources
#COPY src /usr/share/nginx/html
