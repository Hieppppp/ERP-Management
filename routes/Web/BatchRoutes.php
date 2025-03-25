<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Batch\BatchController;
use App\Http\Controllers\WEB\Category\CategoryController;
use Illuminate\Support\Facades\Route;

class BatchRoutes
{
    public static function routes()
    {
        return Route::prefix('batch')->group(function () {
            Route::get('/', [BatchController::class, 'index'])->middleware(['auth', "permission:" . implode(",", [Permission::BATCH])]);
            Route::get('/{batch}', [BatchController::class, 'show'])->middleware(['auth', "permission:" . implode(",", [Permission::BATCH])]);
        });
    }
}
