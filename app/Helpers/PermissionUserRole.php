<?php

namespace App\Helpers;

use App\Enums\UserRole as EnumsUserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionUserRole
{
    public static function checkUserRole(array|null $roles = null, User|null $user = null)
    {
        $user = empty($user) ? Auth::user() : $user;

        if ($user && $user->role === EnumsUserRole::SUPPER_ADMIN) {
            return true;
        }
        foreach ($roles as $role) {
            if ($role == $user->role) {
                return true;
            }
        }
        return false;
    }
}
