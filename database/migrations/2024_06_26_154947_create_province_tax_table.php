<?php

use App\Models\Tax;
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
        Schema::dropIfExists('tax_products');
        Tax::truncate();
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropColumn('rate');
        });
        Schema::create('tax_provinces', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('tax_id');
            $table->float('rate')->nullable();
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
        });
        $taxData = [
            [
                'name' => "Taxe sur les produits et services",
                "code" => 'TPS',
            ],
            [
                'name' => "Taxe de vente provinciale",
                "code" => 'TVP',
            ],
            [
                'name' => "Taxe de vente harmonisée",
                "code" => 'TVH',
            ],
            [
                'name' => "Taxe de vente du Québec",
                "code" => 'TVQ',
            ]
        ];
        Tax::insert($taxData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_provinces');
    }
};
