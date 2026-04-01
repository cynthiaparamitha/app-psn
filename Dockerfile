FROM php:8.2-fpm

# Set environment agar installer tidak meminta input interaktif
ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www

# 1. Install dependensi sistem yang wajib untuk kompilasi
RUN apt-get update && apt-get install -y \
    gnupg2 \
    curl \
    ca-certificates \
    apt-transport-https \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    gcc \
    g++ \
    make \
    autoconf \
    libc-dev \
    pkg-config

# 2. Tambahkan GPG Key & Repo Microsoft (Debian 12)
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64,arm64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list

# 3. Install ODBC Driver (Krusial untuk sqlsrv)
RUN apt-get update && ACCEPT_EULA=Y apt-get install -y \
    msodbcsql18 \
    mssql-tools18 \
    unixodbc-dev

# 4. Install Ekstensi PHP dengan penanganan error manual
# Terkadang PECL butuh update channel sebelum install
RUN pecl channel-update pecl.php.net \
    && pecl install sqlsrv-5.11.0 \
    && pecl install pdo_sqlsrv-5.11.0 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# 5. Install ekstensi standar Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Salin file composer dulu untuk memanfaatkan cache layer
COPY composer.json composer.lock ./

# 4. Jalankan Composer Install (tanpa script dulu agar tidak error jika class belum ada)
RUN composer install --no-scripts --no-autoloader --no-interaction

# 5. Salin seluruh kode aplikasi
COPY . .

# 6. Jalankan dump-autoload dan optimasi
RUN composer dump-autoload --optimize

# 7. Set Permission untuk Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Bersihkan cache untuk mengurangi ukuran image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ... (instalasi driver sebelumnya)

EXPOSE 80

# WAJIB host=0.0.0.0 agar bisa diakses dari luar container
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]