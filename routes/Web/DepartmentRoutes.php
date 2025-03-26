<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Department\DepartmentController;
use Illuminate\Support\Facades\Route;

class DepartmentRoutes
{
    public static function routes()
    {
        return Route::resource('department', DepartmentController::class)->middleware(['auth', "permission:" . implode(",", [Permission::DEPARTMENT])]);
    }
}
