<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Supplier\SupplierRepository;
use App\Repositories\Supplier\SupplierRepositoryInterface;
use App\Services\Supplier\SupplierService;
use App\Services\Supplier\SupplierServiceInterface;
use Illuminate\Support\ServiceProvider;

class SupplierProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $serviceRepository = $this->app->get(SupplierRepository::class);

        $this->app->bind(SupplierServiceInterface::class, function () use ($serviceRepository) {
            return new SupplierService($serviceRepository);
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
