<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Position\PositionRepository;
use App\Repositories\Position\PositionRepositoryInterface;
use App\Services\Position\PositionService;
use App\Services\Position\PositionServiceInterface;
use Illuminate\Support\ServiceProvider;

class PositionProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $positionRepository = $this->app->get(PositionRepository::class);

        $this->app->bind(PositionServiceInterface::class, function () use ($positionRepository) {
            return new PositionService($positionRepository);
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
