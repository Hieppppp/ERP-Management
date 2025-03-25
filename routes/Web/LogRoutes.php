<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Log\LogController;
use Illuminate\Support\Facades\Route;

class LogRoutes
{
    public static function routes()
    {
        return Route::get('log', [LogController::class, 'index'])->middleware(['auth', "permission:" . implode(",", [Permission::CATEGORY])]);
    }
}
