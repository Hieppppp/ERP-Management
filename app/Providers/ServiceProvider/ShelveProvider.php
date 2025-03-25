<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Shelve\ShelveRepository;
use App\Repositories\Shelve\ShelveRepositoryInterface;
use App\Services\Shelve\ShelveService;
use App\Services\Shelve\ShelveServiceInterface;
use Illuminate\Support\ServiceProvider;

class ShelveProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ShelveRepositoryInterface::class, ShelveRepository::class);
        $shelveRepository = $this->app->get(ShelveRepository::class);

        $this->app->bind(ShelveServiceInterface::class, function () use ($shelveRepository) {
            return new ShelveService($shelveRepository);
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
