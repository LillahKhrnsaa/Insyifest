FROM php:8.3-fpm

# 1. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    nodejs \
    npm

# 2. Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd intl zip

# 4. Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set working directory
WORKDIR /var/www

# 6. Copy seluruh file project
COPY . .

# 7. Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# 8. Install Node dependencies & Build Assets (Vite/Inertia/React)
RUN npm install && npm run build

# 9. Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Force PHP-FPM listen to all interfaces
RUN sed -i 's/^listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

# 10. Expose port 9000
EXPOSE 9000
CMD ["php-fpm"]