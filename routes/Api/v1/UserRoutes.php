<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Http\Controllers\API\v1\User\UserController;
use Illuminate\Support\Facades\Route;

class UserRoutes
{
    public static function routes()
    {
        return Route::prefix('user')->middleware('auth')->group(function () {
            Route::get('/', [UserController::class, 'index'])->middleware('role:' . implode(",", [UserRole::ADMIN]));
            Route::delete('{id}', [UserController::class, 'delete'])->middleware('role:' . implode(",", [UserRole::ADMIN]));
            Route::post('/avatar', [UserController::class, 'updateAvatar']);
        });
    }
}
