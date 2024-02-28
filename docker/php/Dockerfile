FROM php:8.3-fpm-alpine

#RUN apt-get update && apt-get install -y git zip unzip

# install configure file
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# settings
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

COPY --from=composer /usr/bin/composer /usr/bin/composer

# app sources
#COPY src /usr/share/nginx/html
