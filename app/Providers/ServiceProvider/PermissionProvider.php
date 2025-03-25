<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Services\Permission\PermissionService;
use App\Services\Permission\PermissionServiceInterface;
use Illuminate\Support\ServiceProvider;

class PermissionProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        $permissionRepository = $this->app->get(PermissionRepositoryInterface::class);

        $this->app->bind(PermissionServiceInterface::class, function () use ($permissionRepository) {
            return new PermissionService($permissionRepository);
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
