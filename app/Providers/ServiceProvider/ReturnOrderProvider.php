<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\ReturnOrder\ReturnOrderRepository;
use App\Repositories\ReturnOrder\ReturnOrderRepositoryInterface;
use App\Services\ReturnOrder\ReturnOrderService;
use App\Services\ReturnOrder\ReturnOrderServiceInterface;
use Illuminate\Support\ServiceProvider;

class ReturnOrderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ReturnOrderRepositoryInterface::class, ReturnOrderRepository::class);
        $returnOrderRepository = $this->app->get(ReturnOrderRepository::class);

        $this->app->bind(ReturnOrderServiceInterface::class, function () use ($returnOrderRepository) {
            return new ReturnOrderService($returnOrderRepository);
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
