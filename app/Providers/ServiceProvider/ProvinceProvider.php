<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Province\ProvinceRepositoryInterface;
use App\Services\Province\ProvinceService;
use App\Services\Province\ProvinceServiceInterface;
use Illuminate\Support\ServiceProvider;

class ProvinceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $provinceRepository = $this->app->get(ProvinceRepository::class);

        $this->app->bind(ProvinceServiceInterface::class, function () use ($provinceRepository) {
            return new ProvinceService($provinceRepository);
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
