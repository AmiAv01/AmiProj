# Operational Runbooks

Collection of step-by-step procedures for common operational tasks.

## Table of Contents

1. [Deployment](#deployment)
2. [Rollback](#rollback)
3. [Incident Response](#incident-response)
4. [Database Operations](#database-operations)
5. [Queue Management](#queue-management)
6. [Performance Issues](#performance-issues)
7. [Backup & Restore](#backup--restore)

---

## Deployment

### Prerequisites

- [ ] All CI checks passing
- [ ] Code reviewed and approved
- [ ] Database backup available
- [ ] Monitoring dashboard open
- [ ] Team notified

### Step-by-Step

```bash
#!/bin/bash
set -e

VERSION=${1:-main}
ENVIRONMENT=${2:-staging}

echo "🚀 Deploying $VERSION to $ENVIRONMENT"

# 1. Pull latest code
git fetch origin
git checkout $VERSION

# 2. Load configuration
export $(cat .env.$ENVIRONMENT | xargs)

# 3. Create pre-deployment backup
echo "💾 Creating backup..."
mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME > \
    backups/db_$(date +%Y%m%d_%H%M%S).sql

# 4. Build and deploy
echo "🐳 Building Docker image..."
docker build -t ami:$VERSION .
docker tag ami:$VERSION registry.example.com/ami:$VERSION
docker push registry.example.com/ami:$VERSION

# 5. Update service
echo "📦 Deploying..."
docker-compose -f docker-compose.$ENVIRONMENT.yml pull
docker-compose -f docker-compose.$ENVIRONMENT.yml up -d

# 6. Run migrations
echo "🗄️ Running migrations..."
docker-compose exec -T app php artisan migrate --force

# 7. Clear cache
echo "♻️ Clearing cache..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan optimize

# 8. Health checks
echo "✅ Running health checks..."
MAX_RETRIES=10
for i in $(seq 1 $MAX_RETRIES); do
    if curl -sf http://localhost/api/health/ready > /dev/null; then
        echo "✓ Health check passed"
        break
    fi
    echo "Waiting for service... ($i/$MAX_RETRIES)"
    sleep 5
done

# 9. Smoke tests
echo "🧪 Running smoke tests..."
./scripts/smoke-tests.sh || {
    echo "❌ Smoke tests failed"
    ./runbook-rollback.sh
    exit 1
}

# 10. Notify
echo "✓ Deployment successful!"
echo "Version: $VERSION"
echo "Environment: $ENVIRONMENT"
echo "Time: $(date)"
# Send notification to Slack/email
```

**Rollback if needed:** See [Rollback](#rollback)

---

## Rollback

### When to Rollback

- [ ] Smoke tests failing
- [ ] Error rate > 5%
- [ ] Database migration errors
- [ ] Health checks failing
- [ ] Data corruption detected

### Rollback Procedure

```bash
#!/bin/bash
set -e

BACKUP_FILE=$1
ENVIRONMENT=${2:-production}

if [ -z "$BACKUP_FILE" ]; then
    echo "Usage: ./runbook-rollback.sh <backup_file> [environment]"
    echo "Available backups:"
    ls -lh backups/db_*.sql
    exit 1
fi

echo "🔄 STARTING ROLLBACK"
echo "Backup: $BACKUP_FILE"
echo "Environment: $ENVIRONMENT"
echo ""
echo "Press ENTER to continue or CTRL+C to abort..."
read

export $(cat .env.$ENVIRONMENT | xargs)

# 1. Stop new version gracefully
echo "⏹️ Stopping new application..."
docker-compose stop app --time=30
docker-compose stop queue-worker scheduler --time=10

# 2. Restore database
echo "🗄️ Restoring database..."
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < $BACKUP_FILE

# 3. Verify database integrity
echo "🔍 Verifying database..."
docker-compose exec -T mysql mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME \
    -e "SELECT COUNT(*) FROM migrations;"

# 4. Start previous version
echo "▶️ Starting previous version..."
docker-compose up -d app queue-worker scheduler

# 5. Verify health
echo "✅ Waiting for service recovery..."
sleep 10

if curl -sf http://localhost/api/health/ready > /dev/null; then
    echo "✓ Service recovered"
else
    echo "❌ Service still unhealthy - MANUAL INTERVENTION REQUIRED"
    exit 1
fi

# 6. Verify functionality
echo "🧪 Running smoke tests..."
./scripts/smoke-tests.sh || {
    echo "❌ Smoke tests still failing"
    exit 1
}

echo ""
echo "✓ ROLLBACK SUCCESSFUL"
echo "Time: $(date)"
# Notify team
```

---

## Incident Response

### 1. Incident Detection

Monitor these metrics:
```bash
# Error rate spike
curl http://monitoring.internal/metrics/errors?interval=1m

# Queue depth increasing
docker-compose exec app php artisan queue:status

# Database slow queries
docker-compose logs mysql | grep "Query_time"

# Memory/CPU usage
docker stats --no-stream
```

### 2. Immediate Actions

```bash
#!/bin/bash

echo "🚨 INCIDENT DETECTED"
echo "Time: $(date)"

# 1. Page on-call engineer
# Send alert to PagerDuty/Slack

# 2. Open war room
echo "Opening incident chat..."
# Link to Slack channel

# 3. Start timeline
echo "Incident started: $(date)" > incident.log

# 4. Assess severity
read -p "Severity (P1/P2/P3): " SEVERITY

case $SEVERITY in
    P1)
        echo "CRITICAL - Customer impact"
        ACTION="ROLLBACK_READY"
        ;;
    P2)
        echo "HIGH - Degraded service"
        ACTION="INVESTIGATE"
        ;;
    P3)
        echo "MEDIUM - Limited impact"
        ACTION="MONITOR"
        ;;
esac

echo "Severity: $SEVERITY - Action: $ACTION" >> incident.log
```

### 3. Investigation

```bash
# Check logs
docker-compose logs --tail=100 app > incident_logs.txt
docker-compose logs mysql >> incident_logs.txt
docker-compose logs queue-worker >> incident_logs.txt

# Check metrics
curl http://monitoring:3000/api/errors?hours=1 > incident_metrics.json

# Check database
docker-compose exec mysql mysql -e "SHOW PROCESSLIST;"

# Check queue
docker-compose exec app php artisan queue:status

# Check disk space
docker exec ami_mysql df -h
docker exec ami_app df -h
```

### 4. Remediation

**Option A: Fix in-place (for non-critical issues)**

```bash
# Clear queue if stuck
docker-compose exec app php artisan queue:flush

# Restart workers
docker-compose restart queue-worker scheduler

# Monitor recovery
watch -n 2 'docker-compose logs --tail=5 queue-worker'
```

**Option B: Rollback (for critical issues)**

```bash
# Find last good backup
ls -lht backups/db_*.sql | head -5

# Execute rollback (see Rollback section)
./runbook-rollback.sh backups/db_20250501_140000.sql production
```

### 5. Escalation

```bash
# If incident ongoing > 15 minutes
echo "⚠️ Escalating to senior engineer"

# If customer-facing > 30 minutes
echo "📧 Notifying customers"

# If > 1 hour with no ETA
echo "🎯 Activate war room"
./scripts/incident-war-room.sh
```

### 6. Post-Mortem

After resolution:

```bash
# Document incident
cat > incident_report.md << EOF
# Incident Report: $(date)

## Timeline
- Detection: ...
- Action taken: ...
- Resolution: ...
- Duration: ...

## Root Cause
...

## Action Items
- [ ] Fix root cause
- [ ] Add monitoring
- [ ] Update runbooks
- [ ] Team training

EOF

# Schedule post-mortem meeting
# Review what went wrong (blameless culture)
# Identify preventive measures
```

---

## Database Operations

### Backup

```bash
#!/bin/bash

ENVIRONMENT=${1:-production}
export $(cat .env.$ENVIRONMENT | xargs)

echo "💾 Creating database backup..."

BACKUP_FILE="backups/db_$(date +%Y%m%d_%H%M%S).sql.gz"
mkdir -p backups

mysqldump \
    -h $DB_HOST \
    -u $DB_USER \
    -p$DB_PASS \
    --single-transaction \
    --quick \
    $DB_NAME | gzip > $BACKUP_FILE

# Verify backup
if gzip -t $BACKUP_FILE; then
    echo "✓ Backup successful: $BACKUP_FILE"
    ls -lh $BACKUP_FILE
else
    echo "✗ Backup verification failed"
    exit 1
fi

# Retention: keep last 30 backups
ls -t backups/db_*.sql.gz | tail -n +31 | xargs rm -f

# Upload to cloud (optional)
gsutil cp $BACKUP_FILE gs://ami-backups/
```

**Schedule daily:**
```bash
# In crontab
0 2 * * * /app/scripts/backup.sh production
```

### Restore

```bash
#!/bin/bash

BACKUP_FILE=$1
ENVIRONMENT=${2:-production}

if [ ! -f "$BACKUP_FILE" ]; then
    echo "Backup file not found: $BACKUP_FILE"
    exit 1
fi

export $(cat .env.$ENVIRONMENT | xargs)

echo "🗄️ Restoring from: $BACKUP_FILE"
echo "⚠️ WARNING: This will overwrite $DB_NAME"
read -p "Type 'YES' to continue: " CONFIRM

if [ "$CONFIRM" != "YES" ]; then
    echo "Aborted"
    exit 1
fi

# Restore
if [[ $BACKUP_FILE == *.gz ]]; then
    gunzip < $BACKUP_FILE | mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME
else
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < $BACKUP_FILE
fi

echo "✓ Restore complete"
```

### Verify Integrity

```bash
#!/bin/bash

export $(cat .env.$ENVIRONMENT | xargs)

echo "🔍 Verifying database integrity..."

# Check table count
TABLES=$(mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME \
    -e "SELECT COUNT(*) FROM information_schema.TABLES" | tail -1)
echo "Tables: $TABLES"

# Check row counts
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME << EOF
SELECT TABLE_NAME, TABLE_ROWS 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = '$DB_NAME';
EOF

# Check for errors
docker-compose logs mysql | grep ERROR
```

---

## Queue Management

### Monitor Queue

```bash
# Check pending jobs
docker-compose exec app php artisan queue:status

# Show job details
docker-compose exec mysql mysql -e \
    "SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5;"

# Monitor in real-time
watch -n 2 'docker-compose exec app php artisan queue:status'
```

### Clear Queue

```bash
# Clear all jobs (CAUTION!)
docker-compose exec app php artisan queue:flush

# Clear specific queue
docker-compose exec app php artisan queue:flush high

# Clear failed jobs
docker-compose exec app php artisan queue:retry all
```

### Restart Workers

```bash
# Graceful restart (finish current jobs)
docker-compose exec app php artisan queue:restart

# Force restart
docker-compose restart queue-worker
```

---

## Performance Issues

### CPU/Memory High

```bash
# Check resource usage
docker stats --no-stream

# Top processes
docker top ami_app

# If PHP-FPM consuming high CPU
docker-compose logs app | grep "memory"

# Increase limits (if needed)
# In docker-compose.yml:
# services:
#   app:
#     deploy:
#       resources:
#         limits:
#           cpus: '2'
#           memory: 2G
```

### Database Slow

```bash
# Enable slow query log
docker-compose exec mysql mysql -e \
    "SET GLOBAL slow_query_log = 'ON';"

# Check slow queries
docker-compose exec mysql tail -f /var/log/mysql/slow-query.log

# Find problematic queries
docker-compose exec mysql mysql -e \
    "SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST WHERE TIME > 30;"

# Kill long-running query
docker-compose exec mysql mysql -e \
    "KILL <process_id>;"
```

### Cache Issues

```bash
# Check Redis memory
docker-compose exec redis redis-cli INFO memory

# Clear cache
docker-compose exec app php artisan cache:clear

# Monitor Redis keys
docker-compose exec redis redis-cli --scan
```

---

## Backup & Restore

### Full System Backup

```bash
#!/bin/bash

BACKUP_DIR="backups/full_$(date +%Y%m%d_%H%M%S)"
mkdir -p $BACKUP_DIR

echo "📦 Creating full system backup..."

# Database
mysqldump ... > $BACKUP_DIR/database.sql

# Application files
tar -czf $BACKUP_DIR/app.tar.gz \
    --exclude=storage/logs \
    --exclude=bootstrap/cache \
    --exclude=.git \
    .

# Storage (user uploads, etc)
tar -czf $BACKUP_DIR/storage.tar.gz storage/

# Configuration
cp .env.production $BACKUP_DIR/env.backup
cp config/*.php $BACKUP_DIR/

echo "✓ Backup complete: $BACKUP_DIR"
du -sh $BACKUP_DIR
```

### Test Restore (Monthly)

```bash
# Every month, on staging:

# 1. Create staging database from backup
# 2. Copy backup files
# 3. Verify application starts
# 4. Run smoke tests
# 5. Check data integrity

echo "✓ Monthly restore test: PASSED"
```

---

## Quick Reference

```bash
# Status
docker-compose ps                   # Service status
docker-compose logs -f app         # View logs
docker stats                       # Resource usage

# Restart
docker-compose restart app         # Restart app
docker-compose restart queue-worker # Restart queue
docker-compose restart scheduler   # Restart scheduler

# Maintenance
docker-compose exec app php artisan migrate   # Run migrations
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan optimize
docker-compose exec app php artisan queue:flush

# Database
docker-compose exec mysql mysql -e "SHOW DATABASES;"
docker-compose exec mysql mysqldump ...

# Shell access
docker-compose exec app sh         # App shell
docker-compose exec mysql bash     # MySQL shell
docker-compose exec redis redis-cli # Redis CLI
```

See also:
- [DEPLOYMENT.md](DEPLOYMENT.md)
- [SECURITY.md](SECURITY.md)
- [DOCKER_RUNTIME.md](DOCKER_RUNTIME.md)
