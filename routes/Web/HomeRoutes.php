<?php

namespace Routes\Web;

use App\Http\Controllers\WEB\Home\HomeController;
use Illuminate\Support\Facades\Route;

class HomeRoutes
{
    public static function routes()
    {
        return Route::prefix('/')->middleware(['auth'])->group(function () {
            Route::get('/', [HomeController::class, 'index']);
            Route::get('index/{locale}', [HomeController::class, 'lang'])->name('lang');
        });
    }
}
