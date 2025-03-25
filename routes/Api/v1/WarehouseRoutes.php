<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Warehouse\WarehouseController;
use Illuminate\Support\Facades\Route;

class WarehouseRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::get('warehouse/search', [WarehouseController::class, 'search']);
            Route::apiResource('warehouse', WarehouseController::class);
        });
    }
}
