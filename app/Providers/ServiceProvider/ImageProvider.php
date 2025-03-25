<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Image\ImageRepository;
use App\Repositories\Image\ImageRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ImageProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
