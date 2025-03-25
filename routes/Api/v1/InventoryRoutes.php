<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Inventory\InventoryController;
use Illuminate\Support\Facades\Route;

class InventoryRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::apiResource('inventory', InventoryController::class);
            Route::post('product/movement/{productLocationId}', [InventoryController::class, 'movement']);
        });
    }
}
