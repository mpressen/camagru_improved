FROM php:7.2.8-apache

ENV PROJECT_ROOT /var/www/html/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public/

COPY ./app/ PROJECT_ROOT

# install gd library dependancy
RUN apt-get update && apt-get install -y libpng-dev

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod headers && a2enmod rewrite

RUN docker-php-ext-install gd pdo_mysql

# use private local smtp server
RUN apt-get install -y ssmtp
RUN echo "mailhub=mail" >> /etc/ssmtp/ssmtp.conf
RUN echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf
RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini