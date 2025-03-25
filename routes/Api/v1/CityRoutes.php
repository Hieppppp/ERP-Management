<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\City\CityController;
use Illuminate\Support\Facades\Route;

class CityRoutes
{
    public static function routes()
    {
        return Route::prefix('city')->middleware('auth')->group(function () {
            Route::get('/search', [CityController::class, 'search']);
            Route::post('/', [CityController::class, 'store']);
        });
    }
}
