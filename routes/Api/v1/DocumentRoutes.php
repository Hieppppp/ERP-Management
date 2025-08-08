<?php

namespace Routes\Api\V1;

use App\Http\Controllers\API\v1\Document\DocumentController;
use Illuminate\Support\Facades\Route;

class DocumentRoutes
{
    public static function routes()
    {
        // return Route::middleware('auth')->group(function () {
        //     Route::apiResource('document', DocumentController::class);
        // });
        Route::apiResource('document', DocumentController::class);
    }
}
