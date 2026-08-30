# Docker runtime

Docker is for local development and CI validation only. The production host cannot run containers; it receives the Laravel backend, `vendor/`, and the compiled `public/build/` through the SSH/rsync workflow. The `production` Docker target is kept to prove that the application can be assembled reproducibly, not as the active production deployment format.

The canonical Docker configuration is documented in [INFRASTRUCTURE.md](../INFRASTRUCTURE.md).

Development uses the `development` target with a bind-mounted source tree and a named Composer vendor volume. The CI-only `production` target assembles an immutable image with compiled frontend assets, runs as `www-data`, and enables OPcache.

Only nginx should be published publicly. PHP-FPM is internal, and the local MySQL mapping is bound to `127.0.0.1`.

```bash
docker compose config --quiet
docker compose up -d --build
docker compose ps
docker compose logs -f app nginx queue-worker
```

Runtime credentials belong in `.env` for local development and in the production secret store for deployment. They must never be copied into an image.
