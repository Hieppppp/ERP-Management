#!/bin/sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is required. Add a value generated with: openssl rand -base64 32" >&2
    exit 1
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

php artisan storage:link --force >/dev/null 2>&1 || true
exec "$@"
