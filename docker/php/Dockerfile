FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libicu-dev libonig-dev libxml2-dev \
    libzip-dev curl && \
    docker-php-ext-install intl pdo pdo_mysql zip opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Symfony project files
COPY ../../ /var/www/html

RUN chmod +x ./init.sh