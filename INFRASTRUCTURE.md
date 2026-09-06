# Infrastructure guide

## Runtime model

The development stack intentionally contains only four services:

- `nginx`: the only public HTTP entry point;
- `app`: PHP-FPM;
- `mysql`: application database, exposed only on `127.0.0.1` for local tools;
- `queue-worker`: database-backed Laravel worker using the same application image.

Redis and a scheduler are not started because the application currently uses neither. Add Redis only when measured load or cross-instance cache/session requirements justify it. Add a scheduler only when `php artisan schedule:list` contains a real task.

An optional `frontend` service in the `tools` profile supplies Node 22 for builds, type checking, audits, and the Vite development server. It is one-off by default and is not part of the long-running stack.

## First start

```bash
cp .env.example .env
./scripts/setup.sh
```

The setup script builds the development image, waits for MySQL through Compose health checks, creates an application key only when one is missing, applies migrations, and creates the public storage link.

To reset only containers while preserving data:

```bash
docker compose down
docker compose up -d
```

To deliberately remove the development database and dependency volumes:

```bash
docker compose down --volumes
```

The latter command destroys local data and should not be used on a production host.

## Queue

The worker runs with:

```text
queue:work database --sleep=3 --tries=3 --backoff=10 --timeout=60 --max-time=3600 --memory=256
```

`retry_after` is 90 seconds, safely above the worker timeout. Workers recycle hourly to pick up code and release accumulated memory. Deployments must run `php artisan queue:restart` after the new code and migrations are ready.

Operational commands:

```bash
docker compose exec app php artisan queue:failed
docker compose exec app php artisan queue:retry all
docker compose exec app php artisan queue:flush
docker compose exec app php artisan queue:restart
```

Do not use `queue:flush` without first reviewing failed jobs.

## Images

The production server cannot run Docker. The production target described below is therefore used only by CI as a reproducible build check; deployment uploads the backend, `vendor/`, and compiled `public/build/` to a conventional nginx/PHP-FPM host.

The Dockerfile has two final targets:

- `development`: includes Composer and development dependencies and is used by Compose;
- `production`: contains optimized PHP dependencies, compiled frontend assets, OPcache configuration, and no Composer/Node toolchain.

Build the production target with:

```bash
docker build --target production -t ami:local .
```

Secrets are not accepted as Docker build arguments and `.env` is excluded from the build context. Runtime secrets must be injected by the deployment platform.

## Logs and debugging

Development uses the configured Laravel log channel and Laravel Debugbar. The production image defaults to `LOG_CHANNEL=stderr`; collect stdout/stderr with the host or container platform. `APP_DEBUG` must always be `false` in production.

Centralized error tracking is not installed. If GlitchTip or Sentry is adopted, install and test the official Laravel SDK, set a release identifier from the commit SHA, start with low tracing sample rates, and verify a test exception before relying on alerts.

## Backups

`scripts/backup.sh production` creates a private gzip-compressed logical dump and retains the latest 30 local copies. A local copy is not a disaster-recovery strategy: production must additionally upload encrypted backups to another host/account and periodically restore one into a disposable database.

See [the production checklist](docs/PRODUCTION_CHECKLIST.md) before enabling deployment.
