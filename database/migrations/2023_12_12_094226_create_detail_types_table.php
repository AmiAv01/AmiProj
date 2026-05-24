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
        Schema::create('detail_type', function (Blueprint $table) {
            $table->integer('dt_tp_ptype')->primary();
            $table->string('dt_tp_name', 100);
            $table->string('dt_tp_ngroupdeal', 10);
            $table->integer('dt_tp_eac');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_type');
    }
};
