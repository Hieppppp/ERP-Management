<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Product\ProductController;
use Illuminate\Support\Facades\Route;

class ProductRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('product/select', [ProductController::class, 'select']);
            Route::resource('product', ProductController::class)->where(['product' => '[0-9]+'])->middleware(["permission:" . implode(",", [Permission::PRODUCT])]);
        });
    }
}
