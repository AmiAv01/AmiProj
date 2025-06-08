<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alt_cz', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->dateTime('datep');
            $table->string('tmp');
            $table->string('hcparts')->index('idx_alt_hcparts');
            $table->string('brand');
            $table->string('typec');
            $table->string('dt_brand');
            $table->string('dt_code');
            $table->string('img');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alt_cz');
    }
};
