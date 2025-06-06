<?php

namespace App\Jobs;

use App\Models\DemandForecast;
use App\Services\SaleOrderDetail\SaleOrderDetailServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ForestDemandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;

    /**
     * Create a new job instance.
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     */
    public function handle(SaleOrderDetailServiceInterface $saleOrderDetailService): void
    {
        Log::info("Bắt đầu dự đoán nhu cầu cho sản phẩm ID: {$this->productId}");
        $forecast = $saleOrderDetailService->getHistoricalSales($this->productId);

        if (isset($forecast['error'])) {
            Log::warning("Không thể dự đoán cho sản phẩn ID: {$this->productId} - {$forecast['error']}");
            return;
        }

        foreach ($forecast['forecast'] as $date => $quantity) {
            DemandForecast::updateOrCreate(
            [
                'product_id' => $this->productId,
                'forecast_date' => $date,
            ],
            [
                'predicted_quantity' => $quantity,
                'trend_percentage' => $forecast['trend_percentage'],
                'recommended_quantity' => $forecast['recommended_quantity'],
            ]
        );
        }
        Log::info("Dự đoán thành công cho sản phẩm ID: {$this->productId}", $forecast);
    }
}
