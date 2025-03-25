<?php

namespace Tests\Seeder;

use App\Models\ProductRelation;

class ProductRelationFactory
{
    public static function intProductRelationFactory()
    {
        return ProductRelation::insert(
            [
                [
                    'parent_product_id' => 1,
                    'child_product_id' => 2
                ],
                [
                    'parent_product_id' => 1,
                    'child_product_id' => 3
                ],
                [
                    'parent_product_id' => 2,
                    'child_product_id' => 3
                ],
                [
                    'parent_product_id' => 2,
                    'child_product_id' => 4
                ],
                [
                    'parent_product_id' => 4,
                    'child_product_id' => 5
                ],
                [
                    'parent_product_id' => 5,
                    'child_product_id' => 6
                ],
            ]
        );
    }
}
