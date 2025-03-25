<?php

namespace Tests\Seeder;

use App\Models\ProductLocation;
use Carbon\Carbon;

class ProductLocationFactory
{
    public static function intProductLocationFactory()
    {
        return ProductLocation::insert(
            [
                [
                    'id' => 1,
                    'purchase_order_id' => 5,
                    'product_id' => 1,
                    'quantity' => 10,
                    'shelve_id' => 7,
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 2,
                    'purchase_order_id' => 5,
                    'product_id' => 2,
                    'quantity' => 10,
                    'shelve_id' => 7,
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::parse('2024-06-29')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 3,
                    'purchase_order_id' => 5,
                    'product_id' => 3,
                    'quantity' => 10,
                    'shelve_id' => 8,
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::parse('2024-06-30')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 4,
                    'purchase_order_id' => 7,
                    'product_id' => 1,
                    'quantity' => 10,
                    'shelve_id' => 8,
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::parse('2024-06-28')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 5,
                    'purchase_order_id' => 7,
                    'product_id' => 2,
                    'quantity' => 20,
                    'shelve_id' => 7,
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::parse('2024-06-30')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 6,
                    'purchase_order_id' => 7,
                    'product_id' => 3,
                    'quantity' => 40,
                    'shelve_id' => 8,
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::parse('2024-06-28')->format("Y-m-d H:i:s")
                ],
            ]
        );
    }
}
