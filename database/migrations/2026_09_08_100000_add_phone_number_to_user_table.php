<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table): void {
            $table->string('phone_number', 20)->nullable()->after('isAdmin');
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table): void {
            $table->dropColumn('phone_number');
        });
    }
};
