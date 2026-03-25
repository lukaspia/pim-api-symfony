FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    icu-dev \
    libpq-dev \
    zlib-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git

# PHP extensions
RUN docker-php-ext-install \
    intl \
    pdo_pgsql \
    bcmath \
    zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html