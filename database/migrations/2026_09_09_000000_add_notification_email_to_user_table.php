<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table): void {
            $table->string('notification_email')->nullable()->after('email');
        });

        DB::table('user')->update([
            'notification_email' => DB::raw('email'),
        ]);

        Schema::table('user', function (Blueprint $table): void {
            $table->string('notification_email')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table): void {
            $table->dropColumn('notification_email');
        });
    }
};
