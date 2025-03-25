<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Country\CountryController;
use Illuminate\Support\Facades\Route;

class CountryRoutes
{
    public static function routes()
    {
        return Route::prefix('country')->middleware('auth')->group(function () {
            Route::get('/search', [CountryController::class, 'search']);
            Route::post('/', [CountryController::class, 'store']);
        });
    }
}
