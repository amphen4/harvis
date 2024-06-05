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
        Schema::create('shop_product_categories', function (Blueprint $table) {
            $table->id('shop_product_categories_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('shop_id');
            $table->foreign('shop_id')->references('shops_id')->on('shops');
            $table->foreignId('parent_category_id')->nullable();
            $table->foreign('parent_category_id')->references('shop_product_categories_id')->on('shop_product_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_categories');
    }
};
