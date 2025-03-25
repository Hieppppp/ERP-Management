<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Inventory\InventoryRepositoryInterface;
use App\Services\Inventory\InventoryService;
use App\Services\Inventory\InventoryServiceInterface;
use App\Services\PurchaseOrder\PurchaseOrderService;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Support\ServiceProvider;

class InventoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $inventoryRepository = $this->app->get(InventoryRepository::class);
        $purchaseOrderService = $this->app->get(PurchaseOrderServiceInterface::class);

        $this->app->bind(InventoryServiceInterface::class, function () use ($inventoryRepository, $purchaseOrderService) {
            return new InventoryService($inventoryRepository, $purchaseOrderService);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
