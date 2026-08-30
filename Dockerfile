FROM php:8.4-fpm-alpine AS php-base

WORKDIR /app

RUN apk add --no-cache \
        fcgi \
        icu-libs \
        libzip \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        linux-headers \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        pcntl \
        pdo_mysql \
    && apk del .build-deps


FROM php-base AS php-dependencies

RUN apk add --no-cache git unzip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-ansi \
    --no-progress \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist

COPY . .

RUN composer dump-autoload --no-dev --classmap-authoritative --no-scripts \
    && php artisan package:discover --ansi


FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
COPY --from=php-dependencies /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy
RUN npm run build


FROM php-base AS development

RUN apk add --no-cache git unzip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install \
    --no-interaction \
    --no-ansi \
    --no-progress \
    --prefer-dist

EXPOSE 9000

CMD ["php-fpm", "-F"]


FROM php-base AS production

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

COPY docker/php/production.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY --chown=www-data:www-data . .
COPY --from=php-dependencies --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD SCRIPT_NAME=/index.php \
        SCRIPT_FILENAME=/app/public/index.php \
        REQUEST_METHOD=GET \
        REQUEST_URI=/api/health/live \
        SERVER_NAME=localhost \
        SERVER_PORT=80 \
        SERVER_PROTOCOL=HTTP/1.1 \
        HTTP_HOST=localhost \
        cgi-fcgi -bind -connect 127.0.0.1:9000 2>/dev/null \
        | grep -q '"status":"ok"' \
        || exit 1

CMD ["php-fpm", "-F"]
