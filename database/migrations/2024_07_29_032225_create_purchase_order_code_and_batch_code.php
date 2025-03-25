<?php

use App\Enums\PurchaseOrderStatusEnum;
use App\Models\PurchaseOrder;
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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('code', 32)->nullable()->after('received_note');
            $table->string('batch_code', 32)->nullable()->after('received_note');
            $table->string('receipt_code', 32)->nullable()->after('received_note');
        });

        PurchaseOrder::whereNull('code')
            ->update([
                'code' => DB::raw('CONCAT("P", PaddedOrOriginalIfShorter(id, 5, "0"))')
            ]);

        PurchaseOrder::whereNull('batch_code')
            ->whereIn('status', [
                PurchaseOrderStatusEnum::PENDING_SHELVE,
                PurchaseOrderStatusEnum::DONE
            ])
            ->update([
                'batch_code' => DB::raw('CONCAT("BATCH", PaddedOrOriginalIfShorter(id, 4, "0"))')
            ]);

        PurchaseOrder::whereNull('receipt_code')
            ->where('status', '!=', PurchaseOrderStatusEnum::DRAFT)
            ->update([
                'receipt_code' => DB::raw('CONCAT_WS("-", CONCAT("WH", warehouse_id), "IN", code)')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('batch_code');
            $table->dropColumn('receipt_code');
        });
    }
};
