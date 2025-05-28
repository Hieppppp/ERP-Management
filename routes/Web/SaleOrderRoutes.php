<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\SaleOrder\SaleOrderController;
use Illuminate\Support\Facades\Route;

class SaleOrderRoutes
{
    public static function routes()
    {
        return Route::middleware(['auth', "permission:" . implode(",", [Permission::SALE_ORDER])])->group(function () {
            Route::get('sale-order/export', [SaleOrderController::class, 'exportRevenueReport'])->name('reports.export');
            Route::get('invoice', [SaleOrderController::class, 'invoice']);
            Route::get('invoice/{invoice}', [SaleOrderController::class, 'invoiceDetail']);
            Route::get('sale-order/register-payment/{invoice}', [SaleOrderController::class, 'registerPayment']);
            Route::resource('sale-order', SaleOrderController::class)->only([
                'index',
                'show',
                'create',
                'edit'
            ]);
            Route::get('sale-order/{id}/receipt', [SaleOrderController::class, 'receipt'])->middleware(["permission:" . implode(",", [Permission::SALE_ORDER])]);
            Route::get('sale-order/{id}/product/{product_id}/select-stock', [SaleOrderController::class, 'selectStock']);

        });
    }
}
