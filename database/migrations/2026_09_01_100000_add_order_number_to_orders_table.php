<?php

use App\Support\OrderNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table): void {
            $table->string('order_number', 14)->nullable()->unique()->after('id');
        });

        DB::table('order')
            ->select('id')
            ->orderBy('id')
            ->eachById(function (object $order): void {
                do {
                    $number = OrderNumber::generate();
                } while (DB::table('order')->where('order_number', $number)->exists());

                DB::table('order')->where('id', $order->id)->update(['order_number' => $number]);
            });

        Schema::table('order', function (Blueprint $table): void {
            $table->string('order_number', 14)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order', function (Blueprint $table): void {
            $table->dropUnique(['order_number']);
            $table->dropColumn('order_number');
        });
    }
};
