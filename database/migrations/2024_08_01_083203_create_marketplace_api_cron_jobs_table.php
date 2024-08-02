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
        Schema::create('marketplace_api_cron_jobs', function (Blueprint $table) {
            $table->id('marketplace_api_cron_jobs_id');
            $table->string('frequency_type');
            $table->json('weekdays')->nullable();
            $table->json('dayhours')->nullable();
            $table->text('cron_name');
            $table->foreignId('marketplace_id')->nullable();
            $table->foreign('marketplace_id')->references('marketplaces_id')->on('marketplaces');
            $table->foreignId('shop_id');
            $table->foreign('shop_id')->references('shops_id')->on('shops');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_api_cron_jobs');
    }
};
