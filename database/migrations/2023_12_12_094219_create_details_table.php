<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail', function (Blueprint $table) {
            $table->unsignedBigInteger('dt_id')->primary();
            $table->integer('dt_code');
            $table->integer('dt_extcode');
            $table->integer('dt_extname');
            $table->string('dt_type', 200);
            $table->string('dt_comment', 255);
            $table->string('dt_foto', 255);
            $table->string('dt_invoice', 100);
            $table->integer('dt_netto');
            $table->string('dt_oem');
            $table->integer('dt_baza');
            $table->integer('dt_cena');
            $table->integer('dt_prod');
            $table->string('dt_typec', 100);
            $table->integer('dt_bp');
            $table->string('dt_cargo', 50);
            $table->integer('dt_e');
            $table->integer('dt_hs');
            $table->date('dt_datep');
            $table->string('dt_name', 100);
            $table->string('fr_code', 100);
            $table->integer('dt_tp_ptype');
            $table->integer('lt_dt_acode');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail');
    }
};
