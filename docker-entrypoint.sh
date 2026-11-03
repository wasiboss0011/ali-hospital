#!/bin/bash
set -e

# Default port configuration for platforms like Railway/Render/Cloud Run
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Ensure .env exists
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env 2>/dev/null || touch /var/www/html/.env
fi

# Generate application key if missing
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set. Generating application key..."
    php artisan key:generate --force || true
fi

# Ensure SQLite file exists if using sqlite
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" -a -z "${DB_URL}" -a -z "${DATABASE_URL}" -a -z "${MYSQL_URL}" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chown -R www-data:www-data /var/www/html/database
    chmod -R 775 /var/www/html/database
fi

# Ensure storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Run migrations and seed default hospital data
echo "Running database migrations and seeders..."
php artisan migrate --force --seed || echo "Migration notice: continuing startup"

# Cache configuration, routes, and views for maximum production performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Ali's Hospital is starting on port ${PORT}..."
exec apache2-foreground
