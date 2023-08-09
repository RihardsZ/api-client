# Docker image used for testing in CI
FROM php:8.0-fpm-alpine

WORKDIR /usr/share/www/app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache --virtual .pipeline-deps \
    autoconf \
    gcc \
    g++ \
    make \
    pkgconf \
    bash \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    icu-dev \
    autoconf \
    libxslt-dev

RUN docker-php-ext-install gd soap zip bcmath exif intl opcache xsl

RUN pecl install pcov && docker-php-ext-enable pcov
