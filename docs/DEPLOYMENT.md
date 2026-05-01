# Deployment & Release Safety

## Overview

Safe deployment process for production releases ensuring:
- Zero-downtime deployments
- Automatic rollback on failure
- Database migration safety
- Cache warmup
- Smoke tests

## Deployment Stages

### Stage 1: Pre-Deployment Validation

**Checklist:**
- [ ] All CI checks passing (validate, quality, tests, security audit)
- [ ] Release version tagged in git
- [ ] Database backup taken
- [ ] Staging deployment tested
- [ ] Release notes prepared

### Stage 2: Backup & Snapshot

```bash
# Create database backup
mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME > backups/db_$(date +%Y%m%d_%H%M%S).sql

# Create filesystem snapshot (example for cloud storage)
gsutil -m cp -r gs://ami-backup/current gs://ami-backup/backup-$(date +%Y%m%d_%H%M%S)
```

### Stage 3: Deploy Artifact

```bash
# Pull latest image
docker pull registry.example.com/ami:v1.2.0

# Stop old container (graceful)
docker stop ami-app --time=30

# Start new container
docker run --name ami-app-new \
  --env-file .env.production \
  -v app_storage:/app/storage \
  registry.example.com/ami:v1.2.0
```

### Stage 4: Database Migrations

```bash
# Run migrations with --force flag (production safety)
php artisan migrate --force

# Verify migrations completed
php artisan migrate:status
```

**Migration Safety Rules:**

1. **Expand/Contract Pattern**
   - New fields added in separate migration
   - Old code removed in follow-up deployment

2. **Never Block Queries**
   ```php
   // ❌ BAD - locks table
   DB::table('users')->update(['active' => true]);
   
   // ✅ GOOD - batched
   DB::table('users')
       ->where('active', false)
       ->limit(1000)
       ->update(['active' => true]);
   ```

3. **No Large Transactions**
   ```php
   // ❌ BAD
   Schema::create('huge_table', function (Blueprint $table) {
       $table->string('data', 10000);
   });
   
   // ✅ GOOD
   Schema::create('huge_table', function (Blueprint $table) {
       $table->text('data');
   });
   ```

### Stage 5: Cache Warmup

```bash
# Clear stale cache
php artisan cache:clear

# Warm up critical paths
php artisan cache:forget query_results
php artisan cache:forget user_settings

# Queue cache population
php artisan queue:high-priority-cache-warmup
```

### Stage 6: Health Checks (Smoke Tests)

```bash
#!/bin/bash
set -e

HEALTH_URL="https://api.example.com/health/ready"
MAX_RETRIES=10
RETRY_DELAY=5

for i in $(seq 1 $MAX_RETRIES); do
    echo "Health check attempt $i/$MAX_RETRIES..."
    
    if curl -f $HEALTH_URL | grep -q '"status":"ready"'; then
        echo "✓ Application is healthy"
        
        # Additional smoke tests
        curl -f https://api.example.com/api/health/live
        echo "✓ Liveness check passed"
        
        exit 0
    fi
    
    if [ $i -lt $MAX_RETRIES ]; then
        echo "Health check failed, retrying in ${RETRY_DELAY}s..."
        sleep $RETRY_DELAY
    fi
done

echo "✗ Health checks failed after $MAX_RETRIES attempts"
exit 1
```

### Stage 7: Monitoring & Validation

```bash
# Check error rates
curl https://api.example.com/monitoring/errors?duration=5m

# Check queue depth
docker-compose exec app php artisan queue:status

# Check log tail
docker-compose logs --tail=50 app

# Monitor for 5 minutes
watch -n 5 'docker stats ami-app'
```

## Rollback Procedure

**If deployment fails:**

```bash
#!/bin/bash
set -e

echo "🔄 Starting rollback..."

# 1. Stop new container
docker stop ami-app-new

# 2. Restore database from backup
LATEST_BACKUP=$(ls -t backups/db_*.sql | head -1)
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < $LATEST_BACKUP
echo "✓ Database restored from $LATEST_BACKUP"

# 3. Start previous version
docker start ami-app
echo "✓ Previous version running"

# 4. Verify
sleep 10
if curl -f https://api.example.com/health/ready; then
    echo "✓ Application healthy, rollback successful"
else
    echo "✗ Rollback failed, manual intervention required"
    exit 1
fi

# 5. Notify team
echo "📧 Rollback completed, notifying team..."
# Send to Slack/PagerDuty/etc
```

