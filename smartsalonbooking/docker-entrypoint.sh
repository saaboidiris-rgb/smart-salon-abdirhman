#!/bin/sh
set -eu

if [ -n "${APP_KEY_BASE64:-}" ]; then
    export APP_KEY="base64:${APP_KEY_BASE64}"
fi
: "${APP_KEY:?APP_KEY must be set in the service environment}"

if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_CONNECTION:-}" ]; then
    export DB_CONNECTION="pgsql"
fi

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    if [ -z "${DB_DATABASE:-}" ]; then
        export DB_DATABASE="database/database.sqlite"
    fi

    case "${DB_DATABASE}" in
        [A-Za-z]:* ) export DB_DATABASE="database/database.sqlite" ;;
    esac
fi

if [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
elif [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
fi

php artisan migrate --force
php artisan storage:link --force

exec "$@"