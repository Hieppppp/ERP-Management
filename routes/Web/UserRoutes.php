<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Http\Controllers\WEB\User\UserController;
use Illuminate\Support\Facades\Route;

class UserRoutes
{
    public static function routes()
    {
        return Route::prefix('/')->middleware(['auth'])->group(function () {
            Route::get('profile', [UserController::class, 'profile']);
            Route::post('profile', [UserController::class, 'updateProfile']);
            Route::post('profile/password', [UserController::class, 'updatePassword']);
            Route::resource('user', UserController::class)->except('show')->middleware('role:' . implode(",", [UserRole::ADMIN]))->where(['user' => '[0-9]+']);
            Route::get('user/{user}', [UserController::class, 'show']);
        });
    }
}
