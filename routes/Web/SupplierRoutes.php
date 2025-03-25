<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Supplier\SupplierController;
use Illuminate\Support\Facades\Route;

class SupplierRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('supplier/select', [SupplierController::class, 'select']);
            Route::resource('supplier', SupplierController::class)->middleware(["permission:" . implode(",", [Permission::SUPPLIER])])->where(['supplier' => '[0-9]+']);
        });
    }
}
