<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

class EmployeeRoutes
{
    public static function routes()
    {
        return Route::resource('employee', EmployeeController::class)->middleware(['auth', "permission:" . implode(",", [Permission::EMPLOYEE])]);
    }
}
