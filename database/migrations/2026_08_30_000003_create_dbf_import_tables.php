<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dbf_import_files')) {
            Schema::create('dbf_import_files', function (Blueprint $table): void {
                $table->string('filename')->primary();
                $table->char('sha256', 64);
                $table->unsignedBigInteger('size');
                $table->timestamp('source_modified_at')->nullable();
                $table->timestamp('imported_at');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dbf_import_runs')) {
            Schema::create('dbf_import_runs', function (Blueprint $table): void {
                $table->id();
                $table->string('filename');
                $table->char('sha256', 64)->nullable();
                $table->string('status', 20)->index();
                $table->unsignedBigInteger('records_read')->default(0);
                $table->unsignedBigInteger('records_written')->default(0);
                $table->text('error')->nullable();
                $table->timestamp('started_at');
                $table->timestamp('finished_at')->nullable();
                $table->timestamps();
            });
        }

        foreach (['detail', 'oems', 'alt_cz', 'roz_cz'] as $tableName) {
            if (! Schema::hasColumn($tableName, 'dbf_source_key')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->char('dbf_source_key', 64)->nullable();
                });
            }
        }

        $this->prepareExistingMysqlData();

        foreach (['detail', 'oems', 'alt_cz', 'roz_cz'] as $tableName) {
            $indexName = "{$tableName}_dbf_source_key_unique";
            if (! Schema::hasIndex($tableName, $indexName)) {
                Schema::table($tableName, function (Blueprint $table) use ($indexName): void {
                    $table->unique('dbf_source_key', $indexName);
                });
            }
        }

        if (! Schema::hasIndex('price', 'price_code_unique')) {
            Schema::table('price', function (Blueprint $table): void {
                $table->unique('code', 'price_code_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('price', function (Blueprint $table): void {
            $table->dropUnique('price_code_unique');
        });

        foreach (['detail', 'oems', 'alt_cz', 'roz_cz'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                $table->dropUnique("{$tableName}_dbf_source_key_unique");
                $table->dropColumn('dbf_source_key');
            });
        }

        Schema::dropIfExists('dbf_import_runs');
        Schema::dropIfExists('dbf_import_files');
    }

    private function prepareExistingMysqlData(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasColumn('price', 'id')) {
            DB::statement('ALTER TABLE price ADD COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        }
        DB::statement(<<<'SQL'
            DELETE duplicate
            FROM price duplicate
            INNER JOIN (
                SELECT code, MIN(id) AS canonical_id
                FROM price
                GROUP BY code
            ) canonical ON canonical.code = duplicate.code
            WHERE duplicate.id <> canonical.canonical_id
        SQL);

        DB::statement(<<<'SQL'
            UPDATE detail target
            INNER JOIN (
                SELECT MIN(dt_id) AS canonical_id
                FROM detail
                GROUP BY dt_code
            ) canonical ON canonical.canonical_id = target.dt_id
            SET target.dbf_source_key = SHA2(CAST(target.dt_code AS CHAR), 256)
            WHERE target.dbf_source_key IS NULL
        SQL);
        DB::statement(<<<'SQL'
            UPDATE oems target
            INNER JOIN (
                SELECT MIN(id) AS canonical_id
                FROM oems
                GROUP BY dt_invoice, dt_oem
            ) canonical ON canonical.canonical_id = target.id
            SET target.dbf_source_key = SHA2(CONCAT_WS(CHAR(31), target.dt_invoice, target.dt_oem), 256)
            WHERE target.dbf_source_key IS NULL
        SQL);

        foreach (['alt_cz', 'roz_cz'] as $tableName) {
            DB::statement(<<<SQL
                UPDATE {$tableName} target
                INNER JOIN (
                    SELECT MIN(id) AS canonical_id
                    FROM {$tableName}
                    GROUP BY tmp, hcparts, brand, typec, dt_brand, dt_code
                ) canonical ON canonical.canonical_id = target.id
                SET target.dbf_source_key = SHA2(CONCAT_WS(CHAR(31), target.tmp, target.hcparts, target.brand, target.typec, target.dt_brand, target.dt_code), 256)
                WHERE target.dbf_source_key IS NULL
            SQL);
        }
    }
};
