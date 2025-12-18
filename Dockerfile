FROM php:8.1-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    curl \
    npm \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build Vite assets (jika pakai Vite)
RUN npm install && npm run build

# Permission
RUN chmod -R 775 storage bootstrap/cache

# Expose port (Render pakai 10000)
EXPOSE 10000

# Start Laravel
CMD php -S 0.0.0.0:10000 -t public
