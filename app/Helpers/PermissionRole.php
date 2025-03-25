<?php

namespace App\Helpers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionRole
{
    public static function checkPermission(array|null $roles = null, User|null $user = null)
    {
        $user = empty($user) ? Auth::user() : $user;
        if (in_array($user->role, [UserRole::ADMIN, UserRole::SUPPER_ADMIN])) {
            return true;
        }
        // Check permission
        if (!empty($roles)) {
            if ($user->permissions) {
                $userPermissions = array_column($user->permissions->toArray(), 'code');
                foreach ($roles as $role) {
                    if (in_array($role, $userPermissions)) {
                        return true;
                    }
                }
            }
            return false;
        }
        return true;
    }

    /**
     * Get User Permission
     *
     * @param  User $user
     * @return array
     */
    public static function getUserPermissions(User $user): array
    {
        return array_column($user->permissions->toArray(), 'code');
    }
}
