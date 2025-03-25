<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\SaleOrder\SaleOrderRepository;
use App\Repositories\SaleOrder\SaleOrderRepositoryInterface;
use App\Services\SaleOrder\SaleOrderService;
use App\Services\SaleOrder\SaleOrderServiceInterface;
use Illuminate\Support\ServiceProvider;

class SaleOrderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SaleOrderRepositoryInterface::class, SaleOrderRepository::class);
        $saleOrderRepository = $this->app->get(SaleOrderRepository::class);

        $this->app->bind(SaleOrderServiceInterface::class, function () use ($saleOrderRepository) {
            return new SaleOrderService($saleOrderRepository);
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
