#!/bin/sh
set -eu

if [ -n "${APP_KEY_BASE64:-}" ]; then
    export APP_KEY="base64:${APP_KEY_BASE64}"
fi
: "${APP_KEY:?APP_KEY must be set in the service environment}"

PORT="${PORT:-8080}"
sed -i "s/Listen 8080/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:8080>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
elif [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
fi

php artisan migrate --force
php artisan storage:link --force

exec "$@"