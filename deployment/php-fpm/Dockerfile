FROM php:fpm-alpine3.7

RUN apk --no-cache --update add \
    curl \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    openssl-dev \
    libmcrypt-dev \
    libxml2-dev


RUN docker-php-ext-configure gd \
        --enable-gd-native-ttf \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd