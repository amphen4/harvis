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
        Schema::create('shop_marketplace', function (Blueprint $table) {
            $table->id('shop_marketplace_id');
            $table->foreignId('marketplace_id');
            $table->foreign('marketplace_id')->references('marketplaces_id')->on('marketplaces');
            $table->foreignId('shop_id');
            $table->foreign('shop_id')->references('shops_id')->on('shops');
            $table->string('shop_alias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_marketplace');
    }
};
