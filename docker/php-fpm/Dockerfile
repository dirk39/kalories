FROM php:7.0-fpm

RUN apt-get update \
   && apt-get install -y libmagickwand-dev --no-install-recommends \
   && pecl install imagick \
   && docker-php-ext-enable imagick

RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-install -j$(nproc) \
        intl \
        pdo_mysql \
        mbstring \
        iconv \
        mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
    && pecl install zip \
    && docker-php-ext-enable zip

RUN docker-php-ext-install pcntl
RUN docker-php-ext-install shmop \
    sysvmsg \
    sysvsem \
    sysvshm

WORKDIR /var/www/motork

CMD ["php-fpm"]

EXPOSE 9000
