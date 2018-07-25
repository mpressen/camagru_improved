FROM php:7.2.8-apache

ENV PROJECT_ROOT /var/www/html/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public/

COPY ./app/ PROJECT_ROOT

RUN docker-php-ext-install pdo pdo_mysql
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod headers && a2enmod rewrite