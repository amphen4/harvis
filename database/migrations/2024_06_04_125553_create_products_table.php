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
        Schema::create('products', function (Blueprint $table) {
            $table->id('products_id');
            $table->text('name');
            $table->string('sku');
            $table->unsignedInteger('stock');
            $table->double('value');
            $table->boolean('active');

            $table->foreignId('shop_product_category_id');
            $table->foreign('shop_product_category_id')->references('shop_product_categories_id')->on('shop_product_categories');
            $table->foreignId('shop_brand_id');
            $table->foreign('shop_brand_id')->references('shop_brands_id')->on('shop_brands');
            $table->foreignId('marketplace_id');
            $table->foreign('marketplace_id')->references('marketplaces_id')->on('marketplaces');
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
        Schema::dropIfExists('products');
    }
};
