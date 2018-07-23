FROM php:7.2.8-apache
COPY ./app/ /var/www/html/
RUN docker-php-ext-install pdo pdo_mysql