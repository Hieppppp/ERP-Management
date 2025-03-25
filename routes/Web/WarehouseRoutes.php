<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Warehouse\WarehouseController;
use Illuminate\Support\Facades\Route;

class WarehouseRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('warehouse/select', [WarehouseController::class, 'select']);
            Route::resource('warehouse', WarehouseController::class)->middleware(["permission:" . implode(",", [Permission::WAREHOUSE])])->where(['warehouse' => '[0-9]+']);
        });
    }
}
