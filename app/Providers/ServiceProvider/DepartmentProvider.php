<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Services\Department\DepartmentService;
use App\Services\Department\DepartmentServiceInterface;
use Illuminate\Support\ServiceProvider;

class DepartmentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $departmentRepository = $this->app->get(DepartmentRepository::class);

        $this->app->bind(DepartmentServiceInterface::class, function () use ($departmentRepository) {
            return new DepartmentService($departmentRepository);
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
