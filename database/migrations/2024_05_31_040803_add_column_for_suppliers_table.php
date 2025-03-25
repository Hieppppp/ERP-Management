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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('site', 255)->nullable();
            $table->string('email', 255)->unique()->change();
            $table->string('phone', 255)->change();
            $table->string('detail_address')->nullable();
            $table->string('country', 255);
            $table->string('province', 255);
            $table->string('city', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('site');
            $table->dropColumn('detail_address');
            $table->dropColumn('country');
            $table->dropColumn('province');
            $table->dropColumn('city');
        });
    }
};
