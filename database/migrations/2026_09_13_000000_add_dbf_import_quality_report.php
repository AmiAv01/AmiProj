<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dbf_import_runs', function (Blueprint $table): void {
            $table->unsignedBigInteger('images_written')->default(0);
            $table->unsignedBigInteger('issues_count')->default(0);
        });

        Schema::create('dbf_import_issues', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('run_id')->constrained('dbf_import_runs')->cascadeOnDelete();
            $table->unsignedBigInteger('detail_id');
            $table->integer('detail_code');
            $table->string('invoice', 100)->default('');
            $table->string('product_name', 200)->default('');
            $table->string('brand', 100)->default('');
            $table->boolean('missing_internal_code')->default(false);
            $table->boolean('missing_invoice')->default(false);
            $table->boolean('missing_cargo')->default(false);
            $table->boolean('missing_oem')->default(false);
            $table->boolean('missing_photo')->default(false);
            $table->timestamps();

            $table->unique(['run_id', 'detail_id']);
            $table->index('detail_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dbf_import_issues');

        Schema::table('dbf_import_runs', function (Blueprint $table): void {
            $table->dropColumn(['images_written', 'issues_count']);
        });
    }
};
