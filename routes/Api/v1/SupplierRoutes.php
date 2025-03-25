<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Supplier\SupplierController;
use Illuminate\Support\Facades\Route;

class SupplierRoutes
{
    public static function routes()
    {
        return Route::prefix('supplier')->middleware('auth')->group(function () {
            Route::get('search', [SupplierController::class, 'search']);
            Route::get('/', [SupplierController::class, 'index']);
            Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->middleware(['permission:' . Permission::SUPPLIER]);
            Route::get('/{supplier}', [SupplierController::class, 'show']);
        });
    }
}
