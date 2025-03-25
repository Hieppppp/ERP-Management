<?php

namespace Tests\Seeder;

use App\Models\RegisterPayment;

class RegisterPaymentFactory
{
    public static function intRegisterPaymentFactory()
    {
        return RegisterPayment::insert(
            [
                [
                    'id' => 2,
                    'sale_order_id' => 3,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 50.0,
                    'note' => null,
                ],
                [
                    'id' => 3,
                    'sale_order_id' => 3,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 44.6176,
                    'note' => null,
                ],
                [
                    'id' => 4,
                    'sale_order_id' => 4,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 10.0,
                    'note' => null
                ],
                [
                    'id' => 5,
                    'sale_order_id' => 4,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 10.0,
                    'note' => null
                ],
                [
                    'id' => 6,
                    'sale_order_id' => 4,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 10.0,
                    'note' => null
                ],
                [
                    'id' => 7,
                    'sale_order_id' => 4,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 10.0,
                    'note' => null
                ],
                [
                    'id' => 8,
                    'sale_order_id' => 5,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 50.0,
                    'note' => null
                ],
                [
                    'id' => 9,
                    'sale_order_id' => 5,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 50.0,
                    'note' => null
                ],
                [
                    'id' => 10,
                    'sale_order_id' => 6,
                    'date' => '2024-08-12 00:00:00',
                    'payment_type' => 'check',
                    'paid_amount' => 26.88,
                    'note' => null
                ],
            ]
        );
    }
}
