<?php

namespace Tests\Seeder;

use App\Enums\Permission as EnumsPermission;
use App\Models\Permission;

class PermissionFactory
{
    public static function intPermissionFactory()
    {
        Permission::query()->delete();
        $permissions = EnumsPermission::getValues();
        $data = [];
        foreach ($permissions as $key => $permission) {
            $data[] = [
                'id' => $key + 1,
                'code' => $permission
            ];
        }
        return Permission::insert($data);
    }
}
