#!/bin/bash
# Setup development environment

set -e

echo "🚀 Setting up development environment..."

# 1. Check prerequisites
echo "📋 Checking prerequisites..."

if ! command -v docker &> /dev/null; then
    echo "❌ Docker not installed"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose not installed"
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
docker-compose build --no-cache

# 4. Start services
echo "▶️ Starting services..."
docker-compose up -d

# 5. Wait for services
echo "⏳ Waiting for services to be ready..."
sleep 10

# 6. Generate app key
echo "🔑 Generating app key..."
docker-compose exec -T app php artisan key:generate

# 7. Run migrations
echo "🗄️ Running migrations..."
docker-compose exec -T app php artisan migrate

# 8. Create storage link
echo "🔗 Creating storage link..."
docker-compose exec -T app php artisan storage:link || true

# 9. Set permissions
echo "🔐 Setting permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache

# 10. Health check
echo "✅ Running health check..."
if curl -sf http://localhost/api/health/live > /dev/null; then
    echo "✓ Application is healthy"
else
    echo "⚠️ Health check failed, but services may still be starting"
fi

echo ""
echo "✅ Development environment ready!"
echo ""
echo "Next steps:"
echo "1. Open http://localhost in your browser"
echo "2. View logs: docker-compose logs -f app"
echo "3. Run tests: docker-compose exec app php artisan test"
echo ""
echo "For more info, see: INFRASTRUCTURE.md"
