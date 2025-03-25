<?php

namespace Tests\Seeder;

use App\Models\PurchaseProductShelve;

class PurchaseProductShelveFactory
{
    public static function intPurchaseProductShelveFactory()
    {
        return PurchaseProductShelve::insert(
            [
                [
                    'product_id' => 1,
                    'purchase_order_id' => 3,
                    'quantity' => 5,
                    "shelve_id" => 5
                ],
                [
                    'product_id' => 1,
                    'purchase_order_id' => 3,
                    'quantity' => 5,
                    "shelve_id" => 6
                ],
                [
                    'product_id' => 1,
                    'purchase_order_id' => 3,
                    'quantity' => 4,
                    "shelve_id" => 5
                ],
                [
                    'product_id' => 1,
                    'purchase_order_id' => 3,
                    'quantity' => 6,
                    "shelve_id" => 6
                ],
                [
                    'product_id' => 2,
                    'purchase_order_id' => 3,
                    'quantity' => 10,
                    "shelve_id" => 6
                ],
                [
                    'product_id' => 3,
                    'purchase_order_id' => 3,
                    'quantity' => 10,
                    "shelve_id" => 5
                ],
                [
                    'product_id' => 1,
                    'purchase_order_id' => 5,
                    'quantity' => 10,
                    "shelve_id" => 8
                ],
                [
                    'product_id' => 2,
                    'purchase_order_id' => 5,
                    'quantity' => 10,
                    "shelve_id" => 7
                ],
                [
                    'product_id' => 3,
                    'purchase_order_id' => 5,
                    'quantity' => 10,
                    "shelve_id" => 8
                ]
            ]
        );
    }
}
