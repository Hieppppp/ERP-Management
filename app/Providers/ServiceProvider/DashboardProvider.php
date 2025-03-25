<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use App\Services\Dashboard\DashboardService;
use App\Services\Dashboard\DashboardServiceInterface;
use Illuminate\Support\ServiceProvider;

class DashboardProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $repository = $this->app->get(DashboardRepositoryInterface::class);

        $this->app->bind(DashboardServiceInterface::class, function () use ($repository) {
            return new DashboardService($repository);
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
