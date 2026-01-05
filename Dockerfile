# 1. GUNAKAN PHP 8.2 (Wajib untuk Laravel 12)
FROM php:8.2-apache

# 2. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl nodejs npm \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. FIX: Izinkan Git mengakses direktori kerja
RUN git config --global --add safe.directory /var/www/html

# 4. Setup Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Konfigurasi Apache (Fix 403 Forbidden)
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

# 6. Jalankan Composer Install (Sekarang akan berhasil karena PHP sudah 8.2)
RUN composer install --no-dev --optimize-autoloader

# 7. Build Assets NPM
RUN npm install && npm run build

# 8. Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
