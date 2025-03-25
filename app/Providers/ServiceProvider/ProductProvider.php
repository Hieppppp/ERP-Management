<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Image\ImageRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Support\ServiceProvider;

class ProductProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $productRepository = $this->app->get(ProductRepositoryInterface::class);
        $imageRepository = $this->app->get(ImageRepositoryInterface::class);

        $this->app->bind(ProductServiceInterface::class, function () use ($productRepository, $imageRepository) {
            return new ProductService($productRepository, $imageRepository);
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
