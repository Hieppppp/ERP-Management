<?php

use App\Models\Product;
use App\Models\Shelve;
use App\Models\Supplier;
use App\Models\Warehouse;
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
        Schema::table('shelves', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Shelve::withTrashed()->whereNull('code')
            ->update([
                'code' => DB::raw('CONCAT("WH", warehouse_id, "-S", id)')
            ]);

        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Warehouse::withTrashed()->whereNull('code')
            ->update([
                'code' => DB::raw('CONCAT("WH", id)')
            ]);

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Supplier::withTrashed()->whereNull('code')
            ->update([
                'code' => DB::raw('CONCAT("SUP", PaddedOrOriginalIfShorter(id, 3, "0"))')
            ]);

        Schema::table('products', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Product::withTrashed()->whereNull('code')
            ->update([
                'code' => DB::raw('CONCAT("PRD", PaddedOrOriginalIfShorter(id, 3, "0"))')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shelves', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
