# DBF synchronization

The legacy DBF import is integrated into Laravel and runs every 15 minutes.

## Configuration

Set these values in the runtime environment:

```dotenv
DBF_HOST_PATH=./storage/app/dbf
DBF_SOURCE_PATH=/data/dbf
DBF_ARCHIVE_PATH=
DBF_ENCRYPTION_KEY=replace-with-the-legacy-key
DBF_BATCH_SIZE=1000
DBF_PROCESS_MEMORY_LIMIT=256M
```

`DBF_SOURCE_PATH` can point to a directory containing DBF files and any number
of ZIP archives; all top-level ZIP files are searched automatically. To use one
specific archive or another archive directory, set `DBF_ARCHIVE_PATH`. Never commit the
encryption key or database credentials. In Docker Compose, `DBF_HOST_PATH` is
mounted read-only at `/data/dbf`.

## Commands

Run all imports:

```shell
php artisan dbf:sync
```

Run selected files or bypass checksum detection:

```shell
php artisan dbf:sync --file=FIRMS.DBF --file=ASS.DBF
php artisan dbf:sync --force
```

Every attempt is recorded in `dbf_import_runs`. The latest successful checksum
for each file is stored in `dbf_import_files`; unchanged files are skipped.
Each upsert batch is atomic; if a later batch fails, the next run safely resumes
the idempotent synchronization without holding one long database transaction.
The all-files command isolates every DBF in its own PHP process so memory from a
large table cannot accumulate into the next import.

On the first production deployment, back up the database, apply the migration,
and then inspect legacy duplicates:

```shell
php artisan migrate --force
php artisan dbf:deduplicate
php artisan dbf:deduplicate --apply
```

The apply mode preserves the canonical `detail` identifier, remaps cart and
order references, merges duplicate cart quantities, and removes redundant rows.

The Docker Compose `scheduler` service runs Laravel's scheduler. On multiple app
servers, configure a shared cache store and set `DBF_CLUSTER_SCHEDULER=true`.
