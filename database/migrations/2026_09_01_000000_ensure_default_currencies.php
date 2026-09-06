<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach (config('currency.rates', []) as $code => $value) {
            $existingId = DB::table('currency')
                ->where('code', $code)
                ->orderByDesc('id')
                ->value('id');

            if ($existingId !== null) {
                DB::table('currency')
                    ->where('code', $code)
                    ->where('id', '!=', $existingId)
                    ->delete();

                continue;
            }

            DB::table('currency')->insert([
                'code' => $code,
                'value' => Crypt::encrypt((string) $value),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Schema::table('currency', function (Blueprint $table): void {
            $table->unique('code', 'currency_code_unique');
        });
    }

    public function down(): void
    {
        Schema::table('currency', function (Blueprint $table): void {
            $table->dropUnique('currency_code_unique');
        });
    }
};
