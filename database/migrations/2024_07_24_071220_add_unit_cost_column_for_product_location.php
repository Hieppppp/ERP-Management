<?php

use App\Models\ProductLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_locations', function (Blueprint $table) {
            $table->float('unit_cost')->nullable();
        });
        ProductLocation::join('product_purchase_orders', function ($join) {
            $join->on('product_locations.product_id', '=', 'product_purchase_orders.product_id')
                ->on('product_locations.purchase_order_id', '=', 'product_purchase_orders.purchase_order_id');
        })->whereNull('product_locations.unit_cost')->update(['product_locations.unit_cost' => DB::raw('product_purchase_orders.unit_cost')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_locations', function (Blueprint $table) {
            $table->dropColumn('unit_cost');
        });
    }
};
