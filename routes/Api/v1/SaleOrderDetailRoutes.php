<?php

namespace Routes\Api\v1;


use App\Http\Controllers\API\v1\DemandForecast\DemandForecastController;
use Illuminate\Support\Facades\Route;

class SaleOrderDetailRoutes
{
    public static function routes()
    {
        Route::get('forecast/trigger', [DemandForecastController::class, 'triggerForecast']);
    }
}
