<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Tax\TaxRepository;
use App\Repositories\Tax\TaxRepositoryInterface;
use App\Services\Tax\TaxService;
use App\Services\Tax\TaxServiceInterface;
use Illuminate\Support\ServiceProvider;

class TaxProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaxRepositoryInterface::class, TaxRepository::class);

        $taxRepository = $this->app->get(TaxRepositoryInterface::class);

        $this->app->bind(TaxServiceInterface::class, function () use ($taxRepository) {
            return new TaxService($taxRepository);
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
