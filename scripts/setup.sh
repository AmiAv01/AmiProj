#!/bin/bash
# Setup development environment

set -euo pipefail

echo "🚀 Setting up development environment..."

export LOCAL_UID="$(id -u)"
export LOCAL_GID="$(id -g)"

# 1. Check prerequisites
echo "📋 Checking prerequisites..."

if ! command -v docker &> /dev/null; then
    echo "❌ Docker not installed"
    exit 1
fi

if ! docker compose version > /dev/null 2>&1; then
    echo "❌ Docker Compose plugin not installed"
    exit 1
fi

echo "✓ Docker & Docker Compose found"

# 2. Setup environment file
echo "⚙️ Setting up environment..."

if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✓ Created .env"
else
    echo "✓ .env already exists"
fi

# 3. Build Docker images
echo "🐳 Building Docker images..."
docker compose build --no-cache

# 4. Start services
echo "▶️ Starting services..."
docker compose up -d

# 5. Generate app key
if grep -q '^APP_KEY=base64:' .env; then
    echo "🔑 Application key already exists"
else
    echo "🔑 Generating app key..."
    docker compose exec -T app php artisan key:generate
fi

# 6. Run migrations
echo "🗄️ Running migrations..."
docker compose exec -T app php artisan migrate

# 7. Create storage link
echo "🔗 Creating storage link..."
docker compose exec -T app php artisan storage:link || true

# 8. Install and build frontend assets
echo "📦 Installing frontend dependencies..."
docker compose run --rm frontend npm ci
docker compose run --rm frontend npm run build

# 9. Set permissions
echo "🔐 Setting permissions..."
docker compose exec -T app chmod -R ug+rwX storage bootstrap/cache

# 10. Health check
echo "✅ Running health check..."
LOCAL_PORT="$(docker compose port nginx 80 | sed 's/.*://')"
if curl -sf "http://127.0.0.1:$LOCAL_PORT/api/health/live" > /dev/null; then
    echo "✓ Application is healthy"
else
    echo "⚠️ Health check failed, but services may still be starting"
fi

echo ""
echo "✅ Development environment ready!"
echo ""
echo "Next steps:"
echo "1. Open http://localhost in your browser"
echo "2. View logs: docker compose logs -f app nginx queue-worker"
echo "3. Run tests: docker compose exec app php artisan test"
echo "4. Run Vite dev server: task vite"
echo ""
echo "For more info, see: INFRASTRUCTURE.md"
