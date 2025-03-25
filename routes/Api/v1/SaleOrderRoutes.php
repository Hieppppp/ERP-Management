<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\SaleOrder\SaleOrderController;
use Illuminate\Support\Facades\Route;

class SaleOrderRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::get('invoice', [SaleOrderController::class, 'getInvoiceList']);
            Route::get('invoice/{invoice}/pdf', [SaleOrderController::class, 'getInvoicePDF']);
            Route::post('sale-order/register-payment/{invoice}', [SaleOrderController::class, 'registerPayment']);
            Route::apiResource('sale-order', SaleOrderController::class);
            Route::post('sale-order/{id}/update-status', [SaleOrderController::class, 'updateStatus']);
            Route::post('sale-order/{id}/validate', [SaleOrderController::class, 'validateROG']);
            Route::post('sale-order/{id}/product/{product_id}/add-stock', [SaleOrderController::class, 'addStock']);
        });
    }
}
