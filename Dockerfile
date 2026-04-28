FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    libzip-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mysqli zip mbstring exif pcntl bcmath gd \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

COPY package.json package-lock.json ./
RUN npm install

COPY . .

RUN npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

RUN php artisan criar:utilizadores --no-interaction || true

EXPOSE 80