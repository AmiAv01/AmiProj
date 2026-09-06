<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart', function (Blueprint $table): void {
            $table->unique('user_id', 'cart_user_id_unique');
        });

        Schema::table('cart_item', function (Blueprint $table): void {
            $table->unique(['cart_id', 'dt_id'], 'cart_item_cart_detail_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cart_item', function (Blueprint $table): void {
            $table->dropUnique('cart_item_cart_detail_unique');
        });

        Schema::table('cart', function (Blueprint $table): void {
            $table->dropUnique('cart_user_id_unique');
        });
    }
};
