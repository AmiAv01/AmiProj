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
        Schema::create('alt_cz', function (Blueprint $table) {
            $table->id();
            $table->date('datep');
            $table->string('tmp', 25);
            $table->string('hcparts', 15);
            $table->string('brand', 20);
            $table->string('typec', 50);
            $table->string('dt_brand', 20);
            $table->string('dt_code', 20);
            $table->string('img', 40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alt_cz');
    }
};
