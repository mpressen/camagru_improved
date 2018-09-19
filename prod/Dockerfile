FROM php:7.2.8-apache

ENV PROJECT_ROOT /var/www/html/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public/
ENV PROTOCOL https
ENV SERVER_NAME camagru.maximilien-pressense.fr

#SSL certificate
COPY certbot.list /etc/apt/sources.list.d/
RUN apt-get update
RUN apt-get install -y python-certbot-apache -t stretch-backports
RUN apt-get install -y python3-pip
RUN pip3 install certbot-dns-route53
RUN certbot -n --dns-route53 --installer apache --agree-tos -d camagru.maximilien-pressense.fr -m maximilien.pressense.pro@gmail.com

RUN apt-get install -y libpng-dev
RUN docker-php-ext-install gd

RUN docker-php-ext-install pdo_mysql

# you need to give apache user right to write recursively on public/images
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod headers && a2enmod rewrite

RUN apt-get install -y ssmtp
RUN echo "mailhub=mail" >> /etc/ssmtp/ssmtp.conf
RUN echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf
RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini
