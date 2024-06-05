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
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->id('operation_logs_id');
            $table->foreignId('operation_id');
            $table->foreign('operation_id')->references('operations_id')->on('operations');
            $table->string('method');
            $table->text('uri');
            $table->json('request_details');
            $table->json('response_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_logs');
    }
};
