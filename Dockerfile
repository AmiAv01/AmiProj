# Build stage
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    build-base \
    autoconf \
    linux-headers

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

# Production stage
FROM php:8.4-fpm-alpine

WORKDIR /app

# Install runtime dependencies
RUN apk add --no-cache \
    mysql-client \
    redis \
    supervisor \
    curl

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath \
    intl \
    opcache

# Copy PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/php.ini" && \
    echo "opcache.preload=/app/config/opcache.preload.php" >> "$PHP_INI_DIR/php.ini"

# Copy application from builder
COPY --from=builder /app /app

# Copy application startup
COPY --chown=www-data:www-data . .

# Set proper permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health/live || exit 1

# Start PHP-FPM by default
CMD ["php-fpm"]
