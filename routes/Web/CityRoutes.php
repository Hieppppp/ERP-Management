<?php

namespace Routes\Web;

use App\Http\Controllers\WEB\City\CityController;
use Illuminate\Support\Facades\Route;

class CityRoutes
{
    public static function routes()
    {
        return Route::resource('city', CityController::class)->middleware(['auth']);
    }
}
