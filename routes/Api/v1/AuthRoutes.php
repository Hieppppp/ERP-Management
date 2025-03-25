<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Auth\LoginController;
use Illuminate\Support\Facades\Route;

class AuthRoutes
{
    public static function routes()
    {
        return Route::prefix('auth')->group(function () {
            Route::post('/login', [LoginController::class, 'login']);
        });
    }
}
