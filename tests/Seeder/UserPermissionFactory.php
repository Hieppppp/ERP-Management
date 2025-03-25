<?php

namespace Tests\Seeder;

use App\Models\UserPermission;

class UserPermissionFactory
{
    public static function intUserPermissionFactory()
    {
        return UserPermission::insert([[
            'user_id' => 3,
            'permission_id' => 1
        ]]);
    }
}
