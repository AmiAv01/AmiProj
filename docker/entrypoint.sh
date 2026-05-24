#!/bin/bash

# Application initialization script
# Runs on first container start

set -e

WORKDIR="/app"
cd $WORKDIR

echo "🚀 Initializing Laravel application..."

# 1. Wait for dependencies
echo "⏳ Waiting for MySQL..."
until nc -z mysql 3306 2>/dev/null; do
    sleep 1
done
echo "✓ MySQL ready"

echo "⏳ Waiting for Redis..."
until nc -z redis 6379 2>/dev/null; do
    sleep 1
done
echo "✓ Redis ready"

# 2. Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# 3. Seed if needed
if [ ! -z "$SEED_DATABASE" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed
fi

# 4. Create queue table if using database driver
if [ "$QUEUE_CONNECTION" = "database" ]; then
    echo "📦 Creating jobs table..."
    php artisan queue:table --create || true
    php artisan queue:batches-table --create || true
    php artisan migrate --force
fi

# 5. Cache optimization
echo "♻️ Optimizing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Create storage links
echo "🔗 Creating storage links..."
php artisan storage:link || true

echo ""
echo "✅ Application initialization complete!"
echo ""
