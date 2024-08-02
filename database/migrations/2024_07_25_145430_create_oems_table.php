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
        Schema::create('oems', function (Blueprint $table) {
            $table->id();
            $table->string('dt_invoice', 20);
            $table->string('dt_parent', 15);
            $table->string('dt_oem', 20);
            $table->string('fr_code', 20);
            $table->string('dt_typec', 40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oems');
    }
};
