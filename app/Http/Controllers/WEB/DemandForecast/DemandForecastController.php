<?php

namespace App\Http\Controllers\WEB\DemandForecast;

use App\Http\Controllers\Controller;
use App\Jobs\ForestDemandJob;
use App\Models\DemandForecast;
use App\Services\SaleOrderDetail\SaleOrderDetailServiceInterface;
use Illuminate\Http\Request;

class DemandForecastController extends Controller
{
    protected SaleOrderDetailServiceInterface $saleOrderDetailService;

    public function __construct(SaleOrderDetailServiceInterface $saleOrderDetailService)
    {
        $this->saleOrderDetailService = $saleOrderDetailService;
    }

    public function triggerForecast(Request $request)
    {
        $productId = $request->input('product_id');
        ForestDemandJob::dispatch($productId);
        // return response()->json(['message' => 'Yêu cầu dự đoán đã được gửi.'], 202);
        // session()->flash('success', __('message.success'));
        return redirect()->back()->with('success', 'Đã gửi yêu cầu dự đoán. Vui lòng đợi vài phút!');
    }

    public function show(string $id)
    {
        $data = DemandForecast::where('product_id', $id)
            ->orderBy('forecast_date')
            ->get();
        if ($data->isEmpty()) {
            return view('pages/sale-order/analysis', ['error' => 'Chưa có dữ liệu dự đoán.', 'productId' => $id]);
        }

        return view('pages/sale-order/analysis', [
            'forecastData' => $data,
            'productId' => $id
        ]);
    }


}
