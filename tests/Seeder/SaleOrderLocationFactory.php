<?php

namespace Tests\Seeder;

use App\Models\SaleOrderLocation;

class SaleOrderLocationFactory
{
    public static function intSaleOrderLocationFactory()
    {
        return SaleOrderLocation::insert(
            [
                [
                    'id' => 1,
                    'sale_order_detail_id' => 1,
                    'product_location_id' => 1,
                    'quantity' => 3,
                ],
                [
                    'id' => 2,
                    'sale_order_detail_id' => 1,
                    'product_location_id' => 4,
                    'quantity' => 2,
                ],
                [
                    'id' => 3,
                    'sale_order_detail_id' => 2,
                    'product_location_id' => 5,
                    'quantity' => 12,
                ],
                [
                    'id' => 4,
                    'sale_order_detail_id' => 3,
                    'product_location_id' => 3,
                    'quantity' => 10,
                ],
                [
                    'id' => 5,
                    'sale_order_detail_id' => 4,
                    'product_location_id' => 1,
                    'quantity' => 5,
                ],
                [
                    'id' => 6,
                    'sale_order_detail_id' => 4,
                    'product_location_id' => 4,
                    'quantity' => 5,
                ],
                [
                    'id' => 7,
                    'sale_order_detail_id' => 5,
                    'product_location_id' => 5,
                    'quantity' => 12,
                ],
                [
                    'id' => 8,
                    'sale_order_detail_id' => 6,
                    'product_location_id' => 3,
                    'quantity' => 8,
                ],
                [
                    'id' => 9,
                    'sale_order_detail_id' => 7,
                    'product_location_id' => 1,
                    'quantity' => 8,
                ],
                [
                    'id' => 10,
                    'sale_order_detail_id' => 8,
                    'product_location_id' => 2,
                    'quantity' => 5,
                ],
            ]
        );
    }
}
