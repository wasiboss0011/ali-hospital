#!/bin/bash
set -e

# Default port configuration for platforms like Railway/Render/Cloud Run
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Generate application key if missing
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set. Generating application key..."
    php artisan key:generate --force
fi

# Ensure SQLite file exists if using sqlite
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" -a -z "${DB_URL}" -a -z "${DATABASE_URL}" -a -z "${MYSQL_URL}" ]; then
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Run migrations and seed default hospital data
echo "Running database migrations and seeders..."
php artisan migrate --force --seed || echo "Migration notice: continuing startup"

# Cache configuration, routes, and views for maximum production performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Ali's Hospital is starting on port ${PORT}..."
exec apache2-foreground
