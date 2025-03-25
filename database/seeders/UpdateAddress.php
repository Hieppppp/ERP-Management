<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Seeder;

class UpdateAddress extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $country = Country::insertGetId([
            'name' => 'Canada'
        ]);
        if ($country) {
            Province::whereNull('country_id')->update([
                'country_id' => $country
            ]);
        }
    }
}
