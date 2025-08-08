<?php

namespace App\Http\Controllers\API\v1\DemandForecast;

use App\Http\Controllers\Controller;
use App\Jobs\ForestDemandJob;
use App\Services\SaleOrderDetail\SaleOrderDetailServiceInterface;
use Illuminate\Http\Request;

class DemandForecastController extends Controller
{
    protected SaleOrderDetailServiceInterface $saleOrderDetailService;

    public function __construct(SaleOrderDetailServiceInterface $saleOrderDetailService)
    {
        $this->saleOrderDetailService = $saleOrderDetailService;
    }

    // public function triggerForecast(Request $request)
    // {
    //     $productId = $request->input('product_id');
    //     $productId = $request->query('product_id');
    //     $productId = 2;
    //     ForestDemandJob::dispatch($productId);
    //     return response()->json(['message' => 'Yêu cầu dự đoán đã được gửi.'], 202);
    // }


}
