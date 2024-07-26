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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id('product_attributes_id');
            $table->json('value');
            $table->foreignId('product_id');
            $table->foreign('product_id')->references('products_id')->on('products');
            $table->foreignId('marketplace_product_attribute_id');
            $table->foreign('marketplace_product_attribute_id')->references('marketplace_product_attributes_id')->on('marketplace_product_attributes');
            /**Pivote*/
            $table->foreignId('marketplace_id');
            $table->foreign('marketplace_id')->references('marketplaces_id')->on('marketplaces');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
