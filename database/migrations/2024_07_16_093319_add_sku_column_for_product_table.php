<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique();
        });

        $products = Product::whereNull('sku')->get();

        foreach ($products as $product) {
            if ($product->sku) {
                continue;
            }
            $product->sku = $this->generateSKU();
            $product->save();
        }
    }

    /**
     * Generate SKU
     *
     * @return string
     */
    public function generateSKU(): string
    {
        do {
            $sku = rand(1, 9);
            for ($i = 0; $i < 7; $i++) {
                $sku .= rand(0, 9);
            }
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sku');
        });
    }
};
