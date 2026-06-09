FROM php:8.2-cli

# 1. Install dan aktifkan extension mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 2. Set folder kerja di dalam container
COPY . /usr/src/vigenesia
WORKDIR /usr/src/vigenesia

# 3. Jalankan PHP built-in server langsung mengikuti PORT dari Railway
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT}"]