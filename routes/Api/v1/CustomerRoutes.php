<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

class CustomerRoutes
{
    public static function routes()
    {
        return Route::prefix('customer')->middleware('auth')->group(function () {
            Route::get('search', [CustomerController::class, 'search']);
            Route::get('/', [CustomerController::class, 'index']);
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->middleware(["permission:" . implode(",", [Permission::CUSTOMER])]);
            Route::get('/{customer}', [CustomerController::class, 'show']);
        });
    }
}
