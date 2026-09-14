<?php
namespace Routes\Api\v1;
use App\Http\Controllers\API\v1\MaterialRequest\MaterialRequestController;
use Illuminate\Support\Facades\Route;
class MaterialRequestRoutes { public static function routes() { Route::middleware(['auth'])->prefix('material-requests')->group(function () { Route::post('/',[MaterialRequestController::class,'store']); Route::post('{id}/submit',[MaterialRequestController::class,'submit']); Route::post('{id}/approve',[MaterialRequestController::class,'approve']); Route::post('{id}/reject',[MaterialRequestController::class,'reject']); Route::post('{id}/issues',[MaterialRequestController::class,'issue']); }); } }
