FROM php:8.2-fpm

# 1) System dependencies + PHP extensions
RUN apt-get update \
  && apt-get install -y \
    git \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libzip-dev \
    pkg-config \
    unzip \
  && docker-php-ext-install \
    intl \
    opcache \
    pdo_mysql \
    zip \
  && rm -rf /var/lib/apt/lists/*

COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/99-opcache.ini

# 2) Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3) Copy all source code
COPY . .

# 4) Install PHP dependencies, warm up the cache, and set permissions
RUN composer install --optimize-autoloader --no-interaction --no-dev \
  && php bin/console cache:warmup --no-ansi --env=prod \
  && chown -R www-data:www-data var

EXPOSE 9000
CMD ["php-fpm"]
