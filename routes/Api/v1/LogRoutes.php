<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Log\LogController;
use Illuminate\Support\Facades\Route;

class LogRoutes
{
    public static function routes()
    {
        return Route::get('/log', [LogController::class, 'index']);
    }
}
