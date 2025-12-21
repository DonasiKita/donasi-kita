# Gunakan base image PHP dengan Apache
FROM php:8.1-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# --- TAMBAHKAN BAGIAN INI UNTUK FIX 403 FORBIDDEN ---
# Mengarahkan Apache DocumentRoot ke folder public Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Aktifkan modul rewrite Apache
RUN a2enmod rewrite
# ----------------------------------------------------

# Tentukan folder kerja
WORKDIR /var/www/html

# Salin seluruh kode program
COPY . .

# --- PERBAIKI IZIN AKSES (Permissions) ---
# Memberikan kepemilikan seluruh folder ke user Apache (www-data)
RUN chown -R www-data:www-data /var/www/html

# Berikan izin khusus untuk folder storage dan cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# ------------------------------------------

# Buka port 80
EXPOSE 80

# Tambahkan ini di Dockerfile jika ingin membangun aset di dalam kontainer
RUN apt-get update && apt-get install -y nodejs npm
RUN npm install && npm run build
