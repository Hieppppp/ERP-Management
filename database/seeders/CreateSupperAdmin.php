<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateSupperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'first_name' => 'admin',
            'last_name' => '1',
            'username' => 'admin',
            'password' => Hash::make('xemmex!@#'),
            'role' => UserRole::SUPPER_ADMIN,
            'email' => 'admin@gmail.com'
        ]);
    }
}
