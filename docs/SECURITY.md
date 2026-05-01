# Security & Secrets Hygiene

## Objectives

1. ✅ Prevent secret exposure in repositories
2. ✅ Manage environment-specific configurations safely
3. ✅ Implement perimeter security controls
4. ✅ Protect sensitive endpoints
5. ✅ Clean up artifacts with embedded data

## Secret Management

### 1. Environment Variables

**Never commit secrets to Git!**

```bash
# ✅ Correct: Use .env (local only)
echo "DB_PASSWORD=super_secret" >> .env

# ❌ Wrong: Committed to Git
git add .env
git commit -m "Add secrets"
```

**Safe approach:**
```bash
# 1. Never add .env to version control
echo ".env" >> .gitignore
echo ".env.*.local" >> .gitignore

# 2. Provide .env.example as template
cp .env.example .env

# 3. Document required variables
# See .env.example for all required variables
```

### 2. GitHub Secrets

For CI/CD pipeline:

```bash
# Set in GitHub Settings → Secrets
SENTRY_LARAVEL_DSN=https://key@glitchtip.example.com/...
DB_PASSWORD=prod_password
REGISTRY_USERNAME=docker_user
REGISTRY_PASSWORD=docker_token
```

Access in workflow:
```yaml
env:
  DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
```

### 3. Secret Scanning

Automatically detect exposed secrets:

```bash
# Gitleaks (included in CI)
gitleaks detect --source . --report-path report.json

# Pre-commit hook
pip install detect-secrets
detect-secrets scan
```

Patterns detected:
- AWS keys
- Private keys
- API tokens
- Database credentials
- OAuth tokens

### 4. Rotate Secrets

```bash
#!/bin/bash
# Rotate database password

# 1. Generate new password
NEW_PASS=$(openssl rand -base64 32)

# 2. Update database user
mysql -u admin -p$OLD_PASS <<EOF
ALTER USER 'app_user'@'localhost' IDENTIFIED BY '$NEW_PASS';
FLUSH PRIVILEGES;
EOF

# 3. Update .env.production
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$NEW_PASS/" .env.production

# 4. Restart applications
docker-compose restart app queue-worker scheduler

# 5. Log rotation event
echo "Database password rotated: $(date)" >> /var/log/rotations.log

# 6. Notify team
# Send to Slack/email
```

**Rotation Schedule:**
- API tokens: Quarterly
- Database passwords: Every 6 months
- SSL certificates: Before expiration
- SSH keys: When team member leaves

## CORS Configuration

### By Environment

```php
// config/cors.php
'allowed_origins' => match(env('APP_ENV')) {
    'production' => ['https://example.com', 'https://app.example.com'],
    'staging' => ['https://staging.example.com', 'http://localhost:3000'],
    'local' => ['http://localhost:*', 'http://127.0.0.1:*'],
},

'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],

'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],

'allowed_credentials' => true,

'max_age' => 86400,
```

### Testing CORS

```bash
# Test preflight request
curl -H "Origin: http://localhost:3000" \
     -H "Access-Control-Request-Method: POST" \
     -X OPTIONS http://localhost/api/orders

# Should respond with allowed headers
```

## Trusted Hosts & Proxies

### Configure Trusted Proxies

```php
// app/Http/Middleware/TrustProxies.php
protected $proxies = [
    '10.0.0.0/8',              // Docker internal
    '172.16.0.0/12',           // Docker networks
    '192.168.1.0/24',          // Local network
    '203.0.113.0/24',          // CDN IP range
];

protected $headers = [
    Request::HEADER_FORWARDED => 'FORWARDED',
    Request::HEADER_X_FORWARDED_FOR => 'X_FORWARDED_FOR',
    Request::HEADER_X_FORWARDED_HOST => 'X_FORWARDED_HOST',
    Request::HEADER_X_FORWARDED_PROTO => 'X_FORWARDED_PROTO',
    Request::HEADER_X_FORWARDED_PORT => 'X_FORWARDED_PORT',
    Request::HEADER_X_FORWARDED_AWS_ELB => 'X_FORWARDED_AWS_ELB',
];
```

