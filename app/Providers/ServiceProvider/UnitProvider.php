<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Unit\UnitRepository;
use App\Repositories\Unit\UnitRepositoryInterface;
use App\Services\Unit\UnitService;
use App\Services\Unit\UnitServiceInterface;
use Illuminate\Support\ServiceProvider;

class UnitProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
        $unitRepository = $this->app->get(UnitRepository::class);

        $this->app->bind(UnitServiceInterface::class, function () use ($unitRepository) {
            return new UnitService($unitRepository);
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
