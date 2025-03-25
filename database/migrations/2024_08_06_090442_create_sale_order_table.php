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
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('code')->nullable();
            $table->string('invoice_code')->nullable();
            $table->string('receipt_code')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('deliver_address')->nullable();
            $table->integer('payment_term')->nullable();
            $table->float('tax_rate')->nullable();
            $table->integer('order_status')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_order_id');
            $table->unsignedBigInteger('product_location_id');
            $table->float('quantity')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('discount_rate')->nullable();
        });

        Schema::create('register_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_order_id');
            $table->dateTime('date')->nullable();
            $table->string('payment_type')->nullable();
            $table->float('paid_amount')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_order_details');
        Schema::dropIfExists('register_payments');
        Schema::dropIfExists('sale_orders');
    }
};
