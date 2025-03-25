<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Product\ProductController;
use Illuminate\Support\Facades\Route;

class ProductRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::post('/filter-product', [ProductController::class, 'index']);
            Route::post('/product/{product}', [ProductController::class, 'update']);
            Route::get('/product/{product}/inventory', [ProductController::class, 'inventory']);
            Route::apiResource('product', ProductController::class);
        });
    }
}
