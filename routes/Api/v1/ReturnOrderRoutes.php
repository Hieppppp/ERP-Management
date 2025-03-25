<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\ReturnOrder\ReturnOrderController;
use Illuminate\Support\Facades\Route;

class ReturnOrderRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('receipt-out', [ReturnOrderController::class, 'receiptOut']);
            Route::apiResource('return-order', ReturnOrderController::class)->except(['show', 'delete']);
        });
    }
}
