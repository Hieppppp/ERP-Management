<?php

use App\Models\ProductLocation;
use App\Models\PurchaseProductShelve;
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
            $table->dropForeign('product_locations_batch_id_foreign');
            $table->dropColumn('batch_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->renameColumn('shelf_id', 'shelve_id');
        });
        // update data
        ProductLocation::insert(
            PurchaseProductShelve::select('purchase_order_id', 'product_id', 'shelve_id', 'quantity', 'created_at', 'updated_at')->get()->toArray()
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_locations', function (Blueprint $table) {
            $table->dropForeign('product_locations_purchase_order_id_foreign');
            $table->dropColumn('purchase_order_id');
            $table->unsignedBigInteger('batch_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
        });
    }
};
