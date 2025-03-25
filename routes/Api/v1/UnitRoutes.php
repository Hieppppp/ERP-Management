<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Unit\UnitController;
use Illuminate\Support\Facades\Route;

class UnitRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::get('unit/search', [UnitController::class, 'search']);
            Route::apiResource('unit', UnitController::class);
        });
    }
}
