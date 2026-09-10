#!/usr/bin/env bash

set -u

SCRIPT_DIR="$(CDPATH= cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)" || exit 1
DEFAULT_APP_PATH="$(dirname -- "$SCRIPT_DIR")"
APP_PATH="${AMI_APP_PATH:-$DEFAULT_APP_PATH}"
PHP_BIN="${AMI_PHP_BIN:-$(command -v php || true)}"

if [[ ! -f "$APP_PATH/artisan" ]]; then
    echo "Application entry point not found: $APP_PATH/artisan" >&2
    exit 1
fi

if [[ -z "$PHP_BIN" || ! -x "$PHP_BIN" ]]; then
    echo "PHP executable not found. Set AMI_PHP_BIN in the server environment." >&2
    exit 1
fi

cd -- "$APP_PATH" || exit 1

FAILED=0

for DBF_FILE in \
    FIRMS.DBF \
    ASS.DBF \
    OEMS_OUT.DBF \
    ALT_CZ.DBF \
    ROZ_CZ.DBF \
    DATA.DBF \
    stk.dbf
do
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Importing ${DBF_FILE}"

    if ! "$PHP_BIN" artisan dbf:sync --file="$DBF_FILE"; then
        echo "[$(date '+%Y-%m-%d %H:%M:%S')] FAILED: ${DBF_FILE}"
        FAILED=1
    fi
done

exit "$FAILED"
