<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Unit\UnitController;
use Illuminate\Support\Facades\Route;

class UnitRoutes
{
    public static function routes()
    {
        return Route::resource('unit', UnitController::class)->middleware(['auth', "permission:" . implode(",", [Permission::UNIT])]);
    }
}
