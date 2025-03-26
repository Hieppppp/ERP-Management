<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Position\PositionController;
use Illuminate\Support\Facades\Route;

class PositionRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::apiResource('position', PositionController::class);
        });
    }
}
