<?php

namespace Tests\Seeder;

use App\Models\ReturnOrderDetails;

class ReturnOrderDetailFactory
{
    public static function intReturnOrderDetailFactory()
    {
        return ReturnOrderDetails::insert(
            [
                [
                    'id' => 1,
                    'return_order_id' => 1,
                    'product_location_id' => '1',
                    'demand_quantity' => 10,
                    'quantity' => 0
                ],
                [
                    'id' => 2,
                    'return_order_id' => 1,
                    'product_location_id' => '2',
                    'demand_quantity' => 10,
                    'quantity' => 0
                ],
                [
                    'id' => 3,
                    'return_order_id' => 1,
                    'product_location_id' => '3',
                    'demand_quantity' => 10,
                    'quantity' => 0
                ],
                [
                    'id' => 4,
                    'return_order_id' => 2,
                    'product_location_id' => '1',
                    'demand_quantity' => 10,
                    'quantity' => 0
                ],
                [
                    'id' => 5,
                    'return_order_id' => 2,
                    'product_location_id' => '2',
                    'demand_quantity' => 10,
                    'quantity' => 0
                ],
                [
                    'id' => 6,
                    'return_order_id' => 2,
                    'product_location_id' => '3',
                    'demand_quantity' => 5,
                    'quantity' => 0
                ],
                [
                    'id' => 7,
                    'return_order_id' => 3,
                    'product_location_id' => '1',
                    'demand_quantity' => 5,
                    'quantity' => 0
                ],
                [
                    'id' => 8,
                    'return_order_id' => 3,
                    'product_location_id' => '2',
                    'demand_quantity' => 5,
                    'quantity' => 0
                ],
                [
                    'id' => 9,
                    'return_order_id' => 3,
                    'product_location_id' => '3',
                    'demand_quantity' => 5,
                    'quantity' => 0
                ],
                [
                    'id' => 10,
                    'return_order_id' => 4,
                    'product_location_id' => '1',
                    'demand_quantity' => 5,
                    'quantity' => 5
                ],
                [
                    'id' => 11,
                    'return_order_id' => 4,
                    'product_location_id' => '2',
                    'demand_quantity' => 5,
                    'quantity' => 5
                ],
                [
                    'id' => 12,
                    'return_order_id' => 4,
                    'product_location_id' => '3',
                    'demand_quantity' => 5,
                    'quantity' => 0
                ],
            ]
        );
    }
}
