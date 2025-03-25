<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\User\UserService;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $userRepository = $this->app->get(UserRepositoryInterface::class);
        $permissionRepository = $this->app->get(PermissionRepositoryInterface::class);

        $this->app->bind(UserServiceInterface::class, function () use ($userRepository, $permissionRepository) {
            return new UserService($userRepository, $permissionRepository);
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
