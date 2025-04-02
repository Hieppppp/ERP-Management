<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

class EmployeeRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::apiResource('employee', EmployeeController::class);
        });
    }
}
