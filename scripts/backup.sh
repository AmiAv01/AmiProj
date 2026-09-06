#!/bin/bash
# Backup database

set -euo pipefail
umask 077

ENVIRONMENT=${1:-production}
ENV_FILE=".env.$ENVIRONMENT"

if [ ! -f "$ENV_FILE" ]; then
    echo "✗ Missing environment file: $ENV_FILE"
    exit 1
fi

set -a
# shellcheck disable=SC1090
source "$ENV_FILE"
set +a

: "${DB_USERNAME:?DB_USERNAME is required}"
: "${DB_PASSWORD:?DB_PASSWORD is required}"
: "${DB_DATABASE:?DB_DATABASE is required}"

echo "💾 Creating database backup..."

BACKUP_FILE="backups/db_$(date +%Y%m%d_%H%M%S).sql.gz"
TEMP_BACKUP="$BACKUP_FILE.tmp"
mkdir -p backups
trap 'rm -f "$TEMP_BACKUP"' EXIT

if docker compose ps mysql > /dev/null 2>&1; then
    echo "Using Docker Compose..."
    docker compose exec -T -e MYSQL_PWD="$DB_PASSWORD" mysql mysqldump \
        -u "$DB_USERNAME" \
        --single-transaction \
        --quick \
        "$DB_DATABASE" | gzip > "$TEMP_BACKUP"
else
    echo "Using local MySQL..."
    MYSQL_PWD="$DB_PASSWORD" mysqldump \
        -h "${DB_HOST:-127.0.0.1}" \
        -u "$DB_USERNAME" \
        --single-transaction \
        --quick \
        "$DB_DATABASE" | gzip > "$TEMP_BACKUP"
fi

# Verify backup
if gzip -t "$TEMP_BACKUP" 2>/dev/null; then
    mv "$TEMP_BACKUP" "$BACKUP_FILE"
    echo "✓ Backup successful: $BACKUP_FILE"
    ls -lh "$BACKUP_FILE"
    
    # Retention: keep last 30 backups
    echo "Cleaning up old backups..."
    ls -t backups/db_*.sql.gz 2>/dev/null | tail -n +31 | xargs rm -f 2>/dev/null || true
else
    echo "✗ Backup verification failed"
    exit 1
fi
