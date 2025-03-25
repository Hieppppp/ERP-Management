<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\Batch\BatchController;
use Illuminate\Support\Facades\Route;

class BatchRoutes
{
    public static function routes()
    {
        return Route::get('batch', [BatchController::class, 'index']);
    }
}
