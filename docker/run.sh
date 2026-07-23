#!/bin/sh

# Cache configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations automatically if needed
# php artisan migrate --force

# Create storage link if not exists
php artisan storage:link --quiet || true

# Start Supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
