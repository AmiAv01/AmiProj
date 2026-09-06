# NewAmiProj

Laravel 12 and Vue 3 application backed by MySQL. Development uses Docker Compose; asynchronous mail notifications use Laravel's database queue.

## Development setup

Requirements: Docker with the Compose plugin. Node.js and PHP are only required when running tools directly on the host.

```bash
cp .env.example .env
./scripts/setup.sh
```

The application is available at `http://localhost` by default. Change `APP_PORT` in `.env` if port 80 is occupied. The setup script installs and builds frontend dependencies in a one-off Node container, so Node.js is not required on the host.

Useful commands:

```bash
docker compose ps
docker compose logs -f app nginx queue-worker
docker compose exec app php artisan migrate:status
docker compose exec app php artisan queue:failed
docker compose exec app php artisan test
```

If [Task](https://taskfile.dev/) is installed, run `task --list` for the equivalent shortcuts. Use `task <name> LOCALLY=1` to run PHP tools on the host instead of in Docker.

Run `task vite` when live frontend rebuilding is needed. The Node service belongs to the optional `tools` profile and does not stay running in the normal four-service stack.

## Health endpoints

- `GET /api/health/live` checks that Laravel can answer a request.
- `GET /api/health/ready` checks database, cache, and queue connectivity.

## Quality checks

```bash
vendor/bin/pint --test
vendor/bin/phpstan --memory-limit=512M
php artisan test
npm run typecheck
npm run build
composer audit --locked
npm audit --audit-level=high
```

## Operations

Production is a non-containerized PHP-FPM deployment: CI uploads the Laravel backend, production Composer dependencies, and the compiled Vue frontend. Docker is used locally and as a CI build check only.

- [Infrastructure guide](INFRASTRUCTURE.md)
- [Production checklist](docs/PRODUCTION_CHECKLIST.md)
- [CI pipeline](docs/CI_PIPELINE.md)
- [Runbooks](docs/RUNBOOKS.md)

Never commit `.env`, private keys, database dumps, or generated production credentials.
