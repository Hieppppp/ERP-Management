<?php

namespace Tests\Seeder;

use App\Models\Product;

class ProductFactory
{
    public static function intProductFactory()
    {
        return Product::insert(
            [
                [
                    'id' => 1,
                    'name' => 'iPhone',
                    'unit_id' => 1,
                    'min_quantity' => 1,
                    'max_quantity' => 12,
                    'category_id' => 1,
                    'unit_price' => 2000
                ],
                [
                    'id' => 2,
                    'name' => 'Barbie Doll',
                    'unit_id' => 1,
                    'min_quantity' => 12,
                    'max_quantity' => 14,
                    'category_id' => 2,
                    'unit_price' => 3000
                ],
                [
                    'id' => 3,
                    'name' => 'Hot Wheels',
                    'unit_id' => 1,
                    'min_quantity' => 65,
                    'max_quantity' => 87,
                    'category_id' => 2,
                    'unit_price' => 1000
                ],
                [
                    'id' => 4,
                    'name' => 'Nespresso Machine',
                    'unit_id' => 1,
                    'min_quantity' => 32,
                    'max_quantity' => 43,
                    'category_id' => 1,
                    'unit_price' => 1200
                ],
                [
                    'id' => 5,
                    'name' => 'Red Bull',
                    'unit_id' => 1,
                    'min_quantity' => 12,
                    'max_quantity' => 65,
                    'category_id' => 3,
                    'unit_price' => 300
                ],
                [
                    'id' => 6,
                    'name' => 'Instant Pot',
                    'unit_id' => 1,
                    'min_quantity' => 43,
                    'max_quantity' => 65,
                    'category_id' => 3,
                    'unit_price' => 100
                ],
            ]
        );
    }
}