## Deployment Script Example

```bash
#!/bin/bash
set -e

VERSION=$1
ENVIRONMENT=${2:-production}

if [ -z "$VERSION" ]; then
    echo "Usage: ./deploy.sh <version> [environment]"
    echo "Example: ./deploy.sh v1.2.0 production"
    exit 1
fi

echo "🚀 Deploying AMI $VERSION to $ENVIRONMENT"

# Load environment
export $(cat .env.$ENVIRONMENT | grep -v '^#' | xargs)

# Pre-deployment checks
echo "📋 Running pre-deployment checks..."
./scripts/pre-deploy-check.sh $ENVIRONMENT

# Backup
echo "💾 Creating backup..."
./scripts/backup.sh

# Deploy
echo "🐳 Deploying Docker image..."
docker pull registry.example.com/ami:$VERSION
docker-compose -f docker-compose.$ENVIRONMENT.yml up -d --no-build

# Migrations
echo "🗄️ Running migrations..."
docker-compose exec -T app php artisan migrate --force

# Cache warmup
echo "♻️ Warming up cache..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan optimize

# Health checks
echo "✅ Running health checks..."
./scripts/health-check.sh

# Notify
echo "✓ Deployment successful!"
echo "Version: $VERSION"
echo "Environment: $ENVIRONMENT"
echo "Timestamp: $(date)"
```

## Zero-Downtime Deployment with Load Balancer

```
┌─────────────────────────────────────────────┐
│         Load Balancer (HAProxy/nginx)       │
└──────────────────┬──────────────────────────┘
                   │
        ┌──────────┼──────────┐
        │          │          │
    ┌───▼──┐   ┌──▼───┐   ┌──▼───┐
    │ App1 │   │ App2 │   │ App3 │
    │ v1.1 │   │ v1.1 │   │ v1.2 │ (new)
    └──────┘   └──────┘   └──────┘

1. Deploy v1.2 to third instance
2. Health check v1.2
3. Add v1.2 to load balancer
4. Drain connections from v1.1 on App1
5. Update App1 to v1.2
6. Repeat for other instances
```

## Automated Deployment with GitHub Actions

```yaml
name: Deploy

on:
  push:
    tags:
      - 'v*.*.*'

jobs:
  deploy:
    runs-on: ubuntu-latest
    environment: production
    steps:
      - uses: actions/checkout@v4
      
      - name: Build Docker image
        run: |
          docker build -t registry.example.com/ami:${{ github.ref_name }} .
      
      - name: Push to registry
        uses: docker/login-action@v2
        with:
          registry: registry.example.com
          username: ${{ secrets.REGISTRY_USERNAME }}
          password: ${{ secrets.REGISTRY_PASSWORD }}
      
      - name: Deploy
        run: |
          ./scripts/deploy.sh ${{ github.ref_name }} production
      
      - name: Verify
        run: |
          ./scripts/health-check.sh
      
      - name: Notify Slack
        if: success()
        uses: 8398a7/action-slack@v3
        with:
          status: ${{ job.status }}
          text: 'Deployed AMI ${{ github.ref_name }} to production'
```

## Monitoring Post-Deployment

```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Error rate
curl https://api.example.com/monitoring/errors?interval=1m

# Database queries (if debug enabled)
SELECT * FROM query_log WHERE created_at > NOW() - INTERVAL 5 MINUTE;

# Queue status
php artisan queue:status

# Cache hit rate
redis-cli --stat
```

## Incident Response

**If issues detected during/after deployment:**

1. **Alert Team** → Page on-call engineer
2. **Assess** → Review logs, error rates, customer reports
3. **Decide** → Continue or rollback?
4. **Act** → Execute rollback procedure if needed
5. **Post-Mortem** → Review what went wrong

See [RUNBOOKS.md](RUNBOOKS.md) for detailed incident procedures.
