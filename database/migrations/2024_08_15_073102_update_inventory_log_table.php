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
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->float('before_quantity')->nullable();
            $table->float('after_quantity')->nullable();
            $table->string('action')->nullable();
            $table->dropColumn('changed_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropColumn('before_quantity');
            $table->dropColumn('after_quantity');
            $table->dropColumn('action');
            $table->float('changed_quantity')->nullable();
        });
    }
};
