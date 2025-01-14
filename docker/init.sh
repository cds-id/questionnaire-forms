#!/bin/sh

# Wait for MySQL to be ready
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "Waiting for MySQL to be ready..."
    sleep 1
done

# Check if this is first time setup
if [ ! -f /var/www/.initialized ]; then
    echo "Running first time initialization..."

    # Run migrations
    php artisan migrate --force

    # Run seeders
    php artisan db:seed --force

    # Create initialization marker
    touch /var/www/.initialized

    echo "Initialization completed!"
fi

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
