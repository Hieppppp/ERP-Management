<?php

namespace Routes\Web;

use App\Http\Controllers\WEB\Province\ProvinceController;
use Illuminate\Support\Facades\Route;

class ProvinceRoutes
{
    public static function routes()
    {
        return Route::resource('province', ProvinceController::class)->middleware(['auth']);
    }
}