### For Docker

```php
// .env.local
TRUESTED_PROXIES="172.16.0.0/12"

// or in docker-compose.yml
environment:
  - TRUSTED_PROXIES=172.16.0.0/12
```

## Rate Limiting

### Sensitive Endpoints

```php
// routes/api.php

// Auth endpoints - 5 attempts per minute
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

// Password reset - 3 attempts per hour
Route::post('/forgot-password', [PasswordController::class, 'forgot'])
    ->middleware('throttle:3,60');

// Search - 100 per minute per user
Route::get('/search', [SearchController::class, 'index'])
    ->middleware('throttle:100,1');

// Admin endpoints - strict limit
Route::middleware('throttle:20,1')->group(function () {
    Route::post('/admin/users', [AdminController::class, 'store']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
});
```

### Custom Rate Limiting

```php
// config/cache.php
'rate_limiter' => [
    'auth_attempts' => '5,1',           // 5 per minute
    'password_reset' => '3,60',         // 3 per hour
    'api_search' => '100,1',            // 100 per minute
],

// Usage
RateLimiter::attempt('auth_attempts:' . $request->ip(), 5, function () {
    return login($credentials);
});
```

## Input Validation & XSS Prevention

```php
// Safe input handling
$validated = request()->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email',
    'message' => 'required|string|max:5000',
]);

// Always escape output in templates
{{ $user->name }}              // Auto-escaped by Blade
{!! $trusted_html !!}          // Only for trusted content

// Never trust user input in SQL
// ❌ Bad
$user = DB::select("SELECT * FROM users WHERE id = " . $id);

// ✅ Good
$user = User::find($id);
$user = DB::select("SELECT * FROM users WHERE id = ?", [$id]);
```

## Artifact Cleanup

### Generated Docs

Remove real data from generated documentation:

```bash
# Scribe generates API docs - may contain real payloads
rm -rf storage/scribe/

# If committed, scrub history
git filter-branch --tree-filter 'rm -rf storage/scribe/' -- --all

# Force push
git push origin main --force-with-lease
```

### Database Dumps

Never commit database dumps:

```bash
echo "*.sql" >> .gitignore
echo "*.dump" >> .gitignore
echo "database/*.sqlite" >> .gitignore
```

## SSL/TLS Certificates

### Nginx Configuration

```conf
server {
    listen 443 ssl http2;
    
    ssl_certificate /etc/nginx/certs/cert.pem;
    ssl_certificate_key /etc/nginx/certs/key.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # Redirect HTTP to HTTPS
    if ($scheme != "https") {
        return 301 https://$server_name$request_uri;
    }
}
```

### Let's Encrypt with Certbot

```bash
# Install
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot certonly --nginx -d api.example.com -d www.example.com

# Auto-renew
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Check renewal
sudo certbot renew --dry-run
```

## Security Headers

```php
// Middleware or nginx config
Header X-Content-Type-Options: nosniff
Header X-Frame-Options: DENY
Header X-XSS-Protection: 1; mode=block
Header Strict-Transport-Security: max-age=31536000; includeSubDomains
Header Content-Security-Policy: default-src 'self'
Header Referrer-Policy: strict-origin-when-cross-origin
```

Implemented in:
- [docker/nginx/default.conf](../../docker/nginx/default.conf)
- Laravel middleware

## Compliance Checklist

- [ ] No secrets in `.env` tracked by git
- [ ] `.env.example` documents all variables
- [ ] GitHub Secrets used for CI/CD
- [ ] CORS restricted by environment
- [ ] Trusted proxies configured
- [ ] Rate limiting on sensitive endpoints
- [ ] Input validation on all user inputs
- [ ] Output escaping in templates
- [ ] SSL/TLS enforced
- [ ] Security headers set
- [ ] Artifacts cleaned (no real data)
- [ ] Credentials rotated regularly
- [ ] Logs don't contain sensitive data

See also:
- [DEPLOYMENT.md](DEPLOYMENT.md) - Safe deployment procedures
- [RUNBOOKS.md](RUNBOOKS.md) - Incident response
