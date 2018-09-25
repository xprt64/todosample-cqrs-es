FROM php:7.1.2-apache

# Install dependencies
RUN apt-get update && apt-get install  -y \
        git \
        curl \
        pkg-config \
        libssl-dev

RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) zip

RUN pecl install mongodb
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/20-mongodb.ini

#RUN docker-php-ext-install -j$(nproc) sockets

RUN ["cp", "/etc/apache2/mods-available/rewrite.load", "/etc/apache2/mods-enabled/"]
COPY deploy/php.ini /usr/local/etc/php/conf.d/
COPY deploy/apache-site.conf  /etc/apache2/sites-enabled/000-default.conf

RUN apt-get -y install cron && mkdir -p /var/log/app

COPY ./ /var/www/

ADD deploy/cron /etc/cron.d/app
RUN chmod +x /etc/cron.d/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN cd /var/www && composer update --no-interaction

ENV PHP_TIMEZONE Europe/Bucharest

EXPOSE 80