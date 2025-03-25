<?php

namespace Routes\Api\v1;

use App\Enums\Permission;
use App\Http\Controllers\API\v1\PurchaseOrder\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

class PurchaseOrderRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth'])->group(function () {
            Route::post('purchase-order/{id}/receive', [PurchaseOrderController::class, 'receiveProduct']);
            Route::post('purchase-order/{id}/send-order', [PurchaseOrderController::class, 'sendPurchaseOrder']);
            Route::post('purchase-order/{id}/cancel', [PurchaseOrderController::class, 'cancelPurchaseOrder']);
            Route::post('purchase-order/{id}/finish', [PurchaseOrderController::class, 'finishPurchaseOrder']);
            Route::post('purchase-order/{purchaseOrderId}/put-product/{productId}', [PurchaseOrderController::class, 'putProductOnShelf']);
            Route::get('receipt-in', [PurchaseOrderController::class, 'getReceipt']);
            Route::apiResource('purchase-order', PurchaseOrderController::class)->except(['show']);
        });
    }
}
