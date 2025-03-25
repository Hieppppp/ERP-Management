<?php

namespace Tests\Seeder;

use App\Models\Tax;

class TaxFactory
{
    public static function intTaxFactory()
    {
        return Tax::insert(
        [
            [
                'id' => 1,
                'name' => 'Tax 1',
                'code' => 'TPS',
                'rate' => 10
            ],
            [
                'id' => 2,
                'name' => 'Tax 2',
                'code' => 'TVQ',
                'rate' => 5
            ],
        ]);
    }
}
