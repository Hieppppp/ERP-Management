<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\City\CityRepository;
use App\Repositories\City\CityRepositoryInterface;
use App\Services\City\CityService;
use App\Services\City\CityServiceInterface;
use Illuminate\Support\ServiceProvider;

class CityProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $repository = $this->app->get(CityRepository::class);

        $this->app->bind(CityServiceInterface::class, function () use ($repository) {
            return new CityService($repository);
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
