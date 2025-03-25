<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Shelve\ShelveController;
use Illuminate\Support\Facades\Route;

class ShelveRoutes
{
    public static function routes()
    {
        return Route::apiResource('shelve', ShelveController::class);
    }
}
