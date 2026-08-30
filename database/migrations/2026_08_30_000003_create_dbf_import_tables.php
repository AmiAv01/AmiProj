<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dbf_import_files', function (Blueprint $table): void {
            $table->string('filename')->primary();
            $table->char('sha256', 64);
            $table->unsignedBigInteger('size');
            $table->timestamp('source_modified_at')->nullable();
            $table->timestamp('imported_at');
            $table->timestamps();
        });

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

        foreach (['detail', 'oems', 'alt_cz', 'roz_cz'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->char('dbf_source_key', 64)->nullable();
            });
        }

        $this->prepareExistingMysqlData();

        foreach (['detail', 'oems', 'alt_cz', 'roz_cz'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                $table->unique('dbf_source_key', "{$tableName}_dbf_source_key_unique");
            });
        }

        Schema::table('price', function (Blueprint $table): void {
            $table->unique('code', 'price_code_unique');
        });
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
        DB::statement('DELETE duplicate FROM price duplicate JOIN price canonical ON duplicate.code = canonical.code AND duplicate.id > canonical.id');

        DB::statement(<<<'SQL'
            UPDATE detail target
            LEFT JOIN detail earlier ON earlier.dt_code = target.dt_code AND earlier.dt_id < target.dt_id
            SET target.dbf_source_key = SHA2(CAST(target.dt_code AS CHAR), 256)
            WHERE earlier.dt_id IS NULL
        SQL);
        DB::statement(<<<'SQL'
            UPDATE oems target
            LEFT JOIN oems earlier
                ON earlier.dt_invoice = target.dt_invoice
                AND earlier.dt_oem = target.dt_oem
                AND earlier.id < target.id
            SET target.dbf_source_key = SHA2(CONCAT_WS(CHAR(31), target.dt_invoice, target.dt_oem), 256)
            WHERE earlier.id IS NULL
        SQL);

        foreach (['alt_cz', 'roz_cz'] as $tableName) {
            DB::statement(<<<SQL
                UPDATE {$tableName} target
                LEFT JOIN {$tableName} earlier
                    ON earlier.tmp = target.tmp
                    AND earlier.hcparts = target.hcparts
                    AND earlier.brand = target.brand
                    AND earlier.typec = target.typec
                    AND earlier.dt_brand = target.dt_brand
                    AND earlier.dt_code = target.dt_code
                    AND earlier.id < target.id
                SET target.dbf_source_key = SHA2(CONCAT_WS(CHAR(31), target.tmp, target.hcparts, target.brand, target.typec, target.dt_brand, target.dt_code), 256)
                WHERE earlier.id IS NULL
            SQL);
        }
    }
};
