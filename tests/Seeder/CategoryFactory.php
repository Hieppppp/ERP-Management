<?php

namespace Tests\Seeder;

use App\Models\Category;

class CategoryFactory
{
    public static function intCategoryFactory()
    {
        return Category::insert(
            [
                [
                    'id' => 1,
                    'name' => 'Trousers',
                    'description' => 'Shorts',
                ],
                [
                    'id' => 2,
                    'name' => 'Shirt',
                    'description' => 'T-shirt',
                ],
                [
                    'id' => 3,
                    'name' => 'Hat',
                    'description' => 'Cap'
                ],
                [
                    'id' => 4,
                    'name' => 'Computer',
                    'description' => 'Laptop'
                ],
                [
                    'id' => 5,
                    'name' => 'Vehicle',
                    'description' => 'Car',
                ],
                [
                    'id' => 6,
                    'name' => 'Electronic',
                    'description' => 'Phone'
                ],
            ]
        );
    }
}
