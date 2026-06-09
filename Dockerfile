FROM php:8.2-apache

# 1. Install dan aktifkan extension mysqli untuk koneksi.php
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 2. Copy semua file project ke dalam direktori Apache
COPY . /var/www/html/

# 3. Paksa Apache mengikuti PORT dinamis yang diberikan oleh Railway
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# 4. Jalankan Apache
EXPOSE 80