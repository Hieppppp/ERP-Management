<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Shelve\ShelveController;
use Illuminate\Support\Facades\Route;

class ShelveRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('shelve/select', [ShelveController::class, 'select']);
            Route::resource('shelve', ShelveController::class)->middleware(["permission:" . implode(",", [Permission::SHELVE])])->where(['shelve' => '[0-9]+']);
        });
    }
}
