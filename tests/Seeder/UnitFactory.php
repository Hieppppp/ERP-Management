<?php

namespace Tests\Seeder;

use App\Models\Unit;

class UnitFactory
{
    public static function intUnitFactory()
    {
        return Unit::insert(
        [
            [
                'id' => 1,
                'name' => 'Kilogram',
                'symbol' => 'kg',
            ],
            [
                'id' => 2,
                'name' => 'meter',
                'symbol' => 'm',
            ],
            [
                'id' => 3,
                'name' => 'kilometer',
                'symbol' => 'km',
            ],
            [
                'id' => 4,
                'name' => 'cubic meter',
                'symbol' => 'm³'
            ],
            [
                'id' => 5,
                'name' => 'minute',
                'symbol' => 'min'
            ],
            [
                'id' => 6,
                'name' => 'gram',
                'symbol' => 'g'
            ],
        ]);
    }
}
