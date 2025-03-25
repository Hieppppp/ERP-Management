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
        Schema::create('product_relations', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_product_id');
            $table->unsignedBigInteger('child_product_id');
            $table->foreign('child_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('parent_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['parent_product_id', 'child_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_relations');
    }
};
