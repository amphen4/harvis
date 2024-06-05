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
        Schema::create('shop_brands', function (Blueprint $table) {
            $table->id('shop_brands_id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->foreignId('shop_id');
            $table->foreign('shop_id')->references('shops_id')->on('shops');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_brands');
    }
};
