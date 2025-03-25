<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Warehouse\WarehouseRepository;
use App\Repositories\Warehouse\WarehouseRepositoryInterface;
use App\Services\Warehouse\WarehouseService;
use App\Services\Warehouse\WarehouseServiceInterface;
use Illuminate\Support\ServiceProvider;

class WarehouseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        $warehouseRepository = $this->app->get(WarehouseRepository::class);

        $this->app->bind(WarehouseServiceInterface::class, function () use ($warehouseRepository) {
            return new WarehouseService($warehouseRepository);
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
