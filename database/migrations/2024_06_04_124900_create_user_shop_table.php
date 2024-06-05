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
        Schema::create('user_shop', function (Blueprint $table) {
            $table->id('user_shop_id');
            $table->foreignId('user_id');
            $table->foreignId('shop_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('shop_id')->references('shop_id')->on('shops');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_shop');
    }
};
