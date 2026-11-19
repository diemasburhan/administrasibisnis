#!/bin/bash
set -e

cd /var/www/html

# Fix storage & cache permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create storage symlink if not exists
if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

# Clear and cache config
php artisan config:cache || true
php artisan view:clear || true

# Start Apache
exec apache2-foreground
