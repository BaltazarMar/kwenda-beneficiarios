FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN chown -R www-data:www-data /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage

ENV APACHE_DOCUMENT_ROOT /var/www/html/público

RUN sed -i 's|/var/www/html|/var/www/html/público|g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

EXPOSE 80