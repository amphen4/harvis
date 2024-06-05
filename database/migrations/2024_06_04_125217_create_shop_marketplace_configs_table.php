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
        Schema::create('shop_marketplace_configs', function (Blueprint $table) {
            $table->id('shop_marketplace_configs_id');
            $table->json('value');
            $table->foreignId('marketplace_config_id');
            $table->foreign('marketplace_config_id')->references('marketplace_configs_id')->on('marketplace_configs');
            $table->foreignId('shop_id');
            $table->foreign('shop_id')->references('shops_id')->on('shops');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_marketplace_configs');
    }
};
