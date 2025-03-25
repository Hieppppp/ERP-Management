<?php

namespace Tests\Seeder;

use App\Models\ProductPurchaseOrder;

class ProductPurchaseOrderFactory
{
    public static function intProductPurchaseOrderFactory()
    {
        return ProductPurchaseOrder::insert(
            [
                [
                    'product_id' => 1,
                    'purchase_order_id' => 1,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 2,
                    'purchase_order_id' => 1,
                    'quantity' => 5,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 3,
                    'purchase_order_id' => 1,
                    'quantity' => 15,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 4,
                    'purchase_order_id' => 2,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 5,
                    'purchase_order_id' => 2,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 6,
                    'purchase_order_id' => 2,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 1,
                    'purchase_order_id' => 3,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => 10
                ],
                [
                    'product_id' => 2,
                    'purchase_order_id' => 3,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => 10
                ],
                [
                    'product_id' => 3,
                    'purchase_order_id' => 3,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => 10
                ],
                [
                    'product_id' => 4,
                    'purchase_order_id' => 4,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 5,
                    'purchase_order_id' => 4,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 6,
                    'purchase_order_id' => 4,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 1,
                    'purchase_order_id' => 5,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => 10
                ],
                [
                    'product_id' => 2,
                    'purchase_order_id' => 5,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => 10
                ],
                [
                    'product_id' => 3,
                    'purchase_order_id' => 5,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => 9
                ],
                [
                    'product_id' => 4,
                    'purchase_order_id' => 6,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 5,
                    'purchase_order_id' => 6,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
                [
                    'product_id' => 6,
                    'purchase_order_id' => 6,
                    'quantity' => 10,
                    'unit_cost' => 10,
                    "received_quantity" => null
                ],
            ]
        );
    }
}
