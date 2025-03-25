<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Category\CategoryController;
use Illuminate\Support\Facades\Route;

class CategoryRoutes
{
    public static function routes()
    {
        return Route::resource('category', CategoryController::class)->middleware(['auth', "permission:" . implode(",", [Permission::CATEGORY])]);
    }
}
