<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Department\DepartmentController;
use Illuminate\Support\Facades\Route;

class DepartmentRoutes
{
    public static function routes()
    {
       
        return Route::middleware('auth')->group(function () {
            Route::get('department/search', [DepartmentController::class, 'search']);
            Route::apiResource('department', DepartmentController::class);
        });
    }
}
