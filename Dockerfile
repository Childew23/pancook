FROM php:8.2-fpm

# 1) Dépendances système
RUN apt-get update && apt-get install -y \
    libicu-dev libpng-dev libonig-dev libzip-dev pkg-config unzip git \
  && docker-php-ext-install intl pdo_mysql zip opcache

# 2) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3) Copier TOUS les fichiers (y compris bin/console, config/, src/, etc.)
COPY . .

# 4) Installer les dépendances PHP DEPUIS le code copié
#    On peut même profiter du cache Docker si composer.json n’a pas changé :
RUN composer install --optimize-autoloader --no-interaction

# 5) Fixer les permissions
RUN chown -R www-data:www-data var

EXPOSE 9000
CMD ["php-fpm"]
