<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Province\ProvinceController;
use Illuminate\Support\Facades\Route;

class ProvinceRoutes
{
    public static function routes()
    {
        return Route::prefix('province')->middleware('auth')->group(function () {
            Route::get('/search', [ProvinceController::class, 'search']);
            Route::post('/', [ProvinceController::class, 'store']);
        });
    }
}
