<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Country\CountryRepository;
use App\Repositories\Country\CountryRepositoryInterface;
use App\Services\Country\CountryService;
use App\Services\Country\CountryServiceInterface;
use Illuminate\Support\ServiceProvider;

class CountryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $repository = $this->app->get(CountryRepository::class);

        $this->app->bind(CountryServiceInterface::class, function () use ($repository) {
            return new CountryService($repository);
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
