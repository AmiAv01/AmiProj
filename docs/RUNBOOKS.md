# Operations runbooks

## Application unavailable

1. Check `/api/health/live`. If it fails, inspect nginx and PHP-FPM status/logs.
2. Check `/api/health/ready`. Its response identifies database, cache, or queue failure.
3. Verify disk space, inode usage, memory, and PHP-FPM saturation.
4. If the incident followed a deployment, stop further deployments and prepare to return to the previous release.

Development commands:

```bash
docker compose ps
docker compose logs --tail=200 nginx app queue-worker mysql
docker compose restart app nginx
```

## Queue is delayed or failing

```bash
php artisan queue:failed
php artisan queue:monitor default:100
php artisan queue:restart
```

Review the exception and correct its cause before retrying. Retry a specific failed job first; do not immediately retry or delete all failures. Confirm that the worker supervisor starts a new process after `queue:restart`.

## Database unavailable

1. Check network reachability and credentials without printing secrets.
2. Check MySQL connection count, disk space, locks, and slow queries.
3. Do not restart MySQL until active operations and recovery implications are understood.
4. If corruption or data loss is suspected, make the application read-only and preserve evidence before restoring.

## Rollback

Application rollback must restore the previous tested artifact or atomically switch the `current` symlink to the previous release. Do not automatically run migration `down()` methods: schema/data rollback is a separate, reviewed operation.

After rollback:

```bash
php artisan optimize
php artisan queue:restart
curl -fsS "$DEPLOY_URL/api/health/live"
curl -fsS "$DEPLOY_URL/api/health/ready"
```

## Backup verification

Run the backup script, upload the result to encrypted off-host storage, and verify freshness monitoring. On a schedule, restore the latest backup into an isolated database and run at least migration-status and representative read checks against it.

Never restore over production merely to test a backup.

## Incident notes

Record UTC timestamps, observed symptoms, affected users, deploy commit, commands executed, evidence links, mitigation, recovery time, and follow-up owners. Do not copy credentials, cookies, tokens, or personal data into incident documents.
