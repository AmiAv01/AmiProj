# Deployment

The current GitHub Actions deployment targets a conventional PHP-FPM server over SSH. Docker is unavailable on the production host and is not part of the deployment path. It is suitable as an intermediate deployment model, provided the server and repository environment follow [PRODUCTION_CHECKLIST.md](PRODUCTION_CHECKLIST.md).

The workflow uploads the Laravel backend with tested production Composer dependencies plus the already compiled Vue assets in `public/build`. It preserves server-owned runtime data, applies migrations, rebuilds caches, restarts Laravel queue workers, and runs smoke tests. The server does not run npm, Composer, or a container build.

The next production-hardening step is atomic releases:

```text
releases/<commit-sha>/
shared/.env
shared/storage/
current -> releases/<commit-sha>
```

Upload into a new release directory, link shared state, migrate, warm caches, switch `current` atomically, reload PHP-FPM, restart workers, and retain at least the previous two releases. If smoke tests fail, switch `current` back. Database migrations must remain backward-compatible because application rollback does not automatically reverse data changes.

Do not claim zero downtime until this release-directory flow and rollback have been exercised on staging.
