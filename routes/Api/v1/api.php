<?php

use Illuminate\Support\Facades\Route;
use Routes\Api\v1\AuthRoutes;
use Routes\Api\v1\BatchRoutes;
use Routes\Api\v1\CategoryRoutes;
use Routes\Api\v1\CityRoutes;
use Routes\Api\v1\ProductRoutes;
use Routes\Api\v1\CountryRoutes;
use Routes\Api\v1\DashboardRoutes;
use Routes\Api\v1\InventoryRoutes;
use Routes\Api\v1\LogRoutes;
use Routes\Api\v1\ProvinceRoutes;
use Routes\Api\v1\PurchaseOrderRoutes;
use Routes\Api\v1\ReturnOrderRoutes;
use Routes\Api\v1\UnitRoutes;
use Routes\Api\v1\ShelveRoutes;
use Routes\Api\v1\SupplierRoutes;
use Routes\Api\v1\UserRoutes;
use Routes\Api\v1\ValidateRoutes;
use Routes\Api\v1\WarehouseRoutes;
use Routes\Api\v1\CustomerRoutes;
use Routes\Api\v1\SaleOrderRoutes;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    AuthRoutes::routes();
    UserRoutes::routes();
    ValidateRoutes::routes();
    WarehouseRoutes::routes();
    ProvinceRoutes::routes();
    CityRoutes::routes();
    UnitRoutes::routes();
    ShelveRoutes::routes();
    SupplierRoutes::routes();
    CategoryRoutes::routes();
    ProductRoutes::routes();
    CountryRoutes::routes();
    PurchaseOrderRoutes::routes();
    BatchRoutes::routes();
    LogRoutes::routes();
    DashboardRoutes::routes();
    ReturnOrderRoutes::routes();
    InventoryRoutes::routes();
    CustomerRoutes::routes();
    SaleOrderRoutes::routes();
});
