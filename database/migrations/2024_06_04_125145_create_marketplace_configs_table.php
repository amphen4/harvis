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
        Schema::create('marketplace_configs', function (Blueprint $table) {
            $table->id('marketplace_configs_id');
            $table->text('name');
            $table->foreignId('marketplace_id');
            $table->foreign('marketplace_id')->references('marketplaces_id')->on('marketplaces');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_configs');
    }
};
