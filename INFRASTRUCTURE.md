# Infrastructure & Development Guide

## Quick Start

### Prerequisites
- Docker & Docker Compose installed
- PHP 8.4+ for local development (optional, can use Docker)
- `git` and `task` (optional, for Task runner)

### Start Development Environment

```bash
# 1. Clone repository
git clone <repo_url>
cd AmiProj

# 2. Copy environment file
cp .env.example .env

# 3. Generate app key
docker-compose run --rm app php artisan key:generate

# 4. Start services
docker-compose up -d

# 5. Run migrations
docker-compose exec app php artisan migrate

# 6. Access application
# Web: http://localhost
# API: http://localhost/api/health/live
```

## Development

### Structure

```
.
├── app/                      # Application code
├── config/                   # Configuration files
├── database/                 # Migrations & seeders
├── docker/                   # Docker configuration
│   ├── nginx/              # Nginx config
│   ├── mysql/              # MySQL init scripts
│   ├── entrypoint.sh       # App initialization
│   └── Dockerfile          # Application image
├── docker-compose.yml        # Development stack
├── routes/                   # API & web routes
├── tests/                    # Test suite
├── docs/                     # Documentation
│   ├── CI_PIPELINE.md      # CI/CD pipeline
│   ├── DOCKER_RUNTIME.md   # Docker guide
│   ├── DEPLOYMENT.md       # Deployment procedures
│   ├── SECURITY.md         # Security checklist
│   ├── RUNBOOKS.md         # Operational procedures
│   └── GLITCHTIP.md        # Error tracking
├── Dockerfile               # Production image
└── .github/
    └── workflows/
        └── laravel.yml      # CI pipeline
```

### Common Commands

```bash
# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan tinker
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Run tests
docker-compose exec app php artisan test

# Code quality
docker-compose exec app php vendor/bin/pint --test
docker-compose exec app php vendor/bin/phpstan

# Composer
docker-compose exec app composer require <package>

# Database access
docker-compose exec mysql mysql -u root -ppassword ami_project
docker-compose exec redis redis-cli
```

### Database

```bash
# Fresh migration
docker-compose exec app php artisan migrate:fresh

# Rollback migration
docker-compose exec app php artisan migrate:rollback

# Create migration
docker-compose exec app php artisan make:migration create_table_name
```

### Queue & Scheduler

```bash
# Process single job
docker-compose exec app php artisan queue:work --once

# Check queue status
docker-compose exec app php artisan queue:status

# List scheduled tasks
docker-compose exec app php artisan schedule:list

# Run scheduler (testing)
docker-compose exec app php artisan schedule:work
```

## CI/CD Pipeline

### Stages

1. **Validate** - `composer validate --strict`
2. **Secret Scan** - Detect exposed secrets (Gitleaks)
3. **Quality** - Pint, PHPStan, Composer audit
4. **Tests** - Pest/PHPUnit suite

See [CI_PIPELINE.md](docs/CI_PIPELINE.md) for details.

### Running Locally

```bash
# Full CI pipeline
task test
task lint
task phpstan

# Individual checks
vendor/bin/pint --test      # Code formatting
vendor/bin/phpstan          # Static analysis
php artisan test            # Run tests
composer audit              # Security audit
```

## Docker Deployment

### Development

```bash
# Start all services
docker-compose up -d

# Stop
docker-compose down

# View status
docker-compose ps
docker-compose logs -f
```

### Production

```bash
# Build image
docker build -t ami:v1.0.0 .

# Push to registry
docker tag ami:v1.0.0 registry.example.com/ami:v1.0.0
docker push registry.example.com/ami:v1.0.0

# Deploy
docker pull registry.example.com/ami:v1.0.0
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose exec app php artisan migrate --force
```

See [DOCKER_RUNTIME.md](docs/DOCKER_RUNTIME.md) for full Docker guide.

## Deployment

### Safe Deployment

```bash
# 1. Tag release
git tag -a v1.2.0 -m "Release v1.2.0"
git push origin v1.2.0

# 2. GitHub Actions builds and tests automatically
# 3. Manual deployment to production
./scripts/deploy.sh v1.2.0 production

# 4. Health checks run automatically
# 5. Rollback available if issues detected
```

See [DEPLOYMENT.md](docs/DEPLOYMENT.md) for safe deployment procedures.

### Rollback

```bash
# If deployment fails
./scripts/rollback.sh backups/db_20250501_140000.sql production

# Automatic health checks verify
curl http://api.example.com/api/health/ready
```

See [RUNBOOKS.md](docs/RUNBOOKS.md) for operational procedures.

## Security

### Environment Variables

**Never commit secrets!**

```bash
# ✅ Correct
echo "DB_PASSWORD=secret" >> .env
echo ".env" >> .gitignore

# ❌ Wrong
git add .env
git commit -m "Add secrets"
```

### Use GitHub Secrets for CI/CD

```yaml
env:
  DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
  REGISTRY_PASSWORD: ${{ secrets.REGISTRY_PASSWORD }}
```

See [SECURITY.md](docs/SECURITY.md) for full security checklist.

## Monitoring & Health Checks

### Health Endpoints

```bash
# Liveness - is process alive?
curl http://localhost/api/health/live

# Readiness - can handle requests?
curl http://localhost/api/health/ready
```

### Error Tracking (Optional)

To enable GlitchTip integration:

```env
SENTRY_LARAVEL_DSN=https://<key>@glitchtip.example.com/projects/<id>/
SENTRY_ENVIRONMENT=production
```

See [GLITCHTIP.md](docs/GLITCHTIP.md) for setup.

## Troubleshooting

### Services Won't Start

```bash
# Check logs
docker-compose logs app mysql redis

# Rebuild images
docker-compose build --no-cache

# Force restart
docker-compose restart
```

### Database Connection Issues

```bash
# Verify MySQL is running
docker-compose logs mysql | grep "ready"

# Test connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo()
```

### High CPU/Memory

```bash
# Check resource usage
docker stats

# Check PHP processes
docker-compose exec app ps aux

# Increase limits in docker-compose.yml
```

See [RUNBOOKS.md](docs/RUNBOOKS.md) for detailed troubleshooting.

## Documentation

- [CI Pipeline](docs/CI_PIPELINE.md) - GitHub Actions & local testing
- [Docker & Runtime](docs/DOCKER_RUNTIME.md) - Container setup & services
- [Deployment](docs/DEPLOYMENT.md) - Safe release procedures
- [Security](docs/SECURITY.md) - Secrets, CORS, rate limiting
- [Runbooks](docs/RUNBOOKS.md) - Operational procedures
- [GlitchTip](docs/GLITCHTIP.md) - Error tracking setup

## Team

- **Dev Lead**: Setup CI/CD and infrastructure
- **DevOps**: Docker, deployment, monitoring
- **Backend**: Services, migrations, API logic
- **QA**: Testing, smoke tests, incidents

## Contributing

1. Create feature branch from `main`
2. Make changes
3. Push to GitHub (CI runs automatically)
4. Create Pull Request
5. All CI checks must pass
6. Code review + approval
7. Merge to main
8. Tag release: `git tag -a v1.2.0`
9. Deploy (manual approval)

## License

See [LICENSE](LICENSE) file.

## Support

- **Errors/Alerts**: Check [RUNBOOKS.md](docs/RUNBOOKS.md)
- **Questions**: See documentation in `docs/` folder
- **Infrastructure**: Contact DevOps team
- **Issues**: Create GitHub issue with details
