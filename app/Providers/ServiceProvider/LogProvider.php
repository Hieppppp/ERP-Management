<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Log\LogRepository;
use App\Repositories\Log\LogRepositoryInterface;
use App\Services\Log\LogService;
use App\Services\Log\LogServiceInterface;
use Illuminate\Support\ServiceProvider;

class LogProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LogRepositoryInterface::class, LogRepository::class);
        $logRepository = $this->app->get(LogRepository::class);

        $this->app->bind(LogServiceInterface::class, function () use ($logRepository) {
            return new LogService($logRepository);
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
