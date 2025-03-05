# Étape 1: Builder l'application Laravel
FROM composer:latest AS builder
WORKDIR /app

# ✅ Correction : Installer les dépendances requises pour GD et MySQL
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip

# ✅ Configuration et installation de l'extension GD et PDO MySQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql

# Copier le projet Laravel et installer les dépendances
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Appliquer les permissions pour Laravel
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Étape 2: Préparer l'image de production avec PHP et Nginx
FROM php:8.2-fpm
WORKDIR /var/app/prod/sygpre

# ✅ Installer les dépendances système + extensions PHP manquantes
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql

# Copier les fichiers Laravel depuis le builder
COPY --from=builder /app ./

# Copier les fichiers de configuration pour Nginx et Supervisor
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Appliquer les permissions correctes
RUN chown -R www-data:www-data /var/app/prod/sygpre

# Exposer les ports pour Nginx et PHP-FPM
EXPOSE 80 9000

# Lancer Supervisor pour gérer PHP-FPM et Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]