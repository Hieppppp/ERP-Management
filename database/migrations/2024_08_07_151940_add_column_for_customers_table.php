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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('contact_url')->nullable();
            $table->string('discount')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_site')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_province')->nullable();
            $table->string('company_city')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_postal_code')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_term')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('contact_url');
            $table->dropColumn('discount');
            $table->dropColumn('company_name');
            $table->dropColumn('company_phone');
            $table->dropColumn('company_email');
            $table->dropColumn('company_site');
            $table->dropColumn('company_country');
            $table->dropColumn('company_province');
            $table->dropColumn('company_city');
            $table->dropColumn('company_address');
            $table->dropColumn('company_postal_code');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_term');
        });
    }
};
