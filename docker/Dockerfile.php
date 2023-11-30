FROM php:8.0.28-fpm

RUN apt-get update && apt-get install -y \
git \
unzip \
nano \
libfreetype6-dev \
libjpeg62-turbo-dev \
libicu-dev \
libmcrypt-dev \
libpng-dev \
libzip-dev \
&& pecl install mcrypt \
&& docker-php-ext-enable mcrypt \
&& docker-php-ext-install -j$(nproc) pdo_mysql \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl \
&& docker-php-ext-install zip \
&& pecl install xdebug \
&& docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-interaction --no-plugins --no-scripts --optimize-autoloader

CMD ["/usr/sbin/cron", "-f"]

CMD ["php-fpm"]
