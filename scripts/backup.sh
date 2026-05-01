#!/bin/bash
# Backup database

set -e

ENVIRONMENT=${1:-production}
export $(cat .env.$ENVIRONMENT | xargs)

echo "💾 Creating database backup..."

BACKUP_FILE="backups/db_$(date +%Y%m%d_%H%M%S).sql.gz"
mkdir -p backups

if command -v docker-compose &> /dev/null; then
    echo "Using Docker Compose..."
    docker-compose exec -T mysql mysqldump \
        -u $DB_USERNAME \
        -p$DB_PASSWORD \
        --single-transaction \
        --quick \
        $DB_DATABASE | gzip > $BACKUP_FILE
else
    echo "Using local MySQL..."
    mysqldump \
        -h $DB_HOST \
        -u $DB_USERNAME \
        -p$DB_PASSWORD \
        --single-transaction \
        --quick \
        $DB_DATABASE | gzip > $BACKUP_FILE
fi

# Verify backup
if gzip -t $BACKUP_FILE 2>/dev/null; then
    echo "✓ Backup successful: $BACKUP_FILE"
    ls -lh $BACKUP_FILE
    
    # Retention: keep last 30 backups
    echo "Cleaning up old backups..."
    ls -t backups/db_*.sql.gz 2>/dev/null | tail -n +31 | xargs rm -f 2>/dev/null || true
else
    echo "✗ Backup verification failed"
    exit 1
fi
