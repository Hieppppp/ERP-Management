<?php

namespace Routes\Web;

use App\Http\Controllers\WEB\Country\CountryController;
use Illuminate\Support\Facades\Route;

class CountryRoutes
{
    public static function routes()
    {
        return Route::resource('country', CountryController::class)->middleware(['auth']);
    }
}
