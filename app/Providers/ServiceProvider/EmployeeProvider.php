<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Employee\EmployeeRepositoryInterface;
use App\Services\Employee\EmployeeService;
use App\Services\Employee\EmployeeServiceInterface;
use Illuminate\Support\ServiceProvider;

class EmployeeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);

        $employeeRepository = $this->app->get(EmployeeRepositoryInterface::class);

        $this->app->bind(EmployeeServiceInterface::class, function () use ($employeeRepository) {
            return new EmployeeService($employeeRepository);
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
