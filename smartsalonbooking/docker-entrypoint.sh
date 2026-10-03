#!/bin/sh
set -eu

: "${APP_KEY_BASE64:?APP_KEY_BASE64 must be set by Render}"
export APP_KEY="base64:${APP_KEY_BASE64}"

if [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
fi

php artisan migrate --force
php artisan storage:link --force

exec "$@"