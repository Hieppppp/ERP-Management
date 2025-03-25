<?php

use App\Enums\PurchaseOrderStatusEnum;
use App\Models\ProductLocation;
use App\Models\PurchaseOrder;
use App\Models\SaleOrderDetail;
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
        Schema::create('sale_order_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_order_detail_id');
            $table->unsignedBigInteger('product_location_id');
            $table->float('quantity')->nullable();
            $table->foreign('sale_order_detail_id')->references('id')->on('sale_order_details')->onDelete('cascade');
            $table->foreign('product_location_id')->references('id')->on('product_locations')->onDelete('cascade');
        });

        Schema::table('sale_order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
        });

        //Update sale_order_details.product_id with product_locations.product_id
        SaleOrderDetail::join('product_locations as pl', 'sale_order_details.product_location_id', '=', 'pl.id')
            ->update(['sale_order_details.product_id' => DB::raw('pl.product_id')]);

        //Insert new records into sale_order_locations
        $minIds = SaleOrderDetail::select('sale_order_id', 'product_id', DB::raw('MIN(id) as min_id'), DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('sale_order_id', 'product_id')
            ->get();

        foreach ($minIds as $minId) {
            $saleOrderDetails = SaleOrderDetail::where('sale_order_id', $minId->sale_order_id)->where('product_id', $minId->product_id)->get();

            foreach ($saleOrderDetails as $saleOrderDetail) {
                DB::table('sale_order_locations')->insert([
                    'sale_order_detail_id' => $minId->min_id,
                    'product_location_id' => $saleOrderDetail->product_location_id,
                    'quantity' => $saleOrderDetail->quantity
                ]);
            }
            SaleOrderDetail::find($minId->min_id)->update([
                'quantity' => $minId->total_quantity
            ]);
        }

        //Delete sale_order_details where id is not in the list of min_ids
        $idsToKeep = $minIds->pluck('min_id')->toArray();
        SaleOrderDetail::whereNotIn('id', $idsToKeep)->delete();

        Schema::table('sale_order_details', function (Blueprint $table) {
            $table->dropForeign(['product_location_id']);
            $table->dropColumn('product_location_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        ProductLocation::join('product_purchase_orders', function ($join) {
            $join->on('product_locations.product_id', '=', 'product_purchase_orders.product_id')
                ->on('product_locations.purchase_order_id', '=', 'product_purchase_orders.purchase_order_id');
        })->whereNull('product_locations.unit_cost')->update(['product_locations.unit_cost' => DB::raw('product_purchase_orders.unit_cost')]);

        PurchaseOrder::where('status', PurchaseOrderStatusEnum::DONE)->whereNull('received_date')->update(['received_date' => DB::raw('created_at')]);
        PurchaseOrder::where('status', PurchaseOrderStatusEnum::DONE)->whereNull('scheduled_date')->update(['scheduled_date' => DB::raw('created_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_order_locations');
        Schema::table('sale_order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_location_id');
            $table->foreign('product_location_id')->references('id')->on('product_locations')->onDelete('cascade');
        });
    }
};
