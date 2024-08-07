FROM php:8.2-cli

WORKDIR /var/www/

# Install system dependencies
RUN apt-get update && apt-get install -y \
    apt-utils \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip unzip \
    npm \
    nodejs \
    git && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

# Copy the rest of the application files
COPY . .

# Generate autoload files
RUN composer dump-autoload

# Install npm packages and build
RUN npm install && npm run build

# Expose ports for both PHP and npm servers
EXPOSE 9000
EXPOSE 5173

# Set up command to run both services
CMD php artisan serve --host=127.0.0.1 --port=9000 & npm run dev --host 127.0.0.1 --port 5173
