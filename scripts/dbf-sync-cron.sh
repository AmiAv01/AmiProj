#!/usr/bin/env bash

set -u

cd /var/www/i/d/www/a || exit 1

PHP_BIN="/opt/php83/bin/php"
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
