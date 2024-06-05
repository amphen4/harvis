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
        Schema::create('operations', function (Blueprint $table) {
            $table->id('operations_id');
            $table->foreignId('product_id');
            $table->foreign('product_id')->references('products_id')->on('products');
            $table->foreignId('operation_type_id');
            $table->foreign('operation_type_id')->references('operation_types_id')->on('operation_types');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
