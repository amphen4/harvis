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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id('product_variants_id');
            $table->text('name');
            $table->string('sku');
            $table->unsignedInteger('stock')->default(0);
            $table->double('price');
            $table->boolean('active')->default(false);
            $table->string('talla')->nullable();
            $table->string('color')->nullable();

            $table->foreignId('product_id')->nullable();
            $table->foreign('product_id')->references('products_id')->on('products');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
