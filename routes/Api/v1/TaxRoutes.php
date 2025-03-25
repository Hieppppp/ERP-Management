<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Tax\TaxController;
use Illuminate\Support\Facades\Route;

class TaxRoutes
{
    public static function routes()
    {
        return Route::middleware('auth')->group(function () {
            Route::get('tax/search', [TaxController::class, 'search']);
            Route::apiResource('tax', TaxController::class);
        });
    }
}
