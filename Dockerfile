# Gunakan base image PHP dengan Apache
FROM php:8.1-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan modul rewrite Apache
RUN a2enmod rewrite

# Tentukan folder kerja
WORKDIR /var/www/html

# Salin seluruh kode program
COPY . .

# Berikan izin akses folder storage (khusus Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80
EXPOSE 80
