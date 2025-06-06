<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\SaleOrderDetail\SaleOrderDetailRepository;
use App\Repositories\SaleOrderDetail\SaleOrderDetailRepositoryInterface;
use App\Services\SaleOrderDetail\SaleOrderDetailService;
use App\Services\SaleOrderDetail\SaleOrderDetailServiceInterface;
use Illuminate\Support\ServiceProvider;

class SaleOrderDetailProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SaleOrderDetailRepositoryInterface::class, SaleOrderDetailRepository::class);
        $saleOrderDetailRepository = $this->app->get(SaleOrderDetailRepository::class);

        $this->app->bind(SaleOrderDetailServiceInterface::class, function () use ($saleOrderDetailRepository) {
            return new SaleOrderDetailService($saleOrderDetailRepository);
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
