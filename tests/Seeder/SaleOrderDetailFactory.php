<?php

namespace Tests\Seeder;

use App\Models\SaleOrderDetail;

class SaleOrderDetailFactory
{
    public static function intSaleOrderDetailFactory()
    {
        return SaleOrderDetail::insert(
            [
                [
                    'id' => 1,
                    'sale_order_id' => 2,
                    'product_id' => 1,
                    'quantity' => 5.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 12.0,
                ],
                [
                    'id' => 2,
                    'sale_order_id' => 2,
                    'product_id' => 2,
                    'quantity' => 12.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 12.0,
                ],
                [
                    'id' => 3,
                    'sale_order_id' => 2,
                    'product_id' => 3,
                    'quantity' => 10.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 12.0,
                ],
                [
                    'id' => 4,
                    'sale_order_id' => 3,
                    'product_id' => 1,
                    'quantity' => 10.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 12.0,
                ],
                [
                    'id' => 5,
                    'sale_order_id' => 3,
                    'product_id' => 2,
                    'quantity' => 12.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 12.0,
                ],
                [
                    'id' => 6,
                    'sale_order_id' => 3,
                    'product_id' => 3,
                    'quantity' => 8.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 12.0,
                ],
                [
                    'id' => 7,
                    'sale_order_id' => 4,
                    'product_id' => 1,
                    'quantity' => 8.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
                [
                    'id' => 8,
                    'sale_order_id' => 4,
                    'product_id' => 2,
                    'quantity' => 5.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
                [
                    'id' => 9,
                    'sale_order_id' => 5,
                    'product_id' => 1,
                    'quantity' => 12.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
                [
                    'id' => 10,
                    'sale_order_id' => 5,
                    'product_id' => 2,
                    'quantity' => 12.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
                [
                    'id' => 11,
                    'sale_order_id' => 5,
                    'product_id' => 3,
                    'quantity' => 2.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
                [
                    'id' => 12,
                    'sale_order_id' => 6,
                    'product_id' => 1,
                    'quantity' => 1.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
                [
                    'id' => 13,
                    'sale_order_id' => 6,
                    'product_id' => 2,
                    'quantity' => 1.0,
                    'unit_price' => 12.0,
                    'discount_rate' => 0.0,
                ],
            ]
        );
    }
}
