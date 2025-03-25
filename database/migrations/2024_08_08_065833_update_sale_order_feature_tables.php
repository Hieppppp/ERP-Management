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
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->integer('delivery_method')->nullable();
            $table->text('tax_info')->nullable();
            $table->integer('receipt_status')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::table('sale_order_details', function (Blueprint $table) {
            $table->foreign('sale_order_id')->references('id')->on('sale_orders')->onDelete('cascade');
            $table->foreign('product_location_id')->references('id')->on('product_locations')->onDelete('cascade');
        });

        Schema::table('register_payments', function (Blueprint $table) {
            $table->foreign('sale_order_id')->references('id')->on('sale_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropColumn('delivery_method');
            $table->dropColumn('tax_info');
            $table->dropColumn('receipt_status');
        });
    }
};
