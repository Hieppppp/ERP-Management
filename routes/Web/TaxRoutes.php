<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Tax\TaxController;
use Illuminate\Support\Facades\Route;

class TaxRoutes
{
    public static function routes()
    {
        return Route::resource('tax', TaxController::class)->middleware(['auth', "permission:" . implode(",", [Permission::TAX])]);
    }
}
