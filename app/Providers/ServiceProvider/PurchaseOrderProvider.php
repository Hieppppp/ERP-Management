<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\PurchaseOrder\PurchaseOrderRepository;
use App\Repositories\PurchaseOrder\PurchaseOrderRepositoryInterface;
use App\Services\PurchaseOrder\PurchaseOrderService;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Support\ServiceProvider;

class PurchaseOrderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PurchaseOrderRepositoryInterface::class, PurchaseOrderRepository::class);
        $purchaseOrderRepository = $this->app->get(PurchaseOrderRepository::class);

        $this->app->bind(PurchaseOrderServiceInterface::class, function () use ($purchaseOrderRepository) {
            return new PurchaseOrderService($purchaseOrderRepository);
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
