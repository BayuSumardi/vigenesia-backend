FROM php:8.2-cli

# 1. Install dan aktifkan extension mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 2. Set folder kerja di dalam container
COPY . /usr/src/vigenesia
WORKDIR /usr/src/vigenesia

# 3. Beritahu Railway secara eksplisit bahwa kontainer ini membuka port 8080
EXPOSE 8080

# 4. Jalankan PHP server langsung di port 8080
CMD ["php", "-S", "0.0.0.0:8080"]