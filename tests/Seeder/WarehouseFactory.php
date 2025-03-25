<?php

namespace Tests\Seeder;

use App\Models\Warehouse;

class WarehouseFactory
{
    public static function intWarehouseFactory()
    {
        return Warehouse::insert(
        [
            [
                'id' => 1,
                'name' => 'Warehouse 1',
                'country' => 'Canada',
                'province' => 'New Brunswick',
                'city' => 'Camrose',
                'detail_address' => '12',
                'postal_code' => 100000,
                'code' => 'WH1'
            ],
            [
                'id' => 2,
                'name' => 'Warehouse 2',
                'country' => 'Canada',
                'province' => 'British Columbia',
                'city' => 'Grande Prairie',
                'detail_address' => '12',
                'postal_code' => 100000,
                'code' => 'WH2'
            ],
            [
                'id' => 3,
                'name' => 'Warehouse 3',
                'country' => 'Canada',
                'province' => 'Nunavut',
                'city' => 'St. Albert',
                'detail_address' => '12',
                'postal_code' => 100000,
                'code' => 'WH3'
            ],
            [
                'id' => 4,
                'name' => 'Warehouse 4',
                'country' => 'Canada',
                'province' => 'Yukon',
                'city' => 'Magrath',
                'detail_address' => '12',
                'postal_code' => 100000,
                'code' => 'WH4'
            ],
            [
                'id' => 5,
                'name' => 'Warehouse 5',
                'country' => 'Canada',
                'province' => 'Nova Scotia',
                'city' => 'Irricana',
                'detail_address' => '12',
                'postal_code' => 100000,
                'code' => 'WH5'
            ],
            [
                'id' => 6,
                'name' => 'Warehouse 6',
                'country' => 'Canada',
                'province' => 'Swan Hills',
                'city' => 'Camrose',
                'detail_address' => '12',
                'postal_code' => 100000,
                'code' => 'WH6'
            ],
        ]);
    }
}
