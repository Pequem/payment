FROM php:8-apache

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update \
    && apt-get install -y curl zip unzip git supervisor sqlite3 libedit-dev libzip-dev libxml2-dev libcap2-bin libpng-dev libjpeg-dev libfreetype6-dev libjpeg62-turbo-dev \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd pdo pdo_mysql xml zip bcmath soap intl readline

RUN a2enmod rewrite
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN pecl channel-update https://pecl.php.net/channel.xml \
    && pecl install swoole

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /etc/php/8.0/cli/conf.d/99-sail.ini

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install

COPY docker/start.sh /start.sh
RUN chmod 700 /start.sh

EXPOSE 80
CMD ["/start.sh"]
