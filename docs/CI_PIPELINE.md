# CI/CD Pipeline Documentation

## GitHub Actions Workflow

The project uses GitHub Actions for automated CI/CD. The workflow is split into 4 independent/dependent stages:

### Stage 1: Validate
- **Job**: `validate`
- **Trigger**: Always runs first
- **Tasks**:
  - Validate `composer.json` with strict mode
  - Ensures dependency manifest integrity

### Stage 2: Secret Scanning  
- **Job**: `secret-scan`
- **Trigger**: Runs in parallel with validate
- **Tasks**:
  - Scans repository for exposed secrets using Gitleaks
  - Prevents accidental commits of API keys, credentials, etc.

### Stage 3: Quality Checks
- **Job**: `quality`
- **Trigger**: After validate completes
- **Tasks**:
  - **Pint**: Code formatting check (`vendor/bin/pint --test`)
  - **PHPStan**: Static analysis (`vendor/bin/phpstan`)
  - **Composer Audit**: Security vulnerability scan (non-blocking)
- **Caching**: Composer dependencies cached by lock file hash

### Stage 4: Tests
- **Job**: `tests`
- **Trigger**: After validate and quality pass
- **Tasks**:
  - Database setup (SQLite for CI)
  - Run migrations
  - Execute test suite via Pest/PHPUnit
- **Caching**: Reuses composer vendor cache

## Local Development

### Run Full CI Pipeline Locally

```bash
task lint       # Pint formatting check
task phpstan    # Static analysis
task test       # Run tests
```

### Individual Tasks

```bash
# Code formatting
task pint               # Auto-format code
task pint -- --test    # Check formatting (dry-run)

# Static analysis
task phpstan            # Run PHPStan

# Testing
task test               # Run Pest/PHPUnit tests

# Advanced
task psalm              # Psalm type checking
task rector             # Rector refactoring checks
```

## Branch Protection Rules

Recommend setting these on the `main` branch:

1. ✅ Require status checks to pass:
   - `validate` 
   - `secret-scan`
   - `quality`
   - `tests`

2. ✅ Require code reviews before merging
3. ✅ Require branches to be up to date

## Quality Gates Standards

### Pint (Code Formatting)
- Configuration: [pint.json](../../pint.json)
- Standard: PSR-12
- Auto-fixable: Yes

### PHPStan (Static Analysis)
- Configuration: [phpstan.neon](../../phpstan.neon)
- Level: Configurable (currently set in config)
- No-diff option: Reduces noise in reports

### Composer Audit (Security)
- Scans for known CVEs in dependencies
- Non-blocking (warnings only)
- Run manually: `composer audit`

### Pest (Testing)
- Configuration: [phpunit.xml](../../phpunit.xml) 
- Database: SQLite for CI
- Coverage: Optional (can be added to workflow)

## Troubleshooting

### Pipeline Fails Locally But Passes in CI

1. Ensure PHP version matches (8.4 in CI)
2. Use SQLite for testing: `DB_CONNECTION=sqlite DB_DATABASE=database/database.sqlite`
3. Run `composer install` without scripts: `composer install --no-scripts`

### Pint Failures

```bash
# Auto-fix code
vendor/bin/pint
```

### PHPStan Failures

```bash
# Run with detailed output
vendor/bin/phpstan --memory-limit=512M
```

### Tests Fail

```bash
# Reset test database and re-run
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate --database=sqlite
php artisan test --database=sqlite
```

## Future Enhancements

- [ ] Code coverage reporting (Codecov)
- [ ] Performance testing
- [ ] Database migration safety checks
- [ ] API documentation generation validation
- [ ] Docker image building and registry push
