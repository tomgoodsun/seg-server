FROM php:8.3-fpm-alpine

# install configure file
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# settings
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

# app sources
#COPY src /usr/share/nginx/html
