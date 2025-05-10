<?php

namespace App\Providers\ServiceProvider;

use App\Repositories\Document\DocumentRepository;
use App\Repositories\Document\DocumentRepositoryInterface;
use App\Services\Document\DocumentService;
use App\Services\Document\DocumentServiceInterface;
use Illuminate\Support\ServiceProvider;

class DocumentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DocumentRepositoryInterface::class, DocumentRepository::class);
        $documentRepository = $this->app->get(DocumentRepository::class);

        $this->app->bind(DocumentServiceInterface::class, function () use ($documentRepository) {
            return new DocumentService($documentRepository);
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
