<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Position\PositionController;
use Illuminate\Support\Facades\Route;

class PositionRoutes
{
    public static function routes()
    {
        return Route::resource('position', PositionController::class)->middleware(['auth', "permission:" . implode(",", [Permission::POSITION])]);
    }
}
