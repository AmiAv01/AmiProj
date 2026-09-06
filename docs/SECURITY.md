# Security baseline

- Keep `.env`, SSH keys, database dumps, and credentials out of Git and Docker build contexts.
- Use GitHub Environment secrets for deployment and a non-root, least-privilege deploy account.
- Verify the SSH host fingerprint out of band and store the known-hosts entry in `SSH_KNOWN_HOSTS`.
- Keep `APP_DEBUG=false`, secure session cookies enabled, and TLS mandatory in production.
- Expose only nginx publicly. Keep PHP-FPM, MySQL, and future Redis instances on private interfaces.
- Keep Composer and npm audits blocking; remediate Dependabot alerts through reviewed pull requests.
- Use a dedicated application database account rather than the MySQL root account.
- Encrypt off-host backups and prove they can be restored.
- Collect logs centrally with access controls and retention; avoid request bodies, authorization headers, cookies, and secrets.
- Review file-upload validation and serving rules before increasing the configured 64 MB request limit.

If a secret is committed or printed to CI logs, revoke and rotate it immediately. Removing it from the latest commit is not sufficient because Git history and logs may retain it.

Before each release, confirm that the CI security scans passed and that no exception was added merely to bypass an advisory.
