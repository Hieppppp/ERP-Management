<?php

use App\Enums\ReturnOrderStatusEnum;
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
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ReturnOrderStatusEnum::getValues())->default(ReturnOrderStatusEnum::READY);
            $table->unsignedBigInteger('purchase_order_id');
            $table->dateTime('scheduled_date')->nullable();
            $table->text('note')->nullable();
            $table->string('code', 32);
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('return_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_order_id');
            $table->unsignedBigInteger('product_location_id');
            $table->float('demand_quantity')->nullable();
            $table->float('quantity')->nullable();
            $table->foreign('return_order_id')->references('id')->on('return_orders')->onDelete('cascade');
            $table->foreign('product_location_id')->references('id')->on('product_locations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_order_details');
        Schema::dropIfExists('return_orders');
    }
};
