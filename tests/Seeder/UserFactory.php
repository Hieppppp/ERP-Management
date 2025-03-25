<?php

namespace Tests\Seeder;

use App\Enums\UserRole;
use App\Models\User;

class UserFactory
{
    public static function intUserFactory()
    {
        return User::factory()->createMany(
        [
            [
                'id' => 1,
                'username' => 'admin',
                'password' => '@admin1234',
                'email' => 'admin@gmail.com',
                'role' => UserRole::SUPPER_ADMIN
            ],
            [
                'id' => 2,
                'username' => 'admin1',
                'password' => '@admin1234',
                'email' => 'admin1@gmail.com',
                'role' => UserRole::ADMIN
            ],
            [
                'id' => 3,
                'username' => 'admin3',
                'password' => '@admin1234',
                'email' => 'admin3@gmail.com',
                'role' => UserRole::USER
            ],
            [
                'id' => 4,
                'username' => 'admin4',
                'password' => '@admin1234',
                'email' => 'admin4@gmail.com',
                'role' => UserRole::USER
            ],
            [
                'id' => 5,
                'username' => 'admin5',
                'password' => '@admin1234',
                'email' => 'admin5@gmail.com',
                'role' => UserRole::USER
            ],
            [
                'id' => 6,
                'username' => 'admin6',
                'password' => '@admin1234',
                'email' => 'admin6@gmail.com',
                'role' => UserRole::USER
            ],
            [
                'id' => 7,
                'username' => 'admin7',
                'password' => '@admin1234',
                'email' => 'admin7@gmail.com',
                'role' => UserRole::USER
            ],
        ]);
    }
}
