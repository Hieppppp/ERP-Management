<?php

namespace Tests\Seeder;

use App\Models\ProductSupplier;

class ProductSupplierFactory
{
    public static function intProductSupplierFactory()
    {
        return ProductSupplier::insert(
            [
                [
                    'product_id' => 1,
                    'supplier_id' => 1,
                    'unit_cost' => 100,
                    'sku' => 'sku1'
                ],
                [
                    'product_id' => 1,
                    'supplier_id' => 3,
                    'unit_cost' => 2000,
                    'sku' => ''
                ],
                [
                    'product_id' => 2,
                    'supplier_id' => 3,
                    'unit_cost' => 200,
                    'sku' => ''
                ],
                [
                    'product_id' => 2,
                    'supplier_id' => 4,
                    'unit_cost' => 233,
                    'sku' => ''
                ],
                [
                    'product_id' => 4,
                    'supplier_id' => 5,
                    'unit_cost' => 122,
                    'sku' => ''
                ],
                [
                    'product_id' => 5,
                    'supplier_id' => 6,
                    'unit_cost' => 567,
                    'sku' => ''
                ],
            ]
        );
    }
}
