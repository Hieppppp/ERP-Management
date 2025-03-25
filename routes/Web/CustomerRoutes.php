<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

class CustomerRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::resource('customer', CustomerController::class)->middleware(["permission:" . implode(",", [Permission::CUSTOMER])])->where(['customer' => '[0-9]+']);
            Route::get('customer/select', [CustomerController::class, 'select']);
        });
    }
}
