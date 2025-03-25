<?php

namespace Tests\Seeder;

use App\Models\Shelve;
use App\Models\Warehouse;

class ShelvesFactory
{
    public static function intShelvesFactory()
    {
        return Shelve::insert(
            [
                [
                    'id' => 1,
                    'name' => 'Shelf 1',
                    'location' => 'In the middle of the warehouse',
                    'warehouse_id' => 1,
                    'code' => 'WH1-S1'
                ],
                [
                    'id' => 2,
                    'name' => 'Shelf 2',
                    'location' => 'Warehouse corner',
                    'warehouse_id' => 1,
                    'code' => 'WH1-S2'
                ],
                [
                    'id' => 3,
                    'name' => 'Shelf 3',
                    'location' => 'In the middle of the warehouse',
                    'warehouse_id' => 2,
                    'code' => 'WH2-S3'
                ],
                [
                    'id' => 4,
                    'name' => 'Warehouse 4',
                    'location' => 'Warehouse corner',
                    'warehouse_id' => 2,
                    'code' => 'WH2-S4'
                ],
                [
                    'id' => 5,
                    'name' => 'Shelf 5',
                    'location' => 'In the middle of the warehouse',
                    'warehouse_id' => 3,
                    'code' => 'WH3-S5'
                ],
                [
                    'id' => 6,
                    'name' => 'Shelf 6',
                    'location' => 'Warehouse corner',
                    'warehouse_id' => 3,
                    'code' => 'WH3-S6'
                ],
                [
                    'id' => 7,
                    'name' => 'Shelf 7',
                    'location' => 'In the middle of the warehouse',
                    'warehouse_id' => 5,
                    'code' => 'WH5-S7'
                ],
                [
                    'id' => 8,
                    'name' => 'Shelf 8',
                    'location' => 'Warehouse corner',
                    'warehouse_id' => 5,
                    'code' => 'WH5-S8'
                ],
            ]
        );
    }
}
