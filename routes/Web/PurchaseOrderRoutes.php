<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\PurchaseOrder\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

class PurchaseOrderRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth', "permission:" . implode(",", [Permission::PURCHASE_ORDER])])->group(function () {
            Route::get('receipt', [PurchaseOrderController::class, 'receipt']);
            Route::get('purchase-order/{id}/receive', [PurchaseOrderController::class, 'receive']);
            Route::get('purchase-order/{purchaseOrder}/product/{product}', [PurchaseOrderController::class, 'putProductOnShelf']);
            Route::resource('purchase-order', PurchaseOrderController::class)->only([
                'index', 'show', 'create', 'edit'
            ]);
        });
    }
}
