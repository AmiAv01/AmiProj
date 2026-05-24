# Build stage
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Install system dependencies
RUN apk update && apk upgrade --no-cache && apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    build-base \
    autoconf \
    linux-headers \
    icu-dev \
    zlib-dev \
    libxml2-dev

# Install PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath \
    intl \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-ansi \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist