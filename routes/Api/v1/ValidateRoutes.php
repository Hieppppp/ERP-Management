<?php

namespace Routes\Api\v1;

use App\Http\Controllers\API\v1\Validate\ValidateController;
use Illuminate\Support\Facades\Route;

class ValidateRoutes
{
    public static function routes()
    {
        return Route::prefix('validation')->group(function () {
            Route::post('check-validate', [ValidateController::class, 'checkValidate']);
            Route::post('check-password', [ValidateController::class, 'checkPassword']);
        });
    }
}
