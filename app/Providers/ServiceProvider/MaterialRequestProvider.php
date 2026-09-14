<?php
namespace App\Providers\ServiceProvider;
use App\Repositories\MaterialRequest\MaterialRequestRepository;
use App\Repositories\MaterialRequest\MaterialRequestRepositoryInterface;
use App\Services\MaterialRequest\MaterialRequestService;
use App\Services\MaterialRequest\MaterialRequestServiceInterface;
use Illuminate\Support\ServiceProvider;
class MaterialRequestProvider extends ServiceProvider { public function register(): void { $this->app->bind(MaterialRequestRepositoryInterface::class, MaterialRequestRepository::class); $this->app->bind(MaterialRequestServiceInterface::class, fn () => new MaterialRequestService($this->app->make(MaterialRequestRepository::class))); } }
