# Gunakan base image PHP dengan Apache (Sesuai kode Anda)
FROM php:8.1-apache

# 1. Install dependencies sistem & ekstensi PHP
# Menambahkan git, zip, dan unzip agar Composer bisa bekerja
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql

# 2. Install Composer secara resmi ke dalam container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- BAGIAN FIX 403 FORBIDDEN (Sesuai kode Anda) ---
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
# ----------------------------------------------------

# Tentukan folder kerja
WORKDIR /var/www/html

# Salin seluruh kode program
COPY . .

# 3. Jalankan Composer Install (Untuk memperbaiki error folder vendor)
RUN composer install --no-dev --optimize-autoloader

# 4. Jalankan Build Aset (Sesuai kode Anda)
RUN npm install && npm run build

# --- PERBAIKI IZIN AKSES (Sesuai kode Anda) ---
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# ------------------------------------------

# Buka port 80
EXPOSE 80
