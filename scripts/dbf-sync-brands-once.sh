#!/usr/bin/env bash

set -u

SCRIPT_DIR="$(CDPATH= cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)" || exit 1
DEFAULT_APP_PATH="$(dirname -- "$SCRIPT_DIR")"
APP_PATH="${AMI_APP_PATH:-$DEFAULT_APP_PATH}"

if [[ -n "${AMI_PHP_BIN:-}" ]]; then
    PHP_BIN="$AMI_PHP_BIN"
elif [[ -x /opt/php83/bin/php ]]; then
    PHP_BIN=/opt/php83/bin/php
else
    PHP_BIN="$(command -v php || true)"
fi

if [[ ! -f "$APP_PATH/artisan" ]]; then
    echo "Application entry point not found: $APP_PATH/artisan" >&2
    exit 1
fi

if [[ -z "$PHP_BIN" || ! -x "$PHP_BIN" ]]; then
    echo "PHP executable not found. Set AMI_PHP_BIN in the server environment." >&2
    exit 1
fi

if ! "$PHP_BIN" -r 'exit(version_compare(PHP_VERSION, "8.3.8", ">=") ? 0 : 1);'; then
    echo "PHP 8.3.8 or newer is required. Selected executable: $PHP_BIN" >&2
    exit 1
fi

cd -- "$APP_PATH" || exit 1

echo "Using $PHP_BIN ($("$PHP_BIN" -r 'echo PHP_VERSION;'))"
exec "$PHP_BIN" artisan dbf:sync-brands "$@"
