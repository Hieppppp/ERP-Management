<?php

namespace Tests\Seeder;

use App\Models\Supplier;

class SupplierFactory
{
    public static function intSupplierFactory()
    {
        return Supplier::insert(
            [
                [
                    'id' => 1,
                    'name' => 'Supplier 1',
                    'country' => 'Canada',
                    'province' => 'New Brunswick',
                    'city' => 'Camrose',
                    'detail_address' => 'Camrose',
                    'email' => 'supplier1@gmail.com',
                    'phone' => "+12505550199",
                    'code' => 'SUP001'
                ],
                [
                    'id' => 2,
                    'name' => 'Supplier 2',
                    'country' => 'Canada',
                    'province' => 'British Columbia',
                    'city' => 'Grande Prairie',
                    'detail_address' => 'Prairie',
                    'email' => 'supplier2@gmail.com',
                    'phone' => "+12505550190",
                    'code' => 'SUP002'
                ],
                [
                    'id' => 3,
                    'name' => 'Supplier 3',
                    'country' => 'Canada',
                    'province' => 'Nunavut',
                    'city' => 'St. Albert',
                    'detail_address' => 'Albert',
                    'email' => 'supplier3@gmail.com',
                    'phone' => "+12505553199",
                    'code' => 'SUP003'
                ],
                [
                    'id' => 4,
                    'name' => 'Supplier 4',
                    'country' => 'Canada',
                    'province' => 'Yukon',
                    'city' => 'Magrath',
                    'detail_address' => 'Magrath',
                    'email' => 'supplier4@gmail.com',
                    'phone' => "+12505520199",
                    'code' => 'SUP004'
                ],
                [
                    'id' => 5,
                    'name' => 'Supplier 5',
                    'country' => 'Canada',
                    'province' => 'Nova Scotia',
                    'city' => 'Irricana',
                    'detail_address' => 'Irricana',
                    'email' => 'supplier5@gmail.com',
                    'phone' => "+12505550799",
                    'code' => 'SUP005'
                ],
                [
                    'id' => 6,
                    'name' => 'Supplier 6',
                    'country' => 'Canada',
                    'province' => 'Swan Hills',
                    'city' => 'Camrose',
                    'detail_address' => 'Camrose',
                    'email' => 'supplier6@gmail.com',
                    'phone' => "+12505150199",
                    'code' => 'SUP006'
                ],
            ]
        );
    }
}
