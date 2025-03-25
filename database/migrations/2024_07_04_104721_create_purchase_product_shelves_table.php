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
        Schema::create('purchase_product_shelves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('shelve_id')->unsigned();
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shelve_id')->references('id')->on('shelves')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_product_shelves');
    }
};
