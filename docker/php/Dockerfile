FROM php:7.2.30-fpm
LABEL maintainer="Stefan Gehrig <stefan.gehrig.hn@googlemail.com>"

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        libmagickwand-dev \
        tar \
        unzip \
        libcurl4-openssl-dev \
        libgmp-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        gmp \
        intl \
        opcache \
        pdo_mysql \
        zip \
    && pecl install \
        apcu \
        imagick \
        xdebug \
    && docker-php-ext-enable \
        apcu \
        imagick \
        xdebug \
    && mkdir /app

COPY php.ini $PHP_INI_DIR/conf.d/iishf.ini
COPY policy.xml /etc/ImageMagick-6/policy.xml

# install composer
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

VOLUME /app
WORKDIR /app

ENV COMPOSER_HOME /tmp/.composer
