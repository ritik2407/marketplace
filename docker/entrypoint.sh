#!/bin/sh
set -e

# Ensure storage directories exist
mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/storage/app/public \
         /var/www/bootstrap/cache

# 1. Install Composer dependencies if missing
if [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "==> Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 2. Ensure .env exists
if [ ! -f "/var/www/.env" ]; then
    echo "==> Creating .env from .env.example..."
    cp /var/www/.env.example /var/www/.env
fi

# 3. Generate APP_KEY if empty
if ! grep -q "^APP_KEY=base64:" /var/www/.env 2>/dev/null; then
    echo "==> Generating Application Key..."
    php artisan key:generate --force
fi

# 4. Build frontend assets if missing
if [ ! -d "/var/www/public/build" ]; then
    echo "==> Installing Node dependencies and building assets..."
    npm install && npm run build
fi

# 5. Create storage symlink
php artisan storage:link --force || true

# 6. Run database migrations when DB connection is available
if [ -n "$DB_HOST" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || true
fi

# 7. Ensure correct permissions
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

echo "==> Laravel Marketplace application ready!"

# Execute container command (php-fpm)
exec "$@"
