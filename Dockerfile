FROM php:8.2-fpm

# 1) Dépendances système + extensions PHP
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

# 2) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3) Copier tout le code
COPY . .

# 4) Installer les dépendances PHP, chauffer le cache, et fixer les permissions
RUN composer install --optimize-autoloader --no-interaction --no-scripts \
  && php bin/console cache:warmup --no-ansi --env=dev \
  && chown -R www-data:www-data var

EXPOSE 9000
CMD ["php-fpm"]
