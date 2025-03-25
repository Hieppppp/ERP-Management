<?php

namespace Tests\Seeder;

use App\Models\Customer;

class CustomerFactory
{
    public static function intCustomerFactory()
    {
        return Customer::insert(
            [
                [
                    'id' => 1,
                    'first_name' => 'Customer',
                    'last_name' => '1',
                    'email' => 'customer1@gmail.com',
                    'phone' => "+12505550190",
                    'postal_code' => 1000,
                    'country' => 'Canada',
                    'province' => 'British Columbia',
                    'city' => 'Grande Prairie',
                    'detail_address' => 'Prairie',
                    'code' => 'CUS0001',
                    'discount' => 30,
                    'company_email' => 'company01@gmail.com',
                    

                ],
                [
                    'id' => 2,
                    'first_name' => 'Customer',
                    'last_name' => '2',
                    'email' => 'customer2@gmail.com',
                    'phone' => "+12505550191",
                    'postal_code' => 1000,
                    'country' => 'Canada',
                    'province' => 'Swan Hills',
                    'city' => 'Camrose',
                    'detail_address' => 'Camrose',
                    'code' => 'CUS0002',
                    'discount' => 30,
                    'company_email' => 'company02@gmail.com',
                ],
                [
                    'id' => 3,
                    'first_name' => 'Customer',
                    'last_name' => '3',
                    'email' => 'customer4@gmail.com',
                    'phone' => "+12505550192",
                    'postal_code' => 1000,
                    'country' => 'Canada',
                    'province' => 'Nova Scotia',
                    'city' => 'Irricana',
                    'detail_address' => 'Irricana',
                    'code' => 'CUS0003',
                    'discount' => 30,
                    'company_email' => 'company03@gmail.com',
                ],
                [
                    'id' => 4,
                    'first_name' => 'Customer',
                    'last_name' => '4',
                    'email' => 'customer4@gmail.com',
                    'phone' => "+12505550193",
                    'postal_code' => 1000,
                    'country' => 'Canada',
                    'province' => 'Yukon',
                    'city' => 'Magrath',
                    'detail_address' => 'Magrath',
                    'code' => 'CUS0004',
                    'discount' => 30,
                    'company_email' => 'company04@gmail.com',
                ],

            ]
        );
    }
}
