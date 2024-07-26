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
        Schema::create('marketplace_product_attributes', function (Blueprint $table) {
            $table->id('marketplace_product_attributes_id');
            $table->string('datatype');
            $table->foreignId('marketplace_id');
            $table->foreign('marketplace_id')->references('marketplaces_id')->on('marketplaces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_product_attributes');
    }
};
