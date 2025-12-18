#!/usr/bin/env bash
# exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
npm install
npm run build

# Generate application key if not set
php artisan key:generate --force

# Run database migrations
php artisan migrate --force

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
