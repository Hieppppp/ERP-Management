<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

class DashboardRoutes
{
    public static function routes()
    {
        return Route::prefix('dashboard')->middleware('auth')->group(function () {
            Route::get('/current-stock', [DashboardController::class, 'currentStock']);
            Route::get('/low-stock', [DashboardController::class, 'lowStock']);
            Route::get('/recent-order', [DashboardController::class, 'recentOrders']);
            Route::get('/top-selling', [DashboardController::class, 'topSelling']);
            Route::get('/statistic', [DashboardController::class, 'statistic']);
            Route::get('/pending-order', [DashboardController::class, 'pendingOrder']);
        });
    }
}
