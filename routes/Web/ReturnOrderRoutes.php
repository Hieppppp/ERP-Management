<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\ReturnOrder\ReturnOrderController;
use Illuminate\Support\Facades\Route;

class ReturnOrderRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth', "permission:" . implode(",", [Permission::PURCHASE_ORDER])])->group(function () {
            Route::get('purchase-order/{id}/return', [ReturnOrderController::class, 'create']);
            Route::get('return-order/{id}/confirm', [ReturnOrderController::class, 'confirm'])->name('return-order.confirm');
            Route::get('return-order/{id}/detail', [ReturnOrderController::class, 'show'])->name('return-order.show');
        });
    }
}
