# Production checklist

## Deployment model

The production host does **not** run Docker or Docker Compose. GitHub Actions delivers one conventional application tree containing:

- the Laravel backend and production Composer dependencies in `vendor/`;
- the already compiled Vue frontend in `public/build/`.

nginx serves `public/` and forwards only `index.php` to the host PHP-FPM service. Node.js, npm, Composer, Docker, and build tooling are not required on the production server. The Docker build in CI is a validation gate only; its image is not published or deployed.

## 1. GitHub repository

Protect the `main` branch:

- require pull requests and at least one approval;
- require all checks listed in `CI_PIPELINE.md`;
- require review conversations to be resolved;
- require branches to be current before merge;
- block force pushes and branch deletion.

Create a GitHub Environment named `production`:

- restrict deployment to `main`;
- add required reviewers for manual production approval;
- keep environment secrets in the environment, not repository variables;
- retain deployment history.

Configure these environment secrets:

| Secret | Purpose |
|---|---|
| `SSH_PRIVATE_KEY` | Dedicated, passphrase-free deploy key with minimum server privileges |
| `SSH_HOST` | Production SSH hostname |
| `SSH_USERNAME` | Non-root deployment user |
| `SSH_PORT` | Optional non-default SSH port |
| `SSH_KNOWN_HOSTS` | Verified known-hosts entry for the production server |
| `DEPLOY_PATH` | Absolute application path on the server |
| `DEPLOY_URL` | Public HTTPS base URL used by smoke tests |

Generate `SSH_KNOWN_HOSTS` from a trusted administrator machine and verify its fingerprint against the hosting provider console before saving it. Do not generate trust dynamically inside CI.

Give the deployment key write access only to `DEPLOY_PATH` and permission to run the explicitly required service reload/restart commands. Do not deploy as `root`.

## 2. Server packages

Install:

- nginx;
- PHP 8.4 FPM and CLI;
- PHP extensions: bcmath, curl, fileinfo, intl, mbstring, openssl, PDO MySQL, tokenizer, XML, and OPcache;
- Supervisor or a systemd unit for the Laravel queue worker;
- MySQL client tools for backup/restore checks.

Node.js and Composer are not required on the server because CI uploads built assets and PHP dependencies.

## 3. Server directories

Before the first deployment:

```bash
sudo install -d -o DEPLOY_USER -g WEB_GROUP /srv/ami
sudo install -d -o DEPLOY_USER -g WEB_GROUP /srv/ami/storage
sudo install -d -o DEPLOY_USER -g WEB_GROUP /srv/ami/bootstrap/cache
sudo chmod -R ug+rwX /srv/ami/storage /srv/ami/bootstrap/cache
```

Replace the example users, groups, and path with the actual deployment values. Create `/srv/ami/.env` manually with mode `0600`; CI deliberately never uploads it.

## 4. Production environment

Required baseline:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
LOG_LEVEL=warning
DB_CONNECTION=mysql
QUEUE_CONNECTION=database
QUEUE_AFTER_COMMIT=true
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
```

Generate `APP_KEY` once and back it up in the secret manager. Rotating it without an explicit key-rotation plan invalidates encrypted application data and sessions.

Use a dedicated least-privilege MySQL user. Do not expose MySQL publicly. Configure SMTP credentials and `MAIL_NOTIFICATION`, then verify both successful delivery and failed-job behaviour.

## 5. Queue worker

Example Supervisor program:

```ini
[program:ami-worker]
directory=/srv/ami
command=/usr/bin/php artisan queue:work database --sleep=3 --tries=3 --backoff=10 --timeout=60 --max-time=3600 --memory=256
user=www-data
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
stopwaitsecs=75
redirect_stderr=true
stdout_logfile=/var/log/ami-worker.log
```

Configure log rotation. Alert when failed jobs appear or when queue depth remains above an agreed threshold. The deploy workflow calls `queue:restart`; Supervisor must automatically start the replacement process.

No scheduler is required today. When scheduled tasks are introduced, add one cron entry:

```cron
* * * * * cd /srv/ami && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

## 6. nginx and TLS

- Point the document root at `/srv/ami/public`, never the repository root.
- Pass only `/index.php` to PHP-FPM.
- Obtain and automatically renew a trusted TLS certificate.
- Redirect HTTP to HTTPS.
- Set the real proxy/IP headers only for known proxies.
- Limit request body size and request rates to the application's needs.
- Confirm that `.env`, Git files, storage internals, and arbitrary PHP files return 404/403.

## 7. Database safety

Before the first deployment containing `2026_08_30_000000_enforce_cart_uniqueness.php`, verify there are no duplicate carts per user or duplicate cart items per `(cart_id, dt_id)`.

Production backups must be encrypted, copied off-host/off-account, monitored, and retained according to business requirements. Perform a restore into a disposable database at least quarterly. A backup that has never been restored is unverified.

Use backward-compatible expand/contract migrations for future releases. Avoid destructive schema changes in the same release that stops reading the old schema.

## 8. Release verification

After deployment verify:

```bash
curl -fsS https://example.com/api/health/live
curl -fsS https://example.com/api/health/ready
php artisan migrate:status
php artisan queue:failed
```

Also verify login, one read operation, one queue notification in staging, logs, disk usage, certificate expiry monitoring, and backup freshness.

The current rsync deployment is not atomic. Plan release directories and an atomic `current` symlink before calling the deployment zero-downtime or relying on automatic rollback.
