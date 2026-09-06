# CI/CD pipeline

`.github/workflows/laravel.yml` runs on pull requests and pushes to `main`.

Required CI jobs:

1. Composer manifest validation.
2. Full-history Gitleaks scan.
3. Pint, PHPStan, and blocking Composer security audit.
4. npm security audit, TypeScript checking, and frontend build.
5. PHPUnit/Pest tests on SQLite.
6. A clean migration run against MySQL 8.
7. A build-only validation of the production Docker target. The image is neither published nor deployed because the production host does not support containers.

Production deployment starts only after all required jobs pass. The deployment environment is serialized so two releases cannot modify the server concurrently. It preserves server-managed `.env`, `storage`, `bootstrap/cache`, backups, and the public storage link; applies migrations; rebuilds Laravel caches; restarts queue workers; and runs HTTP smoke tests.

## Repository settings

Protect `main` and require these checks before merge:

- `Validate`
- `Secret Scan`
- `Quality Checks`
- `Frontend Build`
- `Tests`
- `MySQL migrations`
- `Docker build validation`

Also require a pull request, at least one approval, resolution of review conversations, and an up-to-date branch. Disable direct pushes and force pushes to `main`.

Dependabot checks Composer, npm, Actions, and Docker dependencies weekly. Security updates should be merged through the same CI gates.

## Local equivalent

```bash
task check
```

Or run the commands listed in the root README when Task is unavailable.
