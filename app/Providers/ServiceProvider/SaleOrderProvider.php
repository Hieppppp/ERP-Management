<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Document\DocumentRepository;
use App\Repositories\SaleOrder\SaleOrderRepository;
use App\Repositories\SaleOrder\SaleOrderRepositoryInterface;
use App\Services\Document\DocumentServiceInterface;
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
        $documentService = $this->app->get(DocumentServiceInterface::class);

        $this->app->bind(SaleOrderServiceInterface::class, function () use ($saleOrderRepository, $documentService) {
            return new SaleOrderService($saleOrderRepository, $documentService);
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
