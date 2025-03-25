<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Category\CategoryController;
use Illuminate\Support\Facades\Route;

class CategoryRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::get('category/search', [CategoryController::class, 'search']);
            Route::apiResource('category', CategoryController::class);
        });
    }
}
