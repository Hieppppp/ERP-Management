<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Inventory\InventoryController;
use App\Http\Controllers\WEB\Product\ProductController;
use Illuminate\Support\Facades\Route;

class InventoryRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('product/{id}/inventory', [ProductController::class, 'inventory'])->middleware(["permission:" . implode(",", [Permission::INVENTORY])]);
            Route::get('product/movement/{id}', [ProductController::class, 'movement'])->middleware(["permission:" . implode(",", [Permission::INVENTORY])]);
            Route::get('inventory/{id}/edit', [InventoryController::class, 'edit'])->middleware(["permission:" . implode(",", [Permission::INVENTORY])]);
            Route::get('inventory/select', [InventoryController::class, 'select']);
        });
    }
}
